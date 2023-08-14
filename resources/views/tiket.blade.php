@extends('layouts.app')

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
                                <label class="card-label">
                                    Nama Pemesan </labe>
                                    </tdl>
                            <td>{{$tjual->name}}

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="card-label">
                                    Tanggal Berlaku Tiket </labe>
                                    </tdl>
                            <td>{{$tjual->tgljual}}

                            </td>
                        </tr>
                        @if($tjual->tiket_id != 5 )
                        <tr>
                            <td>
                                <label class="card-label">
                                    Nomor Whatsapp
                                </label>
                            </td>
                            <td> {{$tjual->wa}}

                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td>
                                <label class="card-label">
                                    Email
                                </label>
                            </td>
                            <td>{{$tjual->email}}

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="card-label">
                                    Hari Berlaku Tiket </labe>
                                    </tdl>
                            <td>@if($tjual->tiket_id == 1 ) Berlaku hari Rabu, Kamis, Jumat (kecuali hari libur nasional & opening day) @else Berlaku setiap hari festival (termasuk hari libur nasional, opening day, dan closing day) @endif

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="card-label">
                                    Total Tiket
                                </label>
                            </td>
                            <td>{{$tjual->qty}}

                            </td>
                        </tr>
                        @if($tjual->status == 2)
                        <tr>
                            <td>
                                <label class="card-label">
                                    Harga Tiket
                                </label>
                            </td>
                            <td>{{rupiah($tjual->totalbayar)}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="card-label">
                                    Biaya Admin
                                </label>
                            </td>
                            <td>Rp 1.000
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <div class="smw-card">
            <div class="smw-card-header"> <i class="fa fa-wpforms mr-1 i-orange" aria-hidden="true"></i>
                Download Ticket
            </div>
            <div class="smw-card-body">
                <div class="d-flex justify-content-center ">
                    <P style="text-align: center; font-size:16px">Download tiket Mungkin membutuhkan waktu beberapa detik tergantung dari jumlah tiket </P>

                </div>
                <div class="d-flex justify-content-center ">
                    @if($tjual->status == 2)
                    <a href="{{url('belilagi')}}" class="btn btn-success mr-2 mb-4 resetFalse">Beli Lagi</a>
                    @endif
                    <a href="{{url('download/'.$tjual->id)}}" class="btn btn-orange mb-4 resetFalse">Download</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection