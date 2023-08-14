<table class="table table-responsive table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Jenis tiket</th>
            <th>Diskon(%)</th>
            <th>Niali Diskon (Satuan)</th>
            <th>Min. Pembelian</th>
            <th>Tgl Mulai</th>
            <th>Tgl Akhir</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($diskon as $d)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$d->jenis_tiket ==1 ? 'Regular Day' : 'Premium Day' }}</td>
            <td>{{$d->diskonPersen}}</td>
            <td>{{$d->nilaiDiskon}}</td>
            <td>{{$d->minimalQuantity}}</td>
            <td>{{$d->tanggalMulai}}</td>
            <td>{{$d->tanggalAkhir}}</td>
            <td>
                <a href="{{url('diskon/'.$d->id)}}"><i class="bi bi-trash text-danger"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>