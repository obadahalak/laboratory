<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffUpdateRequest extends FormRequest
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

            'specialization_id' => 'specialization',
            'work_start'=>'work start',
            'job_title_id'=>'job title',
            'specialization_id'=>'specialization',
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
            'name'              => ['required','string'],
            'image'             => ['required','image','max:5000'],
            'DOB'               => ['required','string'],
            'work_start'        => ['required','string'],
            'job_title_id'      => ['required','exists:job_titles,id'],
            'specialization_id' => ['required','exists:specializations,id'],
            'phone'             => ['required','numeric'],
            'address'           => ['required','string'],
            'email'             => ['required', 'email' ,'string' , 'unique:staff,email,' . $this->route('stuffId')],
            'note'              => ['required','string'],
            'salary'            => ['required','numeric'],
            'experiance'        => ['required','string'],
            'collage'           => ['required','string'],

        ];
    }
}
