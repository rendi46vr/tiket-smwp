    var validated = false;
    var buton_submit = true;
    var my_form, name_class,action;
    var auto_focus = false;
    $(document).ready(function(){
        // errorForm()
    $(document).on('click', '.showform', function() {
        const ini = $(this)
        const mod = $(ini.data('target'));
        mod.modal('show')
        // mod.find('form').attr('id', action)
        if(ini.data('edit')){
           edit(ini.data("uniq"),ini.data("core"));
        }
    });
    $(document).on('click', '.deldata', function() {    
        deldata($(this).data("uniq"))
    })
    //create and update
    $(document).on('click', "button[type=submit]", function(e) {
        e.preventDefault()
        const form = $(this).closest('form')
        action =  form.attr('id');
        console.log(action);
        name_class = 'App/Http/Requests/' + action;
        my_form = form
        c(form.hasClass('another'))
        if($(this).hasClass("resetFalse")){
            validate(action, refreshData, false)
        }else{
            form.hasClass('another') ? validate(action, another) : validate(action, refreshData);
        }
    });
    $(document).on('click', ".show-triger", function(e) {
      edit($(this).data('add'), '.show-data');
    });
    })
    $(document).on('click', ".action", function(e) {
        edit($(this).data('add'), $(this).data('show'));
      });
    //paginaition with search
    $(document).on("click",".vr-search", function() {
        c('ok')
        let search = $(".search-value").val()
        if (search != "") {
            $(".show-sv").text("Hasil Pencarian : " + $(".search-value").val());
        } else {
            $(".show-sv").text("");
            search = 'all-data';
        }
        if(this.dataset.val.length >0){
            doReq(this.dataset.add + "/" + search, searchData(this.dataset.val), refreshData, true)
        }else{
            doReq(this.dataset.add + "/" + search, searchData(), refreshData, true)
        }
    })
    function pagination(ini, data =null){
        doReq(ini.data("add"), data, refreshData, true);
    }
    $(document).on('click', '.mypagination', function() {
        pagination($(this), searchData() || null)
    })
    function edit(u,c) {
        doReq(u, null, (res) => {
            $(c).html(res)
            console.log(res)
        })
    }

    function deldata(u) {
        doReq(u, null, refreshData);
    }
    function validate(a,r, b=true) {
        var data = my_form.serializeArray();
        var mydata = {}
        console.log(data)

        data.push({
            name: 'class',
            value: name_class
        });

        for (var i = 0; i < data.length; i++) {
            item = data[i];
            if (item.name == '_method') {
                data.splice(i, 1);
            }
        }
        $.ajax({
            url: baseUri('validation'),
            type: 'post',
            data: $.param(data),
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    $.each(my_form.serializeArray(), function(i, field) {
                        var father = $('#' + field.name).parent('.vr-form');
                        father.removeClass('has-error');
                        father.addClass('has-success');
                        father.find('.help-block').html('');
                    });
                    validated = true;
                    buton_submit = true;
                    if (buton_submit == true) {
                        // e.preventDefault()
                        // my_form.submit();
                        $.each(my_form.serializeArray(), function(i, data) {
                            if (mydata[data.name] != null) {
                                if (Array.isArray(mydata[data.name])) {
                                    mydata[data.name].push(data.value)
                                } else {
                                    mydata[data.name] = [mydata[data.name], data.value]
                                }
                            } else {
                                mydata[data.name] = data.value;
                            }
                        })
                        const mymo = $('#' + my_form.closest('.modal').attr('id'))
                        mymo.modal('hide')
                        b ? my_form[0].reset(): '';
                        c()
                        doReq(a ,mydata,r)
                        my_form.find('.help-block').html('')
                    }
                } else {
                    var campos_error = [];
                    $.each(data.errors, function(key, data) {
                        var campo = my_form.find('.msg' + key);
                        var father = campo.parents('.vr-form');
                        var next = father.find('.help-block')
                        father.removeClass('has-success');
                        father.addClass('has-error');
                        if(next.length > 0){
                            next.html(data[0])
                        }else{
                            campo.after('<div class="help-block f12 text-danger with-errors"> '+data[0]+' </div>');
                        }
                        campos_error.push(key);
                    });
                    $.each(my_form.serializeArray(), function(i, field) {
                        if ($.inArray(field.name, campos_error) === -1) {
                            var father = my_form.find('.msg' + field.name).parent('.vr-form');
                            father.removeClass('has-error');
                            father.addClass('has-success'); 
                            father.find('.help-block').html('');
                        }
                    });

                    validated = false;
                    buton_submit = false;
                }
            },
            error: function(xhr) {
                console.log(xhr);
            }
        });
        return false;

    }
    function doReq(act, data ={_token:tkn()}, callback, load= false, ) {
        $.ajaxSetup({
            headers: { 
                'X-CSRF-TOKEN': tkn()
            }
        });
        $.ajax({
            type: 'post',
            url: baseUri(act),
            data: data,
            beforeSend: function() {
                if(load){
                    callback(loading())
                }
            },
            success: function(result) {
                callback(result)
            },
            error: function(xhr) {
                console.log(xhr);
            }
        });
    }
    function baseUri(uri = ''){
        let url =window.location.origin+"/";
        const secondURI ="http://vittindo.my.id:8083/"
        url == secondURI ? url = secondURI+"vittindo-web/public/": '';
        uri != '' ? url = url+uri:'';
        return url;
    }
    function totop(target = $(".g-area")){
        $('html, body').animate({
            scrollTop: target.offset().top
        }, 10);
    }
    function tkn(){
        return $('meta[name="csrf-token"]').attr('content');
       }
    function errorForm(){
    const inp = $('.vr-form');
    if (inp.find('.help-block').length <= 0) {
        inp.append('<div class="help-block  text-danger with-errors"></div>');
    }
}
function c(data= 'ok'){
    console.log(data)
}