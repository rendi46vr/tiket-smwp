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
        <div class="col-lg-12 col-md-12">
            <div class="smw-card-header"> <i class="fa fa-wpforms mr-1 i-orange" aria-hidden="true"></i>
                Setting Penjualan Tiket
            </div>
            <div class="smw-card-body">
                <div class="msg" style="display:none;">
                </div>
                <form id="settiket">
                    @csrf
                    <table class="table table-responsive-w-100 ft table-bordered">
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
</div>


<script type="text/javascript">
    function refreshData(res) {
        const fs = $('.msg');
        if (res) {
            fs.html('<div class="success-message mt-1 mb-1"> Disimpan </div>');
            fs.show('fast')
            setTimeout(function() {
                fs.hide('slow')
            }, 3000);
        }
    }
</script>
@endsection