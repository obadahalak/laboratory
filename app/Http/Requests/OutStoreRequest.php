<?php

namespace App\Http\Requests;

use App\Rules\OutStoreRule;
use Illuminate\Foundation\Http\FormRequest;

class OutStoreRequest extends FormRequest
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
        switch ($this->method()) {
        case 'DELETE':

            return [
                'out_store_ids'=>[new OutStoreRule()],
            ];
            break;
    }
}
}
