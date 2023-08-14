<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\tgltiket;

class cetakTiket extends FormRequest
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
            'qty' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value > 100) {
                        $fail('Nilai tidak boleh lebih dari 100.');
                    }
                },
            ],
            'tgl' => '',
            'jenis_tiket' => 'required|max:1',
        ];
        if (empty($rules)) {
            $data = [
                'qty' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if ($value > 100) {
                            $fail('Nilai tidak boleh lebih dari 100.');
                        }
                    },
                ],
                'tgl' => '',
                'jenis_tiket' => 'required|max:1',
            ];
        } else {
            if ($rules[1] == 2) {
                if ($rules['0'] != null) {
                    if (in_array($rules['0'], $this->checkDate)) {
                        $data = [
                            'qty' => [
                                'required',
                                function ($attribute, $value, $fail) {
                                    if ($value > 100) {
                                        $fail('Nilai tidak boleh lebih dari 100.');
                                    }
                                },
                            ],
                            'tgl' => '',
                            'jenis_tiket' => 'required|max:1',
                        ];
                    } else {
                        $data = [
                            'qty' => [
                                'required',
                                function ($attribute, $value, $fail) {
                                    if ($value > 100) {
                                        $fail('Nilai tidak boleh lebih dari 100.');
                                    }
                                },
                            ],
                            'tgl' => '',
                            'jenis_tiket' => 'required|max:1',
                        ];
                    }
                } else {
                    $data = [
                        'qty' => [
                            'required',
                            function ($attribute, $value, $fail) {
                                if ($value > 100) {
                                    $fail('Nilai tidak boleh lebih dari 100.');
                                }
                            },
                        ],
                        'tgl' => '',
                        'jenis_tiket' => 'required|max:1',
                    ];
                }
            }
        }
        return $data;
    }
    public function messages()
    {
        return [
            'qty' => 'Jumlah tiket harus diisi',
            'jenis_tiket.required' => "Total Tiket harus Diisi",
            'tgl.required' => "tiket berlaku hanya weekend dan libur nasional",
            'tgl.max' => "tiket berlaku hanya weekend dan libur nasional",
        ];
    }

    public function attributes()
    {
        return [
            'qty' => 'Jumlah Tiket',
            'tgl' => 'Tanggal',
        ];
    }
}
