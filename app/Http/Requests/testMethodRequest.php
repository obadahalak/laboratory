<?php

namespace App\Http\Requests;

use App\Rules\testMethodRule;
use Illuminate\Foundation\Http\FormRequest;

class testMethodRequest extends FormRequest
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
            'test_method_id' => 'test method',
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
                    'test_method' => ['required', 'min:2', 'max:255', new testMethodRule(null)],

                ];
                break;
            case 'PUT':
                return [
                    'test_method' => ['required', 'min:2', 'max:255', new testMethodRule(request()->test_method_id)],
                    'test_method_id' => ['required', function ($attribute, $value, $fail) {
                        if (!auth('lab')->user()->testMethod->find($value)) {
                            $fail('The ' . $attribute . ' is invalid.');
                        }
                    },],
                ];
            case 'DELETE':
                return [
                    'test_method_id' => ['required', function ($attribute, $value, $fail) {
                        if (!auth('lab')->user()->testMethod->find($value)) {
                            $fail('The ' . $attribute . ' is invalid.');
                        }
                    },],
                ];
                break;
        }
    }
}
