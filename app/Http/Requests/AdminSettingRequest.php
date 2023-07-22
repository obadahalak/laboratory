<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminSettingRequest extends FormRequest
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
        return ['image'=>'tupe image'];
    }
    /**
     * Get the validation rules that apply to the   request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':

                if(!$this->route()->name=="updateTupe")
                return [
                    'tupe' => 'required|min:2|max:255',
                    'image'=>'required|image|max:5000',

                ];

                else{
                    return [
                        'id' => ['exists:admin_tupes,id'],
                        'tupe'=>['string','min:2','max:255'],
                        'image'=>['image','max:5000'],
                    ];
                }
                break;

            case 'DELETE':
                return [
                    'id' => ['exists:admin_tupes,id'],
                    'ids' => ['array'],
                    'ids.*' => ['exists:admin_tupes,id'],
                ];
                break;
    }
}
}
