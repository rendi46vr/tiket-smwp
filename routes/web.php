<?php

use App\Http\Controllers\midtansCon;
use App\Http\Controllers\pembelianCon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UserController;
use App\Models\tgltiket;
use Illuminate\Http\Request;
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
Route::get('tiket/{slug}', [pembelianCon::class, 'tiket']);
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
    });
});



Route::get('test', function () {
    $tgl2 = tgltiket::where('status', 2)->pluck('tgl')->toArray();
    if (in_array('2023-09-25', $tgl2)) {
        echo 'sukses';
    } else {
        echo 'gagal';
    }
});
