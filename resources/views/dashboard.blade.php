@extends('layouts.app')
@section('title')
Setting -
@endsection
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
@section('content')
@csrf

<div class="smw-card">
    <div class=" row">
        <div class="col-lg-5 col-md-5">
            <div class="smw-card-header"> <i class="fa fa-wpforms mr-1 i-orange" aria-hidden="true"></i>
                Setting Diskon
            </div>
            <div class="msg1" style="display:none;">
            </div>
            <div class="smw-card-body">

                <form action="" id="adddiskon">
                    @csrf
                    <div id="errorContainer" style="display: none;">
                        <ul id="errorList"></ul>
                    </div>

                    <div class="form-group vr-form">
                        <label style="font-size: 16px;" for="jenisTiket">Jenis Tiket:</label>
                        <select class="form-control msgjenisTiket" id="jenisTiket" name="jenisTiket">
                            <option value="2">Premium Day</option>
                            <option value="1">Regular Day</option>
                        </select>
                        <div class="help-block  f12  text-danger with-errors jtt"></div>
                    </div>

                    <div class="form-group vr-form">
                        <label style="font-size: 16px;" for="tanggalMulai">Tanggal Mulai:</label>
                        <input type="date" class="form-control msgtanggalMulai" id="tanggalMulai" min="2023-07-30" max="2023-09-30" name="tanggalMulai" required>
                    </div>
                    <div class="form-group vr-form">
                        <label style="font-size: 16px;" for="tanggalAkhir">Tanggal Akhir:</label>
                        <input type="date" class="form-control msgtanggalAkhir" id="tanggalAkhir" min="2023-07-30" max="2023-09-30" name="tanggalAkhir" required>
                    </div>
                    <div class="form-group vr-form">
                        <label style="font-size: 16px;" for="minimalQuantity">Minimal Quantity:</label>
                        <input type="number" class="form-control msgminimalQuantity" id="minimalQuantity" name="minimalQuantity" required>
                    </div>
                    <div class="form-group vr-form row">
                        <div class="col">
                            <label style="font-size: 16px;" for="diskonPersen">Diskon Persen:</label>
                            <input type="number" class="form-control msgdiskonPersen" id="diskonPersen" name="diskonPersen" step="0.01" min="0" max="100">
                        </div>
                        <div class="col">
                            <label style="font-size: 16px;" for="nilaiDiskon">Nilai Diskon:</label>
                            <input type="number" class="form-control msgnilaiDiskon" id="nilaiDiskon" name="nilaiDiskon" min="0">
                        </div>
                    </div>
                    <div class="form-group vr-form">
                        <button class="btn btn-orange resetFalse" name="submit" type="submit">Buat</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-7 col-md-7">
            <div class="smw-card-header"> <i class="fa fa-wpforms mr-1 i-orange" aria-hidden="true"></i>
                Data Diskon
            </div>
            <div class="smw-card-body table-diskon">
                {!! $diskon !!}
            </div>
        </div>

    </div>

</div>
<div class="smw-card">
    <div class="col-lg-6 col-md-6">
        <div class="smw-card-header"> <i class="fa fa-wpforms mr-1 i-orange" aria-hidden="true"></i>
            Setting Penjualan Tiket
        </div>
        <div class="smw-card-body">
            <div class="msg" style="display:none;">
            </div>
            <form id="settiket">
                @csrf
                <table class="table table-responsive ft table-bordered">
                    <thead>
                        <tr>
                            <th>Paket</th>
                            <th>Harga</th>
                            <th>Batas Penjualan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $d)
                        <tr>
                            <td>
                                {{$d->judul}}
                            </td>
                            <td><input type="text" name="harga{{$d->slug}}" value="{{$d->harga}}" class="form-control msgharga{{$d->slug}} m-form col-lg-7 col-md-6 col-sm-5 col-xs-5"></td>
                            <td><input type="number" name="batas{{$d->slug}}" class="form-control msgbatas{{$d->slug}} m-form col-lg-7 col-md-6 col-sm-5 col-xs-5" size="2" value="{{$d->batas}}"></td>
                            <td>
                                <div class="custom-control custom-switch " style="margin-left: auto;">
                                    <input type="checkbox" class="custom-control-input msgstatus{{$d->slug}}" name="status{{$d->slug}}" value="1" @if($d->status > 0) checked @endif id="{{$d->slug}}">
                                    <label class="custom-control-label" for="{{$d->slug}}"></label>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                <div class="form-group">
                    <button class="btn btn-orange resetFalse" name="submit" type="submit">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
    function refreshData(res) {
        const fs = $('.msg');
        if (res.status) {
            const fs1 = $('.msg1');
            fs1.html('<div class="success-message mt-1 mb-1"> Disimpan </div>');
            fs1.show('fast')
            setTimeout(function() {
                fs1.hide('slow')
            }, 3000);

            $('.table-diskon').html(res.diskon)
        } else {
            fs.html('<div class="success-message mt-1 mb-1"> Disimpan </div>');
            fs.show('fast')
            setTimeout(function() {
                fs.hide('slow')
            }, 3000);
        }

    }
</script>
@endsection