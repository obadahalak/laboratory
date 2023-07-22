<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComapnyRequest extends FormRequest
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
           'company_id'=>'company'
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
                    'company_id' => ['required', function ($attribute, $value, $fail) {
                        if (!auth('lab')->user()->companies->find($value)) {
                            $fail('The ' . $attribute . ' is invalid.');
                        }
                    },],
                ];

                break;
            case 'DELETE':
                return [
                    'company_id' => ['required', function ($attribute, $value, $fail) {
                        if (!auth('lab')->user()->companies->find($value)) {
                            $fail('The ' . $attribute . ' is invalid.');
                        }
                    },],
                ];

                break;
        }
    }
}
