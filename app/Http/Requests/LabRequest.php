<?php

namespace App\Http\Requests;

use App\Rules\LabRule;
use Illuminate\Foundation\Http\FormRequest;

class LabRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function attributes()
    {
        return [
           'lab_name'=>'lab name',
        ];

    }
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
        switch ($this->method()) {
            case 'POST':
        return [

           'lab_name'  => ['required' , 'string' , 'unique:labs,lab_name'],
           'phone'     => ['required' , 'numeric'],
           'address'   => ['required' , 'string'],
           'code'  => ['unique:labs,code'],

        ];
        break;
        case 'DELETE':

            return [
                'lab_ids'=>[new LabRule()],
            ];
            break;

    }
}
    }

