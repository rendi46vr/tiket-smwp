<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\tgltiket;

class kirimtiket extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public  $checkDate;

    public function __construct()
    {
        $this->checkDate = tgltiket::where('status', 2)->pluck('tgl')->toArray();
    }
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules($rules = [])
    {
        $data = $data = [
            'qty' => 'required|max:5',
            'name' => 'required|max:255',
            'email' => 'required|email:dns',
            'jenis_tiket' => 'required|max:1',
        ];

        return $data;
    }
    public function messages()
    {
        return [
            'qty.required' => 'Jumlah tiket harus diisi',
            'jenis_tiket.required' => "Jenis Tiket harus Diisi",
            'email.required' => "Email tidak Valid",
            'name.required' => "Nama pernerima wajib disii",
        ];
    }

    public function attributes()
    {
        return [
            'qty' => 'Jumlah Tiket',
            'name' => 'Nama Penerima',
            'email' => 'Email',
            'jenis_tiket' => 'Jenis Tiket',

        ];
    }
}
