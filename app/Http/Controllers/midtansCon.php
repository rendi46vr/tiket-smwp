<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tjual;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendMail;
use App\Models\tjual1;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\DiskonController;
use App\Models\tiket;

class midtansCon extends Controller
{
    public function callback(Request $request)
    {
        $skey = env('MID_SKEY');
        $enc = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $skey);
        if ($enc == $request->signature_key) {
            $data = tjual::findorFail($request->order_id);
            if ($request->transaction_status == 'capture') {
                $data->update([
                    "status" => 2,
                ]);
                $undian = tiket::find(1);
                $indexundian = $undian->indexundian;
                Cookie::forget('bayar');
                for ($i = 1; $i <= $data->qty; $i++) {
                    $uu = ++$indexundian;

                    tjual1::create([
                        'id' => Str::uuid(),
                        'tjual_id' => $data->id,
                        'status' => 0,
                        "noundian" => $uu
                    ]);
                }
                $undian->update([
                    "indexundian" => $uu
                ]);
                //send Notification if success
                //mail notificatiion
                $sendnotif = [
                    'title' => 'Pembelian Tiket Berhasil!',
                    'subject' => 'Proses Pembayaran Berhasil!',
                    'url' => '',
                    'body' => 'Haloo Bapak/ibu ' . $data->name . ' , Silahkan download data Tiket anda  (<a href=" ' . url('tiket/' . $data->id) . '">Klik Disini</a>) ',
                ];
                Mail::to($data->email)->send(new sendMail($sendnotif));
                $email = new DiskonController;
                $email->resend($data->id, false);
            } elseif ($request->transaction_status == 'pending') {
                $ty = $request->payment_type;
                switch ($ty) {
                    case "bank_transfer":
                        $ic = $request->va_numbers[0];

                        $message = "Halo.. " . $data->name . " Pesanan telah kami terima, mohon melakukan pembayaran (<a href='" . url('pembayaran/' . $data->id) . "'>Klik Disini</a>) dengan  bank " . strtoupper($ic['bank']) . " dengan Virtual account number <b>" . $ic['va_number'] . "</b> sebelum <b>" . $request->expire_time . "</b>";
                        break;
                    case "cstore":
                        $message = "Halo.. " . $data->name . " Pesanan telah kami terima, mohon melakukan pembayaran (<a href='" . url('pembayaran/' . $data->id) . "'>Klik Disini</a>) di " . strtoupper($request->store) . " dengan Kode Pembayaran <b>" . $request->payment_code . "</b> sebelum <b>" . $request->expire_time . "</b>";
                        break;
                    default:
                        $message = "Halo.. " . $data->name . " Pesanan telah kami terima, mohon melakukan pembayaran (<a href='" . url('pembayaran/' . $data->id) . "'>Klik Disini</a>) sebelum <b>" . $request->expire_time . "</b>";
                        break;
                }
                $sendnotif = [
                    'title' => 'Proses Pembelian Tiket !',
                    'subject' => 'Pesanan ' . $data->np,
                    'url' => '',
                    'body' => $message,
                ];
                Mail::to($data->email)->send(new sendMail($sendnotif));
            } elseif ($request->transaction_status == 'expire') {
                // $sendnotif = [
                //     'title' => 'Proses Pembayaran Gagal',
                //     'subject' => 'No Pesanan ' . $data->n,
                //     'url' => '',
                //     'body' => 'Haloo Bapak/ibu ' . $data->name . ' ,pesanan Bapak?Ibu dengan nomor  ' . $data->np . ', telah Batal, dikarenankan proses pembayaran yang gagal, Silahkna pesan ulang jika Bapak/ibu ingin melakukan pembelian kembali',
                // ];
                // Mail::to($data->email)->send(new sendMail($sendnotif));
                $data->update([
                    "status" => -1
                ]);
            } elseif ($request->transaction_status == 'settlement') {
                if ($data->status == 0) {
                    $data->update([
                        "status" => 2,
                    ]);
                    $undian = tiket::find(1);
                    $indexundian = $undian->indexundian;
                    Cookie::forget('bayar');
                    for ($i = 1; $i <= $data->qty; $i++) {
                        $uu = ++$indexundian;

                        tjual1::create([
                            'id' => Str::uuid(),
                            'tjual_id' => $data->id,
                            'status' => 0,
                            "noundian" => $uu

                        ]);
                    }
                    $undian->update([
                        "indexundian" => $uu
                    ]);
                    //send Notification if success
                    //mail notificatiion
                    $sendnotif = [
                        'title' => 'Pembelian Tiket Berhasil!',
                        'subject' => 'Proses Pembayaran Berhasil!',
                        'url' => '',
                        'body' => 'Haloo Bapak/ibu ' . $data->name . ' , Silahkan download data Tiket anda  (<a href=" ' . url('download/' . $data->id) . '">Klik Disini</a>)  ',
                    ];
                    Mail::to($data->email)->send(new sendMail($sendnotif));
                    $email = new DiskonController;
                    $email->resend($data->id, false);
                }
            } elseif ($request->transaction_status == 'cancel') {
                if ($data->status == 0 || $data->status == 1) {
                    $data->update([
                        "status" => -1,
                    ]);
                }
            }

            // return response()->json("Hai Dude, Whatsup....!");
        } else {
            return 'salah';
        }
    }
    public function resend($id)
    {
        $data = tjual::findorFail($id);
        if ($data->status == 2) {
            //mail notificatiion
            $sendnotif = [
                'title' => 'Pembelian Tiket Berhasil!',
                'subject' => 'Download Tiket Sekarang',
                'url' => '',
                'body' => 'Haloo Bapak/ibu ' . $data->name . ' , Silahkan download data Tiket anda  (<a href=" ' . url('tiket/' . $data->id) . '">Klik Disini</a>) ',
            ];
            Mail::to($data->email)->send(new sendMail($sendnotif));
        }
    }
}
