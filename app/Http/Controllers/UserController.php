<?php

namespace App\Http\Controllers;

use App\Models\tgltiket;
use App\Models\tiket;
use Illuminate\Http\Request;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\tjual;
use App\Models\tjual1;
use App\Tools\tools;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\CodeCoverage\Node\CrapIndex;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendMail;

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
    public function dashboard(DiskonController $ds)
    {
        $diskon = $ds->datadiskon();
        $data = tiket::all();
        return view('dashboard', compact('data', 'diskon'));
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
        // if (auth()->user()->role < 2) {
        //     $tiket = tjual::with('user')->where('status', 4)->where('user_id', auth()->user()->id)->orderby('created_at', 'desc')->paginate(10, ['*'], null, $page);
        // } else {
        $tiket = tjual::with('user')->where('status', 4)->where('user_id', '!=', null)->orderby('created_at', 'desc')->paginate(10, ['*'], null, $page);
        // }

        $pagination = tools::ApiPagination($tiket->lastPage(), $page, 'pagetiket');

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
        $user = auth()->user();
        $paket = tiket::find($validasiData['jenis_tiket']);
        $validasiData['tgljual'] = date('Y-m-d');
        $validasiData['name'] = "Admin";
        $pool = '0123456789';
        $uniq = substr(str_shuffle(str_repeat($pool, 5)), 0, 8);
        $validasiData['np'] = "NP" . date('Y-m-d') . $uniq;
        $validasiData['status'] = 4;
        $validasiData['id'] = Str::uuid();
        $validasiData['tiket_id'] = $paket->id;
        $validasiData['user_id'] = $user->id;
        $data = tjual::create($validasiData);

        $index = $paket->index;
        $paket->update([
            "index" => $index + $validasiData['qty'],
        ]);
        $paket->save();
        for ($i = 1; $i <= $data->qty; $i++) {
            tjual1::create([
                'id' => Str::uuid(),
                'tjual_id' => $data->id,
                'status' => 0,
                'nourut' => $index + $i
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
        $total = tjual::selectRaw(DB::raw("sum(totalbayar) as tb , sum(qty) tq,count(id) as jum"))->where('status', 2)->first();
        $pagination = tools::ApiPagination($tiket->lastPage(), $page, 'pagejual');
        // return print_r($tiket->count());
        return view('penjualan.tablejual', compact('tiket', 'pagination', 'total'))->render();
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

    public function cektiket($slug)
    {

        try {
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
                    $waktu = Carbon::parse($tiket->validon);
                    $tiket->status = 2;
                    $tiket->save();
                    return  response()->json([
                        "status" => 'success',
                        "data" => $tiket,
                        "jentiket" => $jentiket,
                        "time" => $waktu->format('H:i A'),
                    ]);
                } elseif ($tiket->status == 2) {
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
                        "data" => $tiket,
                        "jentiket" => $jentiket,
                        "time" => "Digunakan Pada " . $formattedDateTime

                    ]);
                }
                return  response()->json([
                    "status" => 'invalid',
                    "data" => ''
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
                        "data" => $tiket,
                        "jentiket" => $jentiket,
                        "time" => "Digunakan Pada " . $formattedDateTime,
                        "today" => $today

                    ]);
                } else {
                    $berlaku = $tiket->tjual->tiket_id == 1 ? "Rabu-Jum'at (Kecuali Libur Nasional)" : "Weekend day & Libur Nasional";
                    return  response()->json([
                        "status" => 'pending',
                        "data" => $tiket,
                        "berlaku" => $berlaku,
                        "jentiket" => $jentiket,

                    ]);
                }
            }
        } catch (\Throwable $th) {
            function removeCharacters($text, $numCharacters)
            {
                if ($numCharacters >= 0) {
                    return substr($text, 0, -$numCharacters);
                } else {
                    return $text;
                }
            }
            try {
                $tiket = tjual1::where("id", 'like', "%" . removeCharacters($slug, 4) . "%")->firstOrFail();
                $jentiket =  $tiket->tjual->tiket_id == 2 ? "Premium Day" : "Regular Day";

                if ($tiket->status == 0  ||  $tiket->status == 4  ||  $tiket->status == 5) {
                    $tiket->validon = date('Y-m-d  H:i:s');
                    $tiket->status = 2;
                    $waktu = Carbon::parse($tiket->validon);
                    $tiket->status = 2;
                    $tiket->save();
                    return  response()->json([
                        "status" => 'success',
                        "data" => $tiket,
                        "jentiket" => $jentiket,
                        "time" => $waktu->format('H:i A'),
                    ]);
                } elseif ($tiket->status == 2) {

                    $dateTimeString = $tiket->validon;
                    $format = 'Y-m-d H:i:s';
                    $dateTime = Carbon::createFromFormat($format, $dateTimeString);
                    $formattedDateTime = $dateTime->format('d M Y, H:i A');
                    $today = false;
                    if ($dateTime->isToday()) {
                        $formattedDateTime = "Hari ini Jam " . $dateTime->format('H:i A');
                        $today = true;
                    }
                    return response()->json([
                        "status" => 'used',
                        "data" => $tiket,
                        "jentiket" => $jentiket,
                        "time" => "Digunakan Pada " . $formattedDateTime

                    ]);
                }
            } catch (\Throwable $th) {
                return  response()->json([
                    "status" => 'invalid',
                    "data" => ''
                ]);
            }
        }
    }

    public function penbem()
    {
        $tiket = $this->datapenbem();
        return view('penjualan.penbem', compact('tiket'));
    }
    public function datapenbem($msg = null, $page = 1, $search = false)
    {
        $tiket = tjual::where('status', '<', 2)->where(function ($e) use ($search) {
            if ($search) {
                $e->where('name', 'like', '%' . session($this->sess)['search'] . '%')
                    ->orwhere('email', 'like', '%' . session($this->sess)['search'] . '%')
                    ->orwhere('np', 'like', '%' . session($this->sess)['search'] . '%')
                    ->orwhere('id', 'like', '%' . session($this->sess)['search'] . '%');
            } else {
                Session::forget($this->sess);
            }
        })->orderby('created_at', 'desc')->paginate(10, ['*'], null, $page);

        $pagination = tools::ApiPagination($tiket->lastPage(), $page, 'pagepenbem');
        // return print_r($tiket->count());
        return view('penjualan.tablepenbem', compact('tiket', 'pagination'))->render();
    }
    public function pagepenbem($page)
    {
        if (Session::has($this->sess)) {
            return $this->datapenbem(null, $page, true);
        } else {
            return $this->datapenbem(null, $page);
        }
    }

    public function searchpenbem(Request $request)
    {
        Session::put($this->sess, $request->all());
        return $this->datapenbem(null, 1, true);
    }

    public function cemail($slug, Request $req)
    {
        try {
            //code...
            $tjual = tjual::findorFail($slug);
            if ($req->ajax()) {
                $email = htmlspecialchars($req->email);
                $tjual->email = $email;
                $tjual->save();
                return response()->json(true);
            }
            return response()->json(false);
        } catch (\Throwable $th) {
            return response()->json(false);
        }
    }

    public function iscetak($slug, Request $request)
    {
        $validasiData = $request->validate([
            "cetak" => 'max:1'
        ]);
        try {

            $tjual = tjual::find($slug);
            $tjual->iscetak = $validasiData['cetak'];
            $tjual->save();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function kirim()
    {

        $tiket = $this->datakirim();
        return view('admin.kirim', compact('tiket'));
    }
    public function datakirim($msg = null, $page = 1)
    {
        $tiket = tjual::with('user')->where('status', 5)->where('user_id', '!=', null)->orderby('created_at', 'desc')->paginate(10, ['*'], null, $page);

        $pagination = tools::ApiPagination($tiket->lastPage(), $page, 'pagekirim');
        return view('admin.tablekirim', compact('tiket', 'pagination'))->render();
    }
    public function kirimtiket(Request $request)
    {
        $validasiData = $request->validate([
            'qty' => 'required|max:5',
            'name' => 'required|max:255',
            'email' => 'required|email:dns',
            'jenis_tiket' => 'required|max:1',
        ]);
        $user = auth()->user();
        $paket = tiket::find($validasiData['jenis_tiket']);
        $validasiData['tgljual'] = date('Y-m-d');
        $pool = '0123456789';
        $uniq = substr(str_shuffle(str_repeat($pool, 5)), 0, 8);
        $validasiData['np'] = "NP" . date('Y-m-d') . $uniq;
        $validasiData['status'] = 5;
        $validasiData['id'] = Str::uuid();
        $validasiData['tiket_id'] = $paket->id;
        $validasiData['user_id'] = $user->id;
        $data = tjual::create($validasiData);

        $index = $paket->indexg;
        for ($i = 1; $i <= $data->qty; $i++) {
            $index = ++$index;
            $create = tjual1::create([
                'id' => Str::uuid(),
                'tjual_id' => $data->id,
                'status' => 0,
                'nourut' => $index
            ]);
        }
        $paket->update([
            "indexg" => $index,
        ]);
        $paket->save();

        $sendnotif = [
            'title' => 'Tiket Event SLF 2023!',
            'subject' => 'Download tiket sekarang',
            'url' => '',
            'body' => 'Haloo Bapak/ibu ' . $data->name . ' , Silahkan download data Tiket anda  (<a href=" ' . url('tiket/' . $data->id) . '">Klik Disini</a>) ',
        ];
        Mail::to($data->email)->send(new sendMail($sendnotif));
        return response()->json([
            "data" => $this->datakirim(),
            "notif" => true,
        ]);
    }
    public function pagekirim($page)
    {
        return response()->json([
            "data" => $this->datakirim(null, $page),
            "notif" => false,
        ]);
    }
    public function valhp()
    {
        return view('admin.valhp');
    }
    public function admincekproses($slug)
    {
        try {
            // return 'ok';
            $tiket = tjual1::with('tjual')->findorFail(trim($slug));
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
                    $tiket->status = 2;
                    $tiket->save();
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
                    Berlaku setiap hari festival (termasuk hari libur nasional, opening day, dan closing day)" : $pesan = "Tiket berlaku 1 orang untuk hari Rabu/Kamis/Jumat (16.00 s.d. 21.00) Opening Day kecuali hari libur nasional";
                    return  response()->json([
                        "status" => 'pending',
                        "jentiket" => $jentiket,
                        "pesan" => $pesan
                    ]);
                }
            }
        } catch (\Throwable $th) {
            function removech($text, $numCharacters)
            {
                if ($numCharacters >= 0) {
                    return substr($text, 0, -$numCharacters);
                } else {
                    return $text;
                }
            }
            try {
                $tiket = tjual1::where("id", 'like', "%" . removech($slug, 4) . "%")->firstOrFail();
                $jentiket =  $tiket->tjual->tiket_id == 2 ? "Premium Day" : "Regular Day";

                if ($tiket->status == 0  ||  $tiket->status == 4  ||  $tiket->status == 5) {
                    $tiket->validon = date('Y-m-d  H:i:s');
                    $tiket->status = 2;
                    $tiket->save();
                    $pesan = '1';
                    $tiket->tjual->tiket_id  == 2 ? $pesan = "Tiket berlaku 1 orang (mulai pukul 16.00 WIB)" : $pesan = "Tiket berlaku 1 orang untuk hari Rabu/Kamis/Jumat (16.00 s.d. 21.00) kecuali hari libur nasional";
                    return  response()->json([
                        "status" => 'success',
                        "jentiket" => $jentiket,
                        "pesan" => $pesan
                    ]);
                } elseif ($tiket->status == 2) {
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
                    Berlaku setiap hari festival (termasuk hari libur nasional, opening day, dan closing day)" : $pesan = "Tiket berlaku 1 orang untuk hari Rabu/Kamis/Jumat (16.00 s.d. 21.00) Opening Day kecuali hari libur nasional";
                    return  response()->json([
                        "status" => 'pending',
                        "jentiket" => $jentiket,
                        "pesan" => $pesan
                    ]);
                }
            } catch (\Throwable $th) {
                return  response()->json([
                    "status" => 'invalid',
                    "data" => ''
                ]);
            }
        }
    }

    public function tampilvalidasi()
    {
        // $results = DB::table('tjual1s AS tj1')
        //     ->select('tj.status', DB::raw('COUNT(tj.id) as count'))
        //     ->leftJoin('tjuals AS tj', 'tj1.tjual_id', '=', 'tj.id')
        //     ->where('tj1.status', '<', 1)
        //     ->where('tj.status', '>', 1)
        //     ->groupBy('tj.status')
        //     ->get();
        $table =  $this->tabletampilvalidasi('2023-08-01 ', date('Y-m-d'));
        return view("admin.tampilvalidasi", compact("table"));
    }
    public function tabletampilvalidasi($tgl, $tgl2)
    {
        $mulaiHari = Carbon::parse($tgl)->startOfDay();
        $akhirHari = Carbon::parse($tgl2)->endOfDay();
        $results = tjual1::select('tjuals.status', DB::raw('COUNT(tjuals.id) as count'))
            ->leftJoin('tjuals', 'tjual1s.tjual_id', '=', 'tjuals.id')
            ->where('tjual1s.status', '>', 1)
            ->where('tjuals.status', '>', 1)
            ->whereBetween('tjual1s.validon', [$mulaiHari,  $akhirHari])
            ->groupBy('tjuals.status')
            ->get();
        return view('admin.tabletampilvalidasi', compact("results", 'tgl', 'tgl2'))->render();
    }
    public function tamvalhari(Request $request)
    {
        $validasiData = $request->validate([
            "dari" => "required|date",
            "sampai" => "required|date",
        ]);
        return $this->tabletampilvalidasi($validasiData["dari"], $validasiData["sampai"]);
    }
}
