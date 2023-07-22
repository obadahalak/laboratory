<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
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
    public function attributes()
    {
        return [

            'specialization_id' => 'specialization',
            'work_start'=>'work start',
            'job_title_id'=>'job title',
            'specialization_id'=>'specialization',
        ];
    }
    public function rules()
    {
        return [
            'name'              => ['required' , 'string'],
            'image'             => ['required', 'image'],
            'DOB'               => ['required'],
            'work_start'        => ['required'],
            'job_title_id'      => ['exists:job_titles,id'],
            'specialization_id' => ['exists:specializations,id'],
            'phone'             => ['required' , 'numeric'],
            'address'           => ['required' , 'string'],
            'email'             => ['required' , 'string' , 'unique:staff,email' , 'email'],
            'note'              => ['required' , 'string'],
            'salary'            => ['numeric'],
            'experiance'        => ['string'],
            'collage'           => ['string'],

        ];
    }
}
