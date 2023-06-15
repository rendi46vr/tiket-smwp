<?php

use App\Http\Controllers\midtansCon;
use App\Http\Controllers\pembelianCon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

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
    $validator = Validator::make($request->all(), $my_request->rules(), $my_request->messages());
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
