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

class pembelianCon extends Controller
{
    public function index()
    {
        if (Session::has('bayar')) {
            $data = tjual::find(session::get('bayar'));
            if ($data->status == 2) {
                session::forget('bayar');
                return redirect('/');
            }
            return redirect('pembayaran/' . session::get('bayar'));
        }
        $tiket = tiket::all();
        return view('index', compact('tiket'));
    }
    public function pembelian($slug)
    {
        if (Session::has('bayar')) {
            $data = tjual::find(session::get('bayar'));
            if ($data->status == 2) {
                session::forget('bayar');
                return redirect('/');
            }
            return redirect('pembayaran/' . session::get('bayar'));
        }
        try {
            $tiket = tiket::where('slug', $slug)->firstOrFail();
            session()->put('paket', $slug);
        } catch (\Throwable $th) {
            return view('/');
        }
        return view('form-pembelian', compact('tiket'));
    }
    public function order(Request $request)
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
        $data = tjual::create($validasiData);

        if ($data) {
            $token = $this->getMidToken([
                'order_id' => $data->id,
                'harga'   => $data->totalbayar,
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
    public function qty($qty)
    {
        $paket = tiket::where('slug', session('paket'))->first();
        return $this->rupiah($paket->harga * $qty);
    }
    public function bayar($slug)
    {
        try {
            $tjual = tjual::findOrFail($slug);
            if ($tjual->status == 2) {
                Session::forget('bayar');
                return redirect('/');
            }
            return view('bayar', compact('tjual'));
        } catch (\Throwable $th) {
            return redirect('/');
        }
    }

    public function tiket($slug)
    {
        $tjual = tjual::find($slug);
        $qr = QrCode::generate($tjual->id);
        return view('tiket', compact('tjual', 'qr'));
    }
    public function download($slug)
    {
        try {
            $tjual = tjual::with('tjual1')->findOrFail($slug);
            if ($tjual->status ==  2 || $tjual->status == 4) {
                $pdf = Pdf::loadView('download', compact('tjual'))->setPaper('A8', 'portrait')
                    ->setWarnings(false);
                Session::forget('bayar');
                return $pdf->download('tiket.pdf');
            } else {
                abort(403);
            }
        } catch (\Throwable $th) {
            return redirect('pembayaran/' . $tjual->id);
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
        $hasil = "Rp " . number_format($angka, 2, ',', '.');
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
            if ($cancel['status_code'] == 200) {
                Session::forget('bayar');
                $tjual->update(['status' => -2]);
            }
            return redirect('/');
        } catch (\Throwable $th) {
            return redirect('/');
        }
    }
}
