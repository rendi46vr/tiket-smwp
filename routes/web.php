<?php

use App\Http\Controllers\midtansCon;
use App\Http\Controllers\pembelianCon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UserController;
use App\Models\tgltiket;
use Illuminate\Http\Request;
use App\Http\Controllers\DiskonController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('validation', function (Request $request) {
    $class = $request->class;
    $class = str_replace('/', '\\', $class);
    $my_request = new $class();
    if ($request->uniq) {
        $rules = $my_request->rules([0 => $request->tgl, 1 => $request->jenis_tiket]);
    } else {
        $rules = $my_request->rules();
    }
    $validator = Validator::make($request->all(), $rules, $my_request->messages());
    $validator->setAttributeNames($my_request->attributes());
    if ($request->ajax()) {
        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ));
        } else {
            return response()->json(array(
                'success' => true,
            ));
        }
    }
});

Route::get('/', [pembelianCon::class, 'index']);

Route::get('form-pembelian/{slug}', [pembelianCon::class, 'pembelian']);
Route::post('order', [pembelianCon::class, 'order']);
Route::post('cqty/{qty}', [pembelianCon::class, 'qty']);
Route::get('pembayaran/{slug}', [pembelianCon::class, 'bayar']);
Route::get('tiket/{slug?}', [pembelianCon::class, 'tiket']);
Route::get('download/{slug}', [pembelianCon::class, 'download']);
Route::get('cancel/{slug}', [pembelianCon::class, "cancelTransaction"]);

Route::get('login', [UserController::class, "login"])->name('login');
Route::post('proses_login', [UserController::class, "proses_login"]);
Route::get('logout', [UserController::class, "logout"]);
Route::group(['middleware' => ['auth']], function () {
    Route::group(['middleware' => ['cek_login']], function () {
        Route::get('dashboard', [UserController::class, "dashboard"]);
        Route::post('settiket', [UserController::class, "settiket"]);
        Route::get('ctiket', [UserController::class, "ctiket"]);
        Route::post('cetakTiket', [UserController::class, "cetakTiket"]);
        Route::post('pagetiket/{page}', [UserController::class, "pagetiket"]);
        Route::get('penjualan', [UserController::class, "penjualan"]);
        Route::post('pagejual/{page}', [UserController::class, "pagejual"]);
        Route::post('searchjual/{search}', [UserController::class, "searchjual"]);
        Route::post('/adddiskon', [DiskonController::class, 'adddiskon'])->name('adddiskon');
        Route::get('diskon/{slug}', [DiskonController::class, 'hapus']);
        Route::get('resend/{slug}', [DiskonController::class, 'resend']);

        Route::get('penbem', [UserController::class, "penbem"]);
        Route::post('pagepenbem/{page}', [UserController::class, "pagepenbem"]);
        Route::post('searchpenbem/{search}', [UserController::class, "searchpenbem"]);

        Route::post('cemail/{slug}', [UserController::class, "cemail"]);
        Route::post('iscetak/{slug}', [UserController::class, "iscetak"]);

        Route::get("valhp", [UserController::class, "valhp"]);
        Route::post('admincek/{slug}', [UserController::class, "admincekproses"]);

        Route::get("tampilvalidasi", [UserController::class, "tampilvalidasi"]);

        Route::get("tamvalhari", [UserController::class, "tamvalhari"]);
        Route::post("tamvalhari", [UserController::class, "tamvalhari"]);

        Route::get('kirim', [UserController::class, "kirim"]);
        Route::post('kirimtiket', [UserController::class, "kirimtiket"]);
        Route::post('pagekirim/{page}', [UserController::class, "pagekirim"]);
    });
});
Route::post('cektiket/{slug}', [UserController::class, "cektiket"]);
Route::get('belilagi', [pembelianCon::class, "belilagi"]);
Route::get('publiccek', [pembelianCon::class, "publiccek"]);
Route::post('publiccek/{slug}', [pembelianCon::class, "publiccekproses"]);

Route::get('valtiket', function () {
    return view('admin.cektiket');
});

Route::get('tes', [pembelianCon::class, "metgopay"]);
