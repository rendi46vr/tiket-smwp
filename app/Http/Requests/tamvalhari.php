<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class tamvalhari extends FormRequest
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
        return  [
            'dari' => 'required|date',
            'sampai' => 'required|date|after_or_equal:dari',
        ];
    }
    public function messages()
    {
        return [
            'dari.required' => 'Tanggal harus Diisi',
            'dari.date' => 'Data bukan tanggal (ip anda kami pantau)',
            'sampai.required' => 'Tanggal harus Diisi',
            'sampai.date' => 'Data bukan tanggal (ip anda kami pantau)',
        ];
    }
    public function attributes()
    {
        return [
            'dari' => ' Tanggal ',
            'sampai' => 'Tanggal ',
        ];
    }
}
