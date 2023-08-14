@extends('layouts.app')

@section('style')

@endsection
@section('content')
<?php
function rupiah($angka)
{
    $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
    return $hasil_rupiah;
} ?>
<h1 class="f">Pembelian Tiket Online Event Sriwijaya <br> Lantern Festival 2023</h1>

<div class="jtiket">
    <div class="row lg-100">
        @foreach($tiket as $t)
        <div class="col-lg-6 col-md-6 col-12">
            <div class="single-tiket">
                <div class="tiket-head">
                    <span class="title">{{$t->judul}}</span>
                    <div class="price">
                        <label>Mulai Dari</label>
                        <span class="harga">{{rupiah($t->harga)}}</span>
                    </div>
                </div>
                <div class="deskripsi">
                    <label>Pilihan hari</label>
                    <span class="desc">
                        {{$t->deskripsi}}
                    </span>
                    {!!$data = $ds->cekdiskon($t->id)!!}
                </div>

                <div class="tiket-footer">
                    <span class="available"> @if($t->status >0) Available @else Unavailable @endif</span>
                    <div class="buy">
                        <span class="buy">@if($t->status > 0)<a href="form-pembelian/{{$t->slug}}"><i class="fa fa-shopping-cart mr-1" aria-hidden="true"></i>Beli</a>@else Tidak Tersedia @endif</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>


@endsection