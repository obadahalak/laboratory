<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class LabAccountRequest extends FormRequest
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
           'old_passwod'=>'old password',
           'new_passwod'=>'new password',
           'src'=>'image',
        ];

    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'old_password' => 'nullable',
            'new_password' => 'min:6|confirmed',
            'lab_name' => [
                'string',
                'max:255',
                Rule::unique('accounts', 'lab_name')->ignore(auth('lab')->user()->id),
            ],
            'phone' => '',
            'src' => 'image|max:5000',
        ];
    }
}
