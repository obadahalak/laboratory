<?php

namespace App\Http\Requests;

use App\Rules\StoreRule;
use Illuminate\Foundation\Http\FormRequest;

class storeRequest extends FormRequest
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

            'test_unit_id' => 'test_unit',
            'product_name'=>'product name'
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
                    'product_name'           => ['required'],
                    'image'                  => ['nullable'],
                    'description'            => ['required', 'min:3', 'max:255'],
                    'company'                => ['required',  'min:3', 'max:255'],
                    'expire_date'            => ['required', 'date_format:Y-m-d'],
                    'model'                  => ['required', 'min:3', 'max:255'],
                    'test_unit_id'           => ['required', 'exists:test_units,id'],
                    'quantity'               => ['required', 'numeric'],
                ];
                break;
            case 'DELETE':

                return [
                    'store_ids' => [new StoreRule()],
                ];
                break;
        }
    }
}
