@extends('layouts.app')
@section('script')
<script>
    function disableDates() {
        var inputDate = document.getElementById("inpdate");
        var disabledDates = ["2023-06-15", "2023-06-20", "2023-06-25"]; // Tanggal yang ingin dinonaktifkan

        inputDate.addEventListener("input", function() {
            var selectedDate = inputDate.value;
            if (disabledDates.includes(selectedDate)) {
                inputDate.value = ""; // Menghapus tanggal yang tidak diizinkan
                alert("Tanggal ini tidak tersedia untuk dipilih. Silakan pilih tanggal lain.");
            }
        });
    }
</script>
@endsection
@section('title')
Data Penjualan -
@endsection
@section('content')
@csrf
<div class="smw-card">
    <div class=" row">
        <div class="col-lg-12 col-md-12">
            <div class="smw-card-header"> <i class="fa fa-wpforms mr-1 i-orange" aria-hidden="true"></i>
                Data Penjualan
            </div>
            <div class="d-flex justify-content-end mt-2 mr-2">
                <input type="text" class="d-inline  search-value mr-1" placeholder="Type to search">
                <button class=" mr-1 btn-icon btn btn-light vr-search" data-val="" data-add="searchpenbem"><i class="fa fa-search" aria-hidden="true"></i></button>
            </div>
            <div class="col-sm-12 d-flex justify-content-center">
                @if(session('success'))
                <h3 class="text-dark">{{session('success')}}</h5>
                    @endif
            </div>
            <div class="smw-card-body dataTiket table-responsive">
                {!! $tiket !!}
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function refreshData(res) {
        $('.dataTiket').html(res);
    }

    function searchData() {
        var data = {
            _token: tkn(),
            search: $(".search-value").val(),
        }
        return data;
    }
    $('input[name="qty"]').on('input', function() {
        var value = $(this).val();

        if (value > 2000) {
            $(this).val(2000); // Mengatur nilai menjadi 2000 jika melebihi batas
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