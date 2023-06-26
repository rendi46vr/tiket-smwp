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
use App\Tools\Tools;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{

    protected $sess = 'searchjual';
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

    public function datatiket($msg = null, $page = 1)
    {

        $tiket = tjual::where('status', 4)->orderby('created_at', 'desc')->paginate(10, ['*'], null, $page);
        $pagination = Tools::ApiPagination($tiket->lastPage(), $page, 'pagetiket');

        return view('admin.tabletiket', compact('tiket', 'pagination'))->render();
    }
    public function pagetiket($page)
    {
        return $this->datatiket(null, $page);
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
        return $this->datatiket();
    }
    public function penjualan()
    {
        $tiket = $this->datapenjualan();
        return view('penjualan.jual', compact('tiket'));
    }
    public function datapenjualan($msg = null, $page = 1, $search = false)
    {
        $tiket = tjual::where('status', 2)->where(function ($e) use ($search) {
            if ($search) {
                $e->where('name', 'like', '%' . session($this->sess)['search'] . '%')
                    ->orwhere('email', 'like', '%' . session($this->sess)['search'] . '%')
                    ->orwhere('np', 'like', '%' . session($this->sess)['search'] . '%')
                    ->orwhere('id', 'like', '%' . session($this->sess)['search'] . '%');
            } else {
                Session::forget($this->sess);
            }
        })->orderby('created_at', 'desc')->paginate(10, ['*'], null, $page);
        $pagination = Tools::ApiPagination($tiket->lastPage(), $page, 'pagejual');
        // return print_r($tiket->count());
        return view('penjualan.tablejual', compact('tiket', 'pagination'))->render();
    }
    public function pagejual($page)
    {
        if (Session::has($this->sess)) {
            return $this->datapenjualan(null, $page, true);
        } else {
            return $this->datapenjualan(null, $page);
        }
    }

    public function searchjual(Request $request)
    {
        Session::put($this->sess, $request->all());
        return $this->datapenjualan(null, 1, true);
    }
}
