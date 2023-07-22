<?php

namespace App\Http\Requests;

use App\Models\Section;
use Illuminate\Foundation\Http\FormRequest;

class SortingSectionRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if (!$this->route()->getName() == 'adminMoveup' || !$this->route()->getName() == 'adminMovedown') {


            return [
                'section_id' => ['required', function ($attribute, $value, $fail) {
                    if (!auth('lab')->user()->Section->find($value)) {
                        $fail('section selected invalid');
                    }
                }],
            ];
        }
        else{

            return [
                'section_id' => ['required', function ($attribute, $value, $fail) {
                    if (! Section::AdminSections()->find($value)) {
                        $fail('section selected invalid');
                    }
                }],
            ];
        }
    }
}
