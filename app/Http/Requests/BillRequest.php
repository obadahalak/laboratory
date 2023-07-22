<?php

namespace App\Http\Requests;

use App\Rules\BillRule;
use Illuminate\Foundation\Http\FormRequest;

class BillRequest extends FormRequest
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
            'supplier_id'=>'supplier',
            'invoice_number'=> 'invoice number',
            'date_invoice'=> 'date invoice',
            'total_ID'=> 'total',
            'total_dolar'=>'total dolar'
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
            'supplier_id'               => ['required'],
            'invoice_number'            => ['required'],
            'date_invoice'              => ['required'],
            'total_ID'                     => ['numeric'],
            'total_dolar'                     => ['numeric'],


        ];
        break;
        case 'DELETE':

            return [
                'bills_ids'=>[new BillRule()],
            ];
            break;
    }
}
}
