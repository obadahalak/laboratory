<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AdminAuthRequest extends FormRequest
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

            'new_password'=> 'new password',
            'old_password'=> 'old password',
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
            case 'GET':
                return [
                    'email' => 'required|exists:admins,email',
                    'password' => 'required',
                ];
                break;

            case 'PUT':
                return [
                    'email' => ['required', Rule::unique('admins')->ignore(auth('admin')->user()->id)],
                    'name' => 'required|min:3|max:255',
                    'old_password' => '',
                    'new_password' => [Rule::requiredIf(request()->old_password != null), 'min:6', 'max:30', 'confirmed'],
                ];
                break;
        }
    }
}
