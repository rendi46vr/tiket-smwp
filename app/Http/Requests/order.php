<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\tgltiket;

class order extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }
    public  $checkDate;

    public function __construct()
    {
        $this->checkDate = tgltiket::where('status', 2)->pluck('tgl')->toArray();
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules($rules = [])
    {
        $data = [
            'qty' => 'required',
            'name' => 'required',
            'tgl' => '',
            'wa' => 'required:max:60',
            'email' => 'required|email:dns',
        ];

        if (empty($rules)) {
            $data = [
                'qty' => 'required',
                'name' => 'required',
                'tgl' => '',
                'wa' => 'required:max:60',
                'email' => 'required|email:dns',
            ];
        } else {
            if ($rules[1] == 2) {
                if ($rules['0'] != null) {
                    if (in_array($rules['0'], $this->checkDate)) {
                        $data = [
                            'qty' => 'required|max:5',
                            'name' => 'required',
                            'wa' => 'required:max:60',
                            'email' => 'required|email:dns',
                            'tgl' => '',
                            'jenis_tiket' => 'required|max:1',
                        ];
                    } else {
                        $data = [
                            'qty' => 'required|max:5',
                            'tgl' => '',
                            'jenis_tiket' => 'required|max:1',
                            'name' => 'required',
                            'wa' => 'required:max:60',
                            'email' => 'required|email:dns',
                        ];
                    }
                } else {
                    $data = [
                        'qty' => 'required|max:5',
                        'tgl' => '',
                        'jenis_tiket' => 'required|max:1',
                        'name' => 'required',
                        'wa' => 'required:max:60',
                        'email' => 'required|email:dns',
                    ];
                }
            }
        }
        return $data;
    }
    public function messages()
    {
        return [
            'qty.required' => "Total Tiket harus Diisi",
            'wa.required' => 'Whatsapp harus didisi',
            'name.required' => 'Nama wajib disii!',
            'email.required' => 'Email Harus Diisi',
            'email.email' => 'Email harus valid',
            // 'tgl.required' => "Hanya weekend dan libur nasional",
            // 'tgl.max' => "Hanya weekend dan libur nasional",
        ];
    }

    public function attributes()
    {
        return [
            'qty' => 'Quantity',
            'name' => 'Nama',
            'tgl' => 'Tanggal',
            'wa' => 'Nomor whatsapp',
            'email' => 'Email',
        ];
    }
}
