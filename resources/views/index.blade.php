@extends('layouts.app')

@section('content')


<h1 class="f">Pembelian Tiket Online Acara SLF <br> Sekolah Maitreyawira</h1>

<div class="jtiket">
    <div class="row lg-100">
        @foreach($tiket as $t)
        <div class="col-lg-6 col-md-6 col-12">
            <div class="single-tiket">
                <div class="tiket-head">
                    <span class="title">{{$t->judul}}</span>
                    <div class="price">
                        <label>Mulai Dari</label>
                        <span class="harga">Rp. {{$t->harga}}</span>
                    </div>
                </div>
                <div class="deskripsi">
                    <label>Pilihan hari</label>
                    <span class="desc">
                        {{$t->deskripsi}}
                    </span>
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
        <div class="col-lg-6 col-md-6 col-12">
            <div class="single-tiket">
                <div class="tiket-head">
                    <span class="title">Reguler Day</span>
                    <div class="price">
                        <label>Mulai Dari</label>
                        <span class="harga">Rp. 12.500</span>
                    </div>
                </div>
                <div class="deskripsi">
                    <label>Pilihan hari</label>
                    <span class="desc">
                        Rabu, Kamis & Jumat
                    </span>
                </div>

                <div class="tiket-footer">
                    <span class="available">Available</span>
                    <div class="buy">
                        <span class="buy">Buy</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection