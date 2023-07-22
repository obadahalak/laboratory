<?php

namespace App\Http\Requests;

use App\Rules\GenderRule;
use Illuminate\Foundation\Http\FormRequest;

class GenderRequest extends FormRequest
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
           'gender_id'=>'gender'
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
                    'name' => ['required','min:2','max:255',new GenderRule(null)],
                ];
                break;
            case 'PUT':
                return [
                    'name' => ['required','min:2','max:255',new GenderRule(request()->gender_id)],
                    'gender_id' => [
                        'required', function ($attribute, $value, $fail) {
                            if (!auth('lab')->user()->Genders->find($value)) {
                                $fail('The ' . $attribute . ' is invalid.');
                            }
                        },
                    ],
                ];

                break;

            case 'DELETE':

                return [

                    'gender_id' => [
                        'required', function ($attribute, $value, $fail) {
                            if (!auth('lab')->user()->Genders->find($value)) {
                                $fail('The ' . $attribute . ' is invalid.');
                            }
                        },
                    ],
                ];


                break;
        }
    }
}
