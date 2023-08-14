<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tiket;
use App\Models\tjual;
use App\Models\tjual1;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\DiskonController;
use App\Http\Requests\order;
use Illuminate\Support\Carbon;
use App\Models\diskon;
use App\Models\tgltiket;

class pembelianCon extends Controller
{
    public function index(DiskonController $ds)
    {
        $today =  Carbon::today()->toDateString();


        if (Session::has('bayar')) {
            $data = tjual::find(session::get('bayar'));
            if ($data->status == 2) {
                session::forget('bayar');
                return redirect('tiket/' . $data->id);
            }
            return redirect('pembayaran/' . session::get('bayar'));
        }
        $tiket = tiket::all();
        return view('index', compact('tiket', 'ds'));
    }
    public function pembelian($slug, DiskonController $ds)
    {
        if (Session::has('bayar')) {

            $data = tjual::find(session::get('bayar'));

            if ($data->status == 2) {
                session::forget('bayar');
                return redirect('tiket/' . $data->id);
            }
            return redirect('pembayaran/' . session::get('bayar'));
        }
        try {
            $tiket = tiket::where('slug', $slug)->firstOrFail();
            session()->put('paket', $slug);
        } catch (\Throwable $th) {
            return view('/');
        }
        $diskon = $ds->cekdiskon($tiket->id, false);

        $total =  $tiket->harga * 1;
        if ($diskon) {
            if (1 >= $diskon->minimalQuantity) {
                $total = (1 * $tiket->harga) - ($diskon->nilaiDiskon * 1);
            }
        }
        $harga = $this->rupiah($total);
        return view('form-pembelian', compact('tiket', 'harga'));
    }
    public function order(Request $request, DiskonController $ds)
    {
        $validasiData = $request->validate([
            'qty' => 'required',
            'name' => 'required',
            'tgl' => '',
            'wa' => 'required:max:60',
            'email' => ''
        ]);
        $paket = tiket::where('slug', session('paket'))->first();
        $validasiData['tgljual'] = date('Y-m-d');
        $pool = '0123456789';
        $uniq = substr(str_shuffle(str_repeat($pool, 5)), 0, 8);
        $validasiData['np'] = "NP" . date('Y-m-d') . $uniq;
        $validasiData['totalbayar'] = $request->qty * $paket->harga;
        $validasiData['status'] = 0;
        $validasiData['id'] = Str::uuid();
        $validasiData['tiket_id'] = $paket->id;
        $diskon = $ds->cekdiskon($paket->id, false);
        if ($diskon) {
            if ($request->qty >= $diskon->minimalQuantity) {
                $validasiData['totalbayar'] = ($request->qty * $paket->harga) - ($diskon->nilaiDiskon * $request->qty);
            }
        }
        $data = tjual::create($validasiData);


        if ($data) {
            $token = $this->getMidToken([
                'order_id' => $data->id,
                'harga'   => $data->totalbayar + 1000,
                'user_name' => $data->name,
                'nophone'    => $data->wa,
                'email'      => $data->email,
            ]);
            $data->token = $token;
            $data->save();
            Session::put('bayar', $data->id);
            // Cookie::queue('bayar', $data->id, 1440);
            return response()->json([
                "status" => true,
                "href" => url('pembayaran/' . $data->id)
            ]);
        } else {
            return response()->json([
                "status" => false,
            ]);
        }
    }
    public function qty($qty, DiskonController $ds)
    {
        $paket = tiket::where('slug', session('paket'))->first();
        $diskon = $ds->cekdiskon($paket->id, false);

        $total =  $paket->harga * $qty;
        if ($diskon) {
            if ($qty >= $diskon->minimalQuantity) {
                $total = ($qty * $paket->harga) - ($diskon->nilaiDiskon * $qty);
            }
        }
        return $this->rupiah($total);
    }
    public function bayar($slug)
    {
        try {
            $tjual = tjual::findOrFail($slug);
            if ($tjual->status == 2) {
                Session::forget('bayar');
                return redirect('tiket/' . $tjual->id);
            }
            return view('bayar', compact('tjual'));
        } catch (\Throwable $th) {
            return redirect('/');
        }
    }

    public function tiket($slug)
    {
        $tjual = tjual::find($slug);
        if ($tjual->status != 2 && $tjual->status != 5) {
            echo $tjual->status;
            exit;
            return redirect('/');
        }
        // if (Session::has('bayar')) {
        //     session::forget('bayar');
        // }
        $qr = QrCode::generate($tjual->id);
        return view('tiket', compact('tjual', 'qr'));
    }
    public function belilagi()
    {
        if (Session::has('bayar')) {
            session::forget('bayar');
        }
        return redirect('/');
    }

    public function download($slug)
    {
        try {
            if (auth()->check()) {
                $tjual = tjual::findOrFail($slug);
            } else {
                $tjual = tjual::findOrFail($slug);
            }
            if ($tjual->status ==  2 || $tjual->status == 4 || $tjual->status == 5) {

                $tikets = tjual1::where("tjual_id", $slug)->orderby('nourut', 'asc')->get();
                $pdf = Pdf::loadView('download', compact('tjual', 'tikets'))->setPaper('A8', 'portrait')
                    ->setWarnings(false);
                Session::forget('bayar');
                return $pdf->stream('tiket.pdf');
            } else {
                return redirect('pembayaran/' . $tjual->id);
            }
        } catch (\Throwable $th) {
            return redirect('/');
        }
    }

    public function getMidToken($data = [])
    {
        \Midtrans\Config::$serverKey = env('MID_SKEY');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \midtrans\Config::$is3ds = true;
        $params = array(
            'transaction_details' => array(
                'order_id' => $data['order_id'],
                'gross_amount' => $data['harga']
            ),
            'customer_details' => array(
                'name' => $data['user_name'],
                'email' => $data['email'],
                'phone' => $data['nophone'],
            ),
        );
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        return $snapToken;
    }
    public function rupiah($angka)
    {
        $hasil = "Rp " . number_format($angka, 0, ',', '.');
        return $hasil;
    }

    public function cancelTransaction($slug)
    {
        try {
            $tjual = tjual::where('status', 1)->orWhere('status', 0)->findOrFail($slug);
            // dd($tjual);
            // return 'ok';
            $cancel =  Http::withHeaders([
                "Content-Type" => "application/json",
                "Accept" => "application/json",
                "Authorization" => "Basic " . base64_encode(env('MID_SKEY') . ":"),
            ])->post(env('MID_SB') . "v2/" . $tjual->id . '/cancel');
            // if ($cancel['status_code'] == 200) {
            Session::forget('bayar');
            $tjual->update(['status' => -2]);
            // }
            return redirect('/');
        } catch (\Throwable $th) {
            try {
                $tjual = tjual::where('status', 1)->orWhere('status', 0)->findOrFail($slug);
                $tjual->update(['status' => -2]);
            } catch (\Throwable $th) {
                //throw $th;
            }
            Session::forget('bayar');
            return redirect('/');
        }
    }
    public function metode($props = [], $met)
    {
        $bank_transfer = ["BRI", 'BNI', 'BCA', 'MANDIRI', 'PERMATA'];
        $cstore = ['ALFAMART', 'INDOMARET'];
        $ewallet = ['GOPAY'];
        $data = [];
        $data["payment_type"] = "";
        $data["transaction_details"] = [
            "order_id" => $props["id"],
            "gross_amount" => $props["harga"]
        ];
        if (in_array($met, $bank_transfer)) {
            if ($met == "MANDIRI") {
                $data["payment_type"] = "echannel";
                $data["echannel"] = [
                    "bill_info1" => "Pembayaran",
                    "bill_info2" => "Vittindo"
                ];
            } elseif ($met == "PERMATA") {
                $data["payment_type"] = "permata";
            } else {
                $data["payment_type"] = "bank_transfer";
                $data["bank_transfer"] = [
                    "bank" => $met
                ];
            }
        } elseif (in_array($met, $ewallet)) {
            $data["payment_type"] = "gopay";
            $data["gopay"] = [
                'enable_callback' => true,
                'callback_url' => url('pesanan')
            ];
        } elseif (in_array($met, $cstore)) {
            $data["payment_type"] = 'cstore';
            $data["cstore"] = [
                'store' => $met,
                'message' => ''
            ];
        } else {
            return false;
        }
        return $data;
    }


    public function metgopay()
    {

        return  dd(trim(Str::uuid() . " "));

        return  QrCode::phoneNumber('+6281274063988');

        dd(bcrypt("slf$#@!2023"));
        $req = $this->metode([
            "id" => rand(0, 9),
            "harga" => 50000,
        ], "GOPAY");
        \Midtrans\Config::$serverKey = env("MID_SKEY");
        return $response = \Midtrans\CoreApi::charge($req);

        // $data = Http::withHeaders([
        //     "Accept" => 'application/json',
        //     "Content-Type" => "application/json",
        //     "Authorization" => "Basic " . base64_encode(env('MID_SKEY') . ":")
        // ])->post(env('MID_SB') . '/v2/charge', $req)->json();

        // dd($data);
    }



    public function publiccek()
    {
        return view('cek');
    }
    public function publiccekproses($slug)
    {

        try {
            // return 'ok';
            $tiket = tjual1::with('tjual')->findorFail($slug);
            $jentiket =  $tiket->tjual->tiket_id == 2 ? "Premium Day" : "Regular Day";
            $tglpremium = tgltiket::where('status', 2)->pluck('tgl')->toArray();
            $tglregular = tgltiket::where('status', 1)->pluck('tgl')->toArray();
            $istgl = false;
            if ($tiket->status == 0  ||  $tiket->status == 4  ||  $tiket->status == 5) {

                if ($tiket->tjual->tiket_id == 2) {
                    if (in_array(date('Y-m-d'), $tglpremium)) {
                        $istgl = true;
                    } else {
                        $datatiket = tiket::find(2);

                        if ($datatiket->isallday == 2) {
                            $istgl = true;
                        } else {
                            $istgl = false;
                        }
                    }
                } else {
                    if (in_array(date('Y-m-d'), $tglregular)) {
                        $istgl = true;
                    } else {
                        $istgl = false;
                    }
                }
            }
            if ($istgl) {
                if ($tiket->status == 0  ||  $tiket->status == 4  ||  $tiket->status == 5) {
                    $tiket->validon = date('Y-m-d  H:i:s');
                    // $tiket->status = 2;
                    $pesan = '1';
                    $tiket->tjual->tiket_id  == 2 ? $pesan = "Tiket berlaku 1 orang (mulai pukul 16.00 WIB)" : $pesan = "Tiket berlaku 1 orang untuk hari Rabu/Kamis/Jumat (16.00 s.d. 21.00) kecuali hari libur nasional";
                    return  response()->json([
                        "status" => 'success',
                        "jentiket" => $jentiket,
                        "pesan" => $pesan
                    ]);
                } elseif ($tiket->status == 2) {
                    return  response()->json([
                        "status" => 'used',
                    ]);
                }
                return  response()->json([
                    "status" => 'invalid',
                ]);
            } else {
                if ($tiket->status == 2) {
                    $dateTimeString = $tiket->validon;
                    $format = 'Y-m-d H:i:s';
                    $dateTime = Carbon::createFromFormat($format, $dateTimeString);
                    $formattedDateTime = $dateTime->format('d M Y, H:i A');
                    $today = false;
                    if ($dateTime->isToday()) {
                        $formattedDateTime = "Hari ini Jam " . $dateTime->format('H:i A');
                        $today = true;
                    }
                    return  response()->json([
                        "status" => 'used',
                        "pesan" => "Digunakan Pada " . $formattedDateTime,
                    ]);
                } else {
                    $berlaku = $tiket->tjual->tiket_id == 1 ? "Rabu-Jum'at (Kecuali Libur Nasional)" : "Weekend day & Libur Nasional";
                    $pesan = '1';
                    $tiket->tjual->tiket_id == 2 ? $pesan = "Tiket berlaku 1 orang (mulai pukul 16.00 WIB) <br>
                    Berlaku setiap hari festival (termasuk hari libur nasional, opening day, dan closing day)" : $pesan = "Tiket berlaku 1 orang untuk hari Rabu/Kamis/Jumat (16.00 s.d. 21.00) kecuali hari libur nasional";
                    return  response()->json([
                        "status" => 'success',
                        "jentiket" => $jentiket,
                        "pesan" => $pesan
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return  response()->json([
                "status" => 'invalid',
                "data" => ''
            ]);
        }
    }
}
