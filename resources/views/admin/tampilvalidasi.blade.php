@extends('layouts.app')

@section('style')
Data Tiket Valid
@endsection
@section('style')

@endsection
@section('content')
<div class="smw-card">
    <div class=" row">
        <div class="col-lg-4 col-md-4">
            <div class="smw-card-header"> <i class="fa fa-wpforms mr-1 i-orange" aria-hidden="true"></i>
                Cetak tiket valid
            </div>
            <div class="col-sm-12 d-flex justify-content-center">
                @if(session('success'))
                <h3 class="text-dark">{{session('success')}}</h5>
                    @endif
            </div>
            <div class="smw-card-body  table-responsive">
                <form id="tamvalhari">
                    @csrf
                    <input type="hidden" name="uniq" value="129ewqweqe021">
                    <div class="form-group">
                        <label for="">Dari </label>
                        <input type="date" name="dari" id="input-date" max="2023-09-30" min="2023-07-25" id="" class="form-control  msgtgl" placeholder="+62 ......" aria-describedby="helpId">
                    </div>
                    <div class="form-group">
                        <label for="">Sampai</label>
                        <input type="date" name="sampai" id="input-date" max="2023-09-30" min="2023-07-25" id="" class="form-control  msgtgl" placeholder="+62 ......" aria-describedby="helpId">
                    </div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-orange mb-4 resetFalse " type="submit">Cek</button>
                    </div>
                </form>
            </div>


        </div>
        <div class="col-lg-6 col-md-6">
            <div class="smw-card-header"> <i class="fa fa-wpforms mr-1 i-orange" aria-hidden="true"></i>
                Data Tiket Tervalidasi
            </div>
            <div class="col-sm-12 d-flex justify-content-center">
                @if(session('success'))
                <h3 class="text-dark">{{session('success')}}</h5>
                    @endif
            </div>
            <div class="smw-card-body dataTiket table-responsive">
                {!! $table !!}
            </div>
        </div>
    </div>

</div>
</div>
<script type="text/javascript">
    $(document).on('click', '.tamvalhari', function() {
        if (this.checked) {
            console.log($(this).attr('id'));
            doReq('.tamvalhari/' + $(this).attr('id'), {
                _token: "{{ csrf_token() }}",
                cetak: $(this).val()
            }, () => {});
        } else {
            doReq('.tamvalhari/' + $(this).attr('id'), {
                _token: "{{ csrf_token() }}",
                cetak: 0
            }, () => {});
        }
    });

    function refreshData(res) {
        $('.dataTiket').html(res);
    }

    function searchData() {
        return null;
    }
    $('input[name="qty"]').on('input', function() {
        var value = $(this).val();
        if (value > 2000) {
            $(this).val(2000);
        }
    });
    $(document).on('change', '.jtiket', function() {
        if (this.value == 1) {
            $('.tgl').attr('disabled', '').val('')
        } else {
            $('.tgl').removeAttr('disabled')
        }
    })
</script>
@endsection