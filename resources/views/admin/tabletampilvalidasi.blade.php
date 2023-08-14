<table class="table ftc table-responsive table-bordered" id="dataCetak">
    <thead>
        <tr>
            <th>No</th>
            <th>waktu</th>
            <th>Type</th>
            <th>Jumlah tervalidasi</th>

        </tr>
    </thead>
    <tbody>
        @foreach($results as $rs)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td> {{$tgl ." - ".$tgl2 }} </td>
            <td>@if($rs->status == 2) Tiket Online @elseif($rs->status == 4) Tiket Offline @else Tiket Gratis @endif</td>
            <td>{{$rs->count}}</td>
        </tr>
        @endforeach
    </tbody>
</table>