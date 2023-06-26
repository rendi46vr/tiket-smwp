<table class="table ft ftc table-responsive-w-100 table-bordered" id="dataCetak">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Tanggal input</th>
            <th> Berlaku</th>
            <th>Jenis tiket</th>
            <th>Qty</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tiket as $t)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$t->name}}</td>
            <td>{{$t->email}}</td>
            <td>{{$t->created_at}}</td>
            <td>{{$t->tgl == '' ? "Rabu,Kamis & Jum'at" : $t->tgl}}</td>
            <td>{{$t->tiket_id == 1 ? "Regular Day" : "Premiu Day"}}</td>
            <td>{{$t->qty}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
{!!$pagination!!}