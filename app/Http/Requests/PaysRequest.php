<?php

namespace App\Http\Requests;

use App\Rules\PayRule;
use Illuminate\Foundation\Http\FormRequest;

class PaysRequest extends FormRequest
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
    public function attributes()
    {
        return [
            'bills_id' => 'bills',
            'Amount_ID' => 'Amount',
            'Amount_dolar' => 'Amount dolar'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'bills_id'       => ['required'],
                    'Amount_ID'    => ['numeric'],
                    'Amount_dolar'   => ['numeric'],
                ];
                break;
            case 'DELETE':

                return [
                    'pays_ids' => [new PayRule()],
                ];
                break;
        }
    }
}
