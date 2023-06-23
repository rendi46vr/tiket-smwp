<?php

namespace App\Http\Controllers;

use App\Models\tiket;
use Illuminate\Http\Request;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\tjual;
use App\Models\tjual1;

class UserController extends Controller
{
    public function login()
    {
        // dd(session()->all());
        return view('login');
    }
    public function proses_login(Request $request)
    {
        $kredensial = $request->validate(
            [
                'name' => 'required|string|max:100',
                'password' => 'required|string',
            ]
        );


        if (Auth::attempt($kredensial)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return redirect("login")->withSuccess('Login Gagal, silahkan coba lagi!.');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return Redirect('/');
    }
    public function dashboard()
    {
        $data = tiket::all();
        return view('dashboard', compact('data'));
    }

    public function settiket(Request $request)
    {

        $regular = $request->validate([
            'hargaregular-day' => 'required',
            'batasregular-day' => 'required',
            'statusregular-day' => 'max:3',
        ]);
        $premium = $request->validate([
            'hargapremium-day' => 'required',
            'bataspremium-day' => 'required',
            'statuspremium-day' => 'max:3',
        ]);
        $request->has('statusregular-day') ?  $regular['statusregular-day'] = 1 : $regular['statusregular-day'] = 0;
        $request->has('statuspremium-day') ? $regular['statuspremium-day'] = 1 : $regular['statuspremium-day'] = 0;
        tiket::find(1)->update([
            'harga' => $regular['hargaregular-day'],
            'batas' => $regular['batasregular-day'],
            'status' => $regular['statusregular-day'],
        ]);
        tiket::find(2)->update([
            'harga' => $premium['hargapremium-day'],
            'batas' => $premium['bataspremium-day'],
            'status' => $regular['statuspremium-day'],
        ]);

        return true;
    }

    public function ctiket()
    {
        $tiket = $this->datatiket();

        return view('admin.ctiket', compact('tiket'));
    }

    public function datatiket($msg = null)
    {
        $tiket = tjual::where('status', 4)->get();
        return view('admin.tabletiket', compact('tiket'))->render();
    }

    public function cetakTiket(Request $request)
    {
        $validasiData = $request->validate([
            'qty' => 'required',
            'tgl' => '',
            'jenis_tiket' => 'required|max:1',

        ]);
        $paket = tiket::find($validasiData['jenis_tiket']);
        $validasiData['tgljual'] = date('Y-m-d');
        $validasiData['name'] = "Admin";
        $pool = '0123456789';
        $uniq = substr(str_shuffle(str_repeat($pool, 5)), 0, 8);
        $validasiData['np'] = "NP" . date('Y-m-d') . $uniq;
        $validasiData['status'] = 4;
        $validasiData['id'] = Str::uuid();
        $validasiData['tiket_id'] = $paket->id;
        $data = tjual::create($validasiData);
        for ($i = 1; $i <= $data->qty; $i++) {
            tjual1::create([
                'id' => Str::uuid(),
                'tjual_id' => $data->id,
                'status' => 0
            ]);
        }
        return "yeah dude!";
    }
}
