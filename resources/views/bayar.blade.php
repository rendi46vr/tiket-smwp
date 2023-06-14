@extends('layouts.app')
@section('scripts')

<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SET_YOUR_CLIENT_KEY_HERE"></script>
@endsection
@section('content')
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
                    <td><label class="date-label tes123">{{$tjual->name}}
                            </labe>
                    </td>
                </tr>
                <tr>
                    <td>
                        <labe class="card-label">
                            Tanggal Berlaku Tiket </labe>
                    </td>
                    <td><label class="date-label tes123">{{$tjual->tgljual}}
                            </labe>
                    </td>
                </tr>
                <tr>
                    <td>
                        <labe class="card-label">
                            Nomor Whatsapp
                        </labe>
                    </td>
                    <td> <label class="date-label tes123">{{$tjual->wa}}
                            </labe>
                    </td>
                </tr>
                <tr>
                    <td>
                        <labe class="card-label">
                            Email
                        </labe>
                    </td>
                    <td><label class="date-label tes123">{{$tjual->email}}
                            </labe>
                    </td>
                </tr>
                <tr>
                    <td>
                        <labe class="card-label">
                            Total Tiket
                        </labe>
                    </td>
                    <td><label class="date-label tes123">{{$tjual->totalbayar}}
                            </labe>
                    </td>
                </tr>
                <tr>
                    <td>
                        <labe class="card-label">
                            <label class="date-label tes123"> Total Bayar
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
<div class="smw-card">
    <div class="smw-card-header"> <i class="fa fa-wpforms mr-1 i-orange" aria-hidden="true"></i>
        Pembayaran
    </div>
    <div class="smw-card-body">
        <div class="d-flex justify-content-center mt-4">
            <button class="btn btn-orange mb-4 resetFalse reqbayar" data-whatever="{{$tjual->token}}">Bayar</button>
        </div>
    </div>
    <div class="smw-card-footer text-muted">
    </div>
</div>

@endsection