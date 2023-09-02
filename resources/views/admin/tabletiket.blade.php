<table class="table ft ftc table-responsive-w-100 table-bordered" id="dataCetak">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal input</th>
            <!-- <th> Berlaku</th> -->
            <th>Jenis tiket</th>
            <th>Qty</th>
            <th>By</th>
            <th>Unduh</th>
            <th>Sts Cetak</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tiket as $t)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$t->created_at}}</td>
            <!-- <td>{{$t->tgl == '' ? "Rabu,Kamis & Jum'at" : "Sabtu, Minggu, Libur Nasional, Openening day & Closing day"}}</td> -->
            <td>{{$t->tiket_id == 1 ? "Regular Day" : "Premiu Day"}} @if($t->status == 5) (Gratis) @endif</td>
            <td>{{$t->qty}}</td>
            <td>{{$t->user->name}}</td>
            <td> <a href="{{url('download/'.$t->id)}}"> <i class="fa fa-download text-success" aria-hidden="true"></i></a></td>
            <td>
                <div class="custom-control custom-switch " style="margin-left: auto;">
                    <input type="checkbox" class="custom-control-input iscetak" name="iscetak" value="2" @if($t->iscetak > 0) checked @endif id="{{$t->id}}">
                    <label class="custom-control-label" for="{{$t->id}}"></label>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


{!!$pagination!!}