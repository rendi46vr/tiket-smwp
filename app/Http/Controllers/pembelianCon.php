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
        // $tjual = tjual::all();
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
        $paket = tiket::where('slug', session('paket'))->first();

        $validasiData = $request->validate([
            'qty' => 'required',
            'name' => 'required',
            'tgl' => 'required',
            'wa' => 'required:max:60',
            'email' => ''
        ]);
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
        return $paket->harga * $qty;
    }
    public function bayar($slug)
    {

        $tjual = tjual::find($slug);

        return view('bayar', compact('tjual'));
    }
    public function tiket($slug)
    {
        $tjual = tjual::find($slug);
        $qr = QrCode::generate($tjual->id);
        return view('tiket', compact('tjual', 'qr'));
    }
    public function download($slug)
    {
        $tjual = tjual::with('tjual1')->find($slug);
        if ($tjual->status == 2) {

            // $qr = base64_encode(QrCode::style('round')->size(100)->generate($tjual->id));
            //  $qr = base64_encode(QrCode::generate($tjual->id));
            $pdf = Pdf::loadView('download', compact('tjual'))->setPaper('A8', 'portrait')
                ->setWarnings(false);
            return $pdf->download('tiket.pdf');
        } elseif ($tjual->status == 1) {
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
}
