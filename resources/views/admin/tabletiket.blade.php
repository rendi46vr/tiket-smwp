<table class="table ft ftc table-responsive-w-100 table-bordered" id="dataCetak">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal input</th>
            <th>Tanggal Berlaku</th>
            <th>Jenis tiket</th>
            <th>Unduh</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tiket as $t)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$t->tgljual}}</td>
            <td>{{$t->tgl}}</td>
            <td>{{$t->tiket_id == 1 ? "Regular Day" : "Premiu Day"}}</td>
            <td> <a href="{{url('download/'.$t->id)}}"> <i class="fa fa-download text-success" aria-hidden="true"></i></a></td>
        </tr>
        @endforeach
    </tbody>
</table>