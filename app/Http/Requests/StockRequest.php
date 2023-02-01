<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
            'stocks' => 'present|array',
            'stocks.*.produit_id' => 'required',
            'stocks.*.qte' => 'required|numeric|gt:0',
            'stocks.*.puht' => 'required|numeric|gt:0',
            'stocks.*.tva' => 'required|numeric|gt:0',
        ];
    }
}
