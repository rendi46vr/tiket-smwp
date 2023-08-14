@extends('layouts.app')
@section('script')
<script type="text/javascript">
    <?php
    if ($tiket->id == 2) { ?>
        $(documnet).ready(function() {
            var unavailableDates = ["12-08-2023", "14-3-2012", "15-3-2012"];
            $('#input-date').datepicker({
                beforeShowDay: function(date) {
                    var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                    return [unavailableDates.indexOf(string) == -1]
                }
            });
        })
    <?php  } ?>
</script>
@endsection
@section('content')
<?php
function rupiah($angka)
{
    $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
    return $hasil_rupiah;
} ?>
<form id="order">
    @csrf
    <input type="hidden" name="uniq" value="sdndnfmfqw ehieknklsaudv19823h">
    <input type="hidden" name="jenis_tiket" value="{{$tiket->id}}">
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="smw-card">
                <div class="smw-card-header"> <i class="fa fa-wpforms mr-1 i-orange" aria-hidden="true"></i>
                    Info Tiket
                </div>
                <div class="smw-card-body">
                    <labe class="card-label">
                        Tanggal Tiket
                    </labe>
                    <div class="input-date">
                        <input type="date" @if($tiket->id == 1) class="form-control" disabled @else name="tgl" id="input-date" value="" max="2023-09-30" min="2023-08-11" class="form-control msgtgl" @endif />
                        <!-- <label for="input-date" onclick="showDatePicker()" class="date-label tes123">Mon, 10 Sep 2020</label> -->
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <labe class="card-label">
                                Total Tiket
                            </labe>
                            <div class="qty">
                                <span class="pointer kurang">
                                    <i class="fa fa-minus-circle " aria-hidden="true"></i>
                                </span>
                                <span class="quantity mr-2 ml-2">1</span>
                                <span class="pointer tambah">
                                    <i class="fa fa-plus-circle i-orange" aria-hidden="true"></i>
                                </span>
                            </div>
                            <input type="hidden" name="qty" value="1" class="msgqty">
                        </div>
                        <div class="col-6">
                            <labe class="card-label">
                                Total Harga
                            </labe>
                            <span class="harga"><span data-harga="{{$tiket->harga}}">{{$harga}}</span></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info d-flex justify-content-start m-4">
                            <p>Tiket {{$tiket->deskripsi}}</p>
                        </div>
                    </div>
                </div>
                <div class="smw-card-footer text-muted">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="smw-card">
                <div class="smw-card-header"> <i class="fa fa-wpforms mr-1 i-orange" aria-hidden="true"></i>
                    Info Pelanggan
                </div>
                <div class="smw-card-body pt-2">
                    <div class="form-group">
                        <label for="">Nama Lengkap</label>
                        <input type="text" name="name" id="" class="form-control msgname" placeholder="Nama Anda" aria-describedby="helpId">
                    </div>
                    <div class="form-group">
                        <label for="">Nomor Whatsapp</label>
                        <input type="text" name="wa" id="" class="form-control msgwa" placeholder="+62 ......" aria-describedby="helpId">
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" id="" class="form-control msgemail" placeholder="example@email.com" aria-describedby="helpId">
                    </div>

                    <div class="d-flex justify-content-center">
                        <button class="btn btn-orange mb-4 resetFalse" type="submit">Proses Pembayaran</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    function refreshData(res) {
        if (res.status) {
            window.location.href = res.href
        } else {
            c('something wrong there')
        }
    }
</script>
@endsection