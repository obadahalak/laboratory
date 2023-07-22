<?php

namespace App\Http\Requests;

use App\Rules\DoctorRule;
use Illuminate\Foundation\Http\FormRequest;

class DoctorRequest extends FormRequest
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
           'src'=>'image'
        ];

    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules=[
            'name' => 'required|min:3',
            'phone' => 'required',
            'address' => 'required',
            'email' => ['required', 'unique:doctors,email'],
            'password' => 'required|min:6',
            'gender' => 'required',
            'ratio' => 'required|min:1|max:90',
            'src'=>'image|max:5000'
        ];
        if($this->isMethod('POST')){
            return $rules;     
        }
        if($this->isMethod('PUT')){
            return [
                'password'=>['required','min:6','max:30','confirmed']
            ];
            
          
        }
        if($this->isMethod('DELETE')){
            return [
                'doctor_ids'=>[new DoctorRule()],
            ];
        }
       
    }
}
