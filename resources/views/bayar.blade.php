@extends('layouts.app')
@section('scripts')

<script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="{{env('mid_ckey')}}"></script>
@endsection
@section('content')
<?php
function rupiah($angka)
{
    $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
    return $hasil_rupiah;
} ?>
<div class="row">
    <div class="col-lg-6 col-md-6">
        <div class="smw-card">
            <div class="smw-card-header"> <i class="fa fa-wpforms mr-1 i-orange" aria-hidden="true"></i>
                Detail Pesanan
            </div>
            <div class="smw-card-body">
                <table class="table table-responsive-w-100 table-borderless">
                    <tbody>
                        <tr>
                            <td>
                                <labe class="card-label">
                                    Nama Pemesan </labe>
                            </td>
                            <td>{{$tjual->name}}

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <labe class="card-label">
                                    Hari Berlaku Tiket </labe>
                            </td>
                            <td>@if($tjual->tiket_id == 1 ) Berlaku hari Rabu, Kamis, Jumat (kecuali hari libur nasional & opening day) @else Berlaku setiap hari festival (termasuk hari libur nasional, opening day, dan closing day) @endif

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <labe class="card-label">
                                    Nomor Whatsapp
                                </labe>
                            </td>
                            <td> {{$tjual->wa}}

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <labe class="card-label">
                                    Email
                                </labe>
                            </td>
                            <td>{{$tjual->email}}

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <labe class="card-label">
                                    Total Tiket
                                </labe>
                            </td>
                            <td>{{$tjual->qty}}

                            </td>
                        </tr>

                        <tr>
                            <td>
                                <labe class="card-label">
                                    Harga Tiket
                                </labe>
                            </td>
                            <td>{{rupiah($tjual->totalbayar)}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <labe class="card-label">
                                    Biaya Layanan
                                </labe>
                            </td>
                            <td>Rp 1.000
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="smw-card-footer text-muted">
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <div class="smw-card">
            <div class="smw-card-header"> <i class="fa fa-wpforms mr-1 i-orange" aria-hidden="true"></i>
                Pembayaran
            </div>
            <div class="smw-card-body">
                <div class="d-flex justify-content-center mt-4">
                    <a href="{{url('cancel/'.$tjual->id)}}" class="btn btn-danger mb-4 mr-4">Batal</a>
                    <button class="btn btn-orange mb-4 resetFalse reqbayar" data-whatever="{{$tjual->token}}" data-ind="{{$tjual->id}}">Bayar</button>
                </div>
            </div>
            <div class="smw-card-footer text-muted">
            </div>
        </div>
    </div>
</div>


@endsection