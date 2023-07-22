<?php

namespace App\Http\Requests;

use App\Rules\testUnitRule;
use Illuminate\Foundation\Http\FormRequest;

class testUnitRequest extends FormRequest
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
            'test_unit' => 'test unit',
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
                    'test_unit' => ['required', 'min:2', 'max:255', new testUnitRule(null)]

                ];
                break;
            case 'PUT':
                return [
                    'test_unit' => ['required', 'min:2', 'max:255', new testUnitRule(request()->test_unit_id)],
                    'test_unit_id' => ['required', function ($attribute, $value, $fail) {
                        if (!auth()->user('lab')->testUnit->find($value)) {
                            $fail('The ' . $attribute . ' is invalid.');
                        }
                    },],
                ];
            case 'DELETE':
                return [
                    'test_unit_id' => ['required', function ($attribute, $value, $fail) {
                        if (!auth()->user('lab')->testUnit->find($value)) {
                            $fail('The ' . $attribute . ' is invalid.');
                        }
                    },],
                ];
                break;
        }
    }
}
