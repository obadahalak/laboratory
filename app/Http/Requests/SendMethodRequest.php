<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMethodRequest extends FormRequest
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

          'send_method_id'=>'send method'
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
                    'send_method_id' => [
                        'required', function ($attribute, $value, $fail) {
                            if (!auth('lab')->user()->sendMethod->find($value)) {
                                $fail('The send method id is invalid.');
                            }
                        },
                    ],
                ];

                break;

            case 'DELETE':

                return [

                    'send_method_id' => [
                        'required', function ($attribute, $value, $fail) {
                            if (!auth('lab')->user()->sendMethod->find($value)) {
                                $fail('The send method id is invalid.');
                            }
                        },
                    ],
                ];


                break;
        }
    }
}
