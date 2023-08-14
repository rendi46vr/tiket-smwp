<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class adddiskon extends FormRequest
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
            'jenisTiket' => 'required',
            'tanggalMulai' => 'required|date',
            'tanggalAkhir' => 'required|date|after_or_equal:tanggalMulai',
            'minimalQuantity' => 'required|integer|min:1|max:100',
            'diskonPersen' => 'required',
            'nilaiDiskon' => 'required|numeric|min:0',

        ];
    }
    public function messages()
    {
        return [
            'jenisTiket.required' => 'Data tidak valid',
            'tanggalMulai.required' => 'Tanggal harus Diisi',
            'tanggalMulai.date' => 'Data bukan tanggal (ip anda kami pantau)',
            'tanggalAkhir.required' => 'Tanggal harus Diisi',
            'tanggalAkhir.date' => 'Data bukan tanggal (ip anda kami pantau)',
            'minimalQuantity.required' => 'Quantity Harus diisi',
            'diskonPersen.required' => 'Harus diisi',
            'nilaiDiskon.required' => 'Harus diisi',
        ];
    }
    public function attributes()
    {
        return [
            'jenisTiket' => 'Jenis Tiket',
            'tanggalMulai' => ' Tanggal Mulai',
            'tanggalAkhir' => 'Tanggal Akhir',
            'minimalQuantity' => 'Quantity',
            'diskonPersen' => 'Diskon on Persen',
            'nilaiDiskon' => 'Nilai Diskon',
        ];
    }
}
