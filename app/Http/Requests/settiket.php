<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class settiket extends FormRequest
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
            'hargaregular-day' => 'required',
            'batasregular-day' => 'required',
            'statusregular-day' => 'max:3',
            'hargapremium-day' => 'required',
            'bataspremium-day' => 'required',
            'statuspremium-day' => 'max:3',

        ];
    }
    public function messages()
    {
        return [
            'hargaregular-day.required' => "Harga harus Diisi",
            'batasregular-day.required' => "Batas harus Diisi",
            'statusregular-day.max' => "Someting wrong there!",
            'hargaregular-day.required' => "Harga harus Diisi",
            'batasregular-day.required' => "Batas harus Diisi",
            'statusregular-day.max' => "Someting wrong there!",
        ];
    }

    public function attributes()
    {
        return [
            'hargaregular-day' => 'Harga',
            'batasregular-day' => 'Batas',
            'statusregular-day' => '',
            'hargapremium-day' => 'Harga',
            'bataspremium-day' => 'Batas',
            'statuspremium-day' => '',
        ];
    }
}
