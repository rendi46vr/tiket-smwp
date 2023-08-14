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
Cetak Tket -
@endsection
@section('content')
@csrf
<div class="smw-card">
    <div class=" row">
        <div class="col-lg-4 col-md-4">
            <div class="smw-card-header"> <i class="fa fa-wpforms mr-1 i-orange" aria-hidden="true"></i>
                Kirim Tiket
            </div>
            <div class="msg" style="display:none;">
            </div>
            <div class="smw-card-body">
                <form id="kirimtiket">
                    @csrf
                    <input type="hidden" name="uniq" value="129ewqweqe021">
                    <div class="form-group vr-form">
                        <label for="">Jenis Tiket</label>
                        <select name="jenis_tiket" id="" class="form-control jtiket msgjenis_tiket">
                            <option hidden disabled selected>Jenis Tiket</option>
                            <option value="1">Regular Day</option>
                            <option value="2">Premium Day</option>
                        </select>
                    </div>
                    <div class="form-group vr-form">
                        <label for="">nama Penerima </label>
                        <input type="name" name="name" class="form-control msgname" placeholder="Nama Penerima..">
                    </div>
                    <div class="form-group vr-form">
                        <label for="">Email Penerima </label>
                        <input type="email" name="email" class="form-control msgemail" placeholder="email">
                    </div>
                    <div class="form-group vr-form">
                        <label for="">Jumlah Tiket</label>
                        <input type="number" name="qty" max="1000" class="form-control msgqty" placeholder="1000" aria-describedby="helpId">
                    </div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-orange mb-4 " type="submit">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-8 col-md-8">
            <div class="smw-card-header"> <i class="fa fa-wpforms mr-1 i-orange" aria-hidden="true"></i>
                Tiket Terkirim
            </div>
            <div class="smw-card-body dataTiket">
                {!! $tiket !!}
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function refreshData(res) {
        if (res.notif) {
            const fs1 = $('.msg');
            fs1.html('<div class="success-message mt-1 mb-1"> Tiket dikirim </div>');
            fs1.show('fast')
            setTimeout(function() {
                fs1.hide('slow')
            }, 3000);
        }
        $('.dataTiket').html(res.data);
    }

    function searchData() {
        return null
    }
    $('input[name="qty"]').on('input', function() {
        var value = $(this).val();

        if (value > 2000) {
            $(this).val(2000); // Mengatur nilai menjadi 2000 jika melebihi batas
        }
    });
</script>

@endsection