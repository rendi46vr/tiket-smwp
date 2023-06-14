@extends('layouts.app')

@section('content')
<div class="smw-card">
    <div class="smw-card-header"> <i class="fa fa-wpforms mr-1 i-orange" aria-hidden="true"></i>
        Qr Code
    </div>
    <div class="smw-card-body">
        <div class="d-flex justify-content-center mt-4">
            {!! $qr !!}

        </div>
        <div class="d-flex justify-content-center mt-4">
            <button class="btn btn-success mb-4 resetFalse">Download</button>
        </div>
    </div>
</div>
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
                            Tanggal Berlaku Tiket </labe>
                    </td>
                    <td>{{$tjual->tgljual}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <labe class="card-label">
                            Nomor Whatsapp
                        </labe>
                    </td>
                    <td>{{$tjual->wa}}
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
                            Total Bayar
                        </labe>
                    </td>
                    <td> <label class="date-label tes123">Rp. {{$tjual->totalbayar}}</label>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="smw-card-footer text-muted">
    </div>
</div>

@endsection