@extends('layouts.app')
@section('scripts')

<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SET_YOUR_CLIENT_KEY_HERE"></script>
@endsection
@section('content')
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
                                    Total Bayar
                                </labe>
                            </td>
                            <td>Rp. {{$tjual->totalbayar}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="smw-card-footer text-muted">
            </div>
        </div>
    </div>
    <div class="col-6 col-md-6">
        <div class="smw-card">
            <div class="smw-card-header"> <i class="fa fa-wpforms mr-1 i-orange" aria-hidden="true"></i>
                Pembayaran
            </div>
            <div class="smw-card-body">
                <div class="d-flex justify-content-center mt-4">
                    <button class="btn btn-orange mb-4 resetFalse reqbayar" data-whatever="{{$tjual->token}}" data-ind="{{$tjual->id}}">Bayar</button>
                </div>
            </div>
            <div class="smw-card-footer text-muted">
            </div>
        </div>
    </div>
</div>



@endsection