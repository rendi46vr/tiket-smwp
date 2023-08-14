<?php
function rupiah($angka)
{
    $hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
    return $hasil_rupiah;
} ?>
<table class="table ftc table-responsive-w-100 table-bordered" id="dataCetak">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>No telepon</th>
            <th>Tanggal Pembelian</th>
            <th> Total Tagihan</th>
            <th>Jenis tiket</th>
            <th>Qty</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tiket as $t)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$t->name}}</td>
            <td>{{$t->email}}</td>
            <td>{{$t->wa}}</td>
            <td>{{$t->created_at->format('Y-m-d')}}</td>
            <td>{{rupiah($t->totalbayar)}}</td>
            <td>{{$t->tiket_id == 1 ? "Regular Day" : "Premium Day"}}</td>
            <td>{{$t->qty}}</td>
            <td>@if($t->status == 0 ) <button class="btn rounded-pill btn-warning"> Belum Bayar</button> @else<button class="btn rounded-pill btn-danger"> Gagal Bayar</button> @endif </td>
        </tr>
        @endforeach
    </tbody>
</table>
{!!$pagination!!}