<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
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
            'src'=>'image',
            'lab_name'=> 'lab name',
            'new_password'=> 'new password',
            // 'old_password'=> 'old password',
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
                    'name' => 'required|max:40',
                    'email' => 'required|email|unique:accounts,email',
                    'password' => 'required|min:6|max:30',
                    'lab_name' => 'required|unique:accounts,lab_name',
                    'address'=>'required',
                    'phone' => 'required',
                    'src' => 'required|image|max:5000',

                ];
                break;

            case 'PUT':
                return [
                    'account_id'=>'required|exists:accounts,id',
                    'new_password' => ['min:6', 'max:30','confirmed'],
                    'name' => 'required|max:20',
                    'lab_name' => [
                        'required',
                        'string',
                        'max:255',
                        Rule::unique('accounts', 'lab_name')->ignore(request()->account_id)
                    ],
                    'address'=>'required',
                    'email' => 'required|email|unique:accounts,email,'.request()->account_id,
                    'phone' => 'required',
                    'image' => ['image', 'max:5000'],

                ];
               break;
        }
    }


}
