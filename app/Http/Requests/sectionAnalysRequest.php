c8847027d385<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class sectionAnalysRequest extends FormRequest
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
            'section_id'=>'section'
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
            'section_id'=>['required', function($attribute, $value, $fail){
                if(!  auth('lab')->user()->analyz->where('section_id',$value)){
                    $fail('The ' . $attribute . ' is invalid.');
                }
            }]
        ];
    }
}
