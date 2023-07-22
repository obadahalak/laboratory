<?php

namespace App\Http\Requests;

use App\Rules\AccountingRule;
use Illuminate\Foundation\Http\FormRequest;

class AccountingRequest extends FormRequest
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
            'payment_amount'=> 'payment amount',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        switch ($this->method())
        {
            case 'POST':

                        return [

                            'day'         => ['required' , 'string' , 'max:10'],
                            'date'      => ['required' , 'date_format:Y-m-d'],
                            'payment_amount' => ['required' , 'numeric'],
                            'note'    => ['required' , 'string'],
                            'status'  => ['required' , 'boolean']
                        ];
                break;
            case 'PUT' :

                return [


                      'day'         => ['string' , 'max:10'],
                      'date'      => ['date_format:Y-m-d'],
                      'Payment_amount' => ['numeric'],
                      'Note'    => ['string'],
                      'status'  => ['boolean']
                  ];
                break;
                case 'DELETE':

                    return [
                        'accounting_ids'=>[new AccountingRule()],
                    ];
                    break;
        }

    }
}
