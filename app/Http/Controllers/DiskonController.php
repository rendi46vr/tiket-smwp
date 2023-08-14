<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\diskon;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendMail;
use App\Models\tjual;
use App\Tools\tools;

class DiskonController extends Controller
{
    public function adddiskon(Request $request)
    {
        // Validasi input form
        $m = $request->tanggalMulai;
        $a = $request->tanggalAkhir;
        $j = $request->jenisTiket;
        $request->validate([
            'tanggalMulai' => 'required|date',
            'tanggalAkhir' => 'required|date|after_or_equal:tanggalMulai',
            'minimalQuantity' => 'required|integer|min:1|max:100',
            'diskonPersen' => 'required',
            'nilaiDiskon' => 'required|numeric|min:0',
            'jenisTiket' => [
                'required',
                'in:1,2',
                // Gunakan custom validation rule untuk memeriksa tumpang tindih diskon
                function ($attribute, $value, $fail) use ($m, $a, $j) {
                    $tumpangTindihDiskon = Diskon::where('jenis_tiket', $j)
                        ->where(function ($query) use ($m, $a, $j) {
                            $query->whereBetween('tanggalMulai', [$m, $a])
                                ->orWhereBetween('tanggalAkhir', [$m, $a])
                                ->orWhere(function ($query) use ($m, $a) {
                                    $query->where('tanggalMulai', '<=', $m)
                                        ->where('tanggalAkhir', '>=', $a);
                                });
                        })
                        ->first();

                    if ($tumpangTindihDiskon) {
                        $fail('Diskon yang ditambahkan tumpang tindih dengan diskon yang sudah ada.');
                    }
                },
            ]

        ]);
        $diskon = new diskon;
        $diskon->jenis_tiket = $request->jenisTiket;
        $diskon->tanggalMulai = $request->tanggalMulai;
        $diskon->tanggalAkhir = $request->tanggalAkhir;
        $diskon->minimalQuantity = $request->minimalQuantity;
        $diskon->diskonPersen = $request->diskonPersen;
        $diskon->nilaiDiskon = $request->nilaiDiskon;
        $diskon->save();
        // Jika Anda ingin menambahkan data ke database, Anda bisa menggunakan model seperti contoh di atas.
        // Namun, dalam contoh ini, saya hanya akan menampilkan data yang telah diisi dalam bentuk array.
        return response()->json([
            "status" => true,
            "diskon" => $this->datadiskon()
        ]);
    }


    public function datadiskon()
    {
        $diskon = diskon::all();
        return view('diskontabel', compact('diskon'))->render();
    }

    public function cekdiskon($js, $full = true)
    {
        $today =  Carbon::today()->toDateString();

        $diskonHariIni = diskon::where('jenis_tiket', $js)
            ->whereDate('tanggalMulai', '<=', $today)
            ->whereDate('tanggalAkhir', '>=', $today)
            ->where(function ($query) {
                $query->where('diskonPersen', '>', 0)
                    ->orWhere('nilaiDiskon', '>', 0);
            })
            ->first();
        $pesan = '';
        if ($diskonHariIni) {
            $ket = $diskonHariIni->minimalQuantity < 2 ?  'Diskon' : 'Min. Pembelian' . $diskonHariIni->minimalQuantity . " tiket, Diskon";
            $pesan = ("
            <div class='discount-container'>
            <div class='discount-info'>
             <span class='discount-text'>  " . $ket . " .</span>
                <span class='discount-percent'> " . tools::fRupiah($diskonHariIni->nilaiDiskon) . " </span>
             <span class='discount-text'>pertiket</span>

            </div>
           
        </div>
            
            ");
        }
        if ($full) {
            return $pesan;
        } else {
            // if ($diskonHariIni) {
            return $diskonHariIni;
            // } else {
            // return false;
            // }
        }
    }

    public function hapus($id)
    {
        try {
            diskon::destroy($id);
            return redirect('dashboard');
        } catch (\Throwable $th) {
            return redirect('dashboard');
        }
    }

    public function resend($id, $false = true)
    {
        // try {
        //code...
        $data = tjual::findorFail($id);
        if ($data->status == 2) {
            //mail notificatiion
            $sendnotif = [
                'title' => 'Pembelian Tiket Berhasil!',
                'subject' => 'Download Tiket Sekarang',
                'url' => '',
                'body' => 'Haloo Bapak/ibu ' . $data->name . ' , Silahkan download data Tiket anda  (<a href=" ' . url('tiket/' . $data->id) . '">Klik Disini</a>) ',
            ];
            Mail::to($data->email)->send(new sendMail($sendnotif));
        }
        if ($false) {

            return redirect('penjualan')->with('success', 'Tiket  Berhasil Dikirim!');
        }
        // } catch (\Throwable $th) {
        //     if ($false) {

        //         return redirect('penjualan')->with('success', 'Tiket  Gagal Dikirim!');
        //     }
        // }
    }
}
