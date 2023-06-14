<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tiket;
use App\Models\tjual;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class pembelianCon extends Controller
{
    public function index()
    {
        $tiket = tiket::all();
        // $tjual = tjual::all();
        return view('index', compact('tiket'));
    }
    public function pembelian($slug)
    {
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
        $tjual = tjual::find($slug);
        $qr = base64_encode(QrCode::style('round')->size(100)->generate($tjual->id));
        // return $qr = base64_encode(QrCode::generate($tjual->id));

        $pdf = Pdf::loadView('download', compact('qr'))->setPaper('A8', 'portrait')
            ->setWarnings(false)
            ->save('myfile.pdf');
        return $pdf->stream();
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
