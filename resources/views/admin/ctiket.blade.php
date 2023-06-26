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
                Cetak Tiket
            </div>
            <div class="smw-card-body">
                <form id="cetakTiket">
                    @csrf
                    <input type="hidden" name="uniq" value="129ewqweqe021">
                    <div class="form-group">
                        <label for="">Jenis Tiket</label>
                        <select name="jenis_tiket" id="" class="form-control jtiket msgjenis_tiket">
                            <option hidden disabled selected>Jenis Tiket</option>
                            <option value="1">Regular Day</option>
                            <option value="2">Premium Day</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Tanggal </label>
                        <input type="date" name="tgl" id="input-date" max="2023-09-30" min="2023-08-11" id="" class="form-control tgl msgtgl" placeholder="+62 ......" aria-describedby="helpId">
                    </div>
                    <div class="form-group">
                        <label for="">Jumlah Tiket</label>
                        <input type="number" name="qty" max="1000" id="" class="form-control msgqty" placeholder="1000" aria-describedby="helpId">
                    </div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-orange mb-4 " type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-8 col-md-8">
            <div class="smw-card-header"> <i class="fa fa-wpforms mr-1 i-orange" aria-hidden="true"></i>
                Tiket Tercetak
            </div>
            <div class="smw-card-body dataTiket">
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
        return null
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