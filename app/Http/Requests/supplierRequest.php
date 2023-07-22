<?php

namespace App\Http\Requests;

use App\Rules\SupplireRule;
use Illuminate\Foundation\Http\FormRequest;

class supplierRequest extends FormRequest
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
            'scientific_office_name'=>'scientific office name',
            'maintain_phone'=>'maintain phone',
            'added_date'=> 'added date'

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
                    'scientific_office_name'    => ['required'],
                    'added_date'                => ['required', 'date'],
                    'phone'                     => ['required', 'numeric'],
                    'maintain_phone'            => ['required', 'numeric'],
                    'address'                   => ['required'],

                ];
                break;
            case 'DELETE':

                return [
                    'supplier_ids'=>[new SupplireRule()],
                ];
                break;
        }
    }
}
