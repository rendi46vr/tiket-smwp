<table class="table ft ftc table-responsive table-bordered" id="dataCetak">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal input</th>
            <!-- <th> Berlaku</th> -->
            <th>Nama</th>
            <th>Email</th>
            <th>Jenis tiket</th>
            <th>Qty</th>
            <th>By</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tiket as $t)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$t->created_at}}</td>
            <!-- <td>{{$t->tgl == '' ? "Rabu,Kamis & Jum'at" : "Sabtu, Minggu, Libur Nasional, Openening day & Closing day"}}</td> -->
            <td>{{$t->name}}</td>
            <td>{{$t->email}}</td>
            <td>{{$t->tiket_id == 1 ? "Regular Day" : "Premiu Day"}}</td>
            <td>{{$t->qty}}</td>
            <td>{{$t->user->name}}</td>

        </tr>
        @endforeach
    </tbody>
</table>


{!!$pagination!!}