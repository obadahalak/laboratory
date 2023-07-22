<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentMethodRequest extends FormRequest
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
            'payment_id'=>'payment',

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
                    'name' => 'required|min:2|max:255',
                ];
                break;
            case 'PUT':
                return [
                    'name' => 'required|min:2|max:255',
                    'payment_id' => [
                        'required', function ($attribute, $value, $fail) {
                            if (!auth('lab')->user()->paymentMethod->find($value)) {
                                $fail('The payment is invalid.');
                            }
                        },
                    ],
                ];

                break;

            case 'DELETE':

                return [

                    'payment_id' => [
                        'required', function ($attribute, $value, $fail) {
                            if (!auth('lab')->user()->paymentMethod->find($value)) {
                                $fail('The payment is invalid.');
                            }
                        },
                    ],
                ];


                break;
        }
    }
}
