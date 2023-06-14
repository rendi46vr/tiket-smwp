<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'qty' => 'required',
            'name' => 'required',
            'tgl' => 'required',
            'wa' => 'required:max:60',
            'email' => 'required|email:dns',
        ];
    }
    public function messages()
    {
        return [
            'qty.required' => "Total Tiket harus Diisi",
            'tgl.required' => "Tanggal Tiket harus disii",
            'wa.required' => 'Whatsapp harus didisi',
            'name.required' => 'Nama wajib disii!',
            'email.required' => 'Email Harus Diisi',
            'email.email' => 'Email harus valid'
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
