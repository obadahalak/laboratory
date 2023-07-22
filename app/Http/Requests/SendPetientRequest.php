<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Rules\cheackPatientAnalysIdRule;
use Illuminate\Foundation\Http\FormRequest;

class SendPetientRequest extends FormRequest
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


            'patient_id'=>'patient',
            'recived_id'=>'recived',
            'lab_id'=>'lab',
            'patieon_analys_id'=>'patient analyse',
            'shipping_cost' => 'shipping cost',
            'total_amount'  => 'total amount',
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
            'send_id' => ['required', Rule::in('lab_id', 'recived_id')],
            'patient_id'    => ['required', 'exists:patients,id'],
            'recived_id'    => ['required_if:send_id,recived_id', function ($attr, $value, $fail) {
                if ($value == auth('lab')->user()->id && Rule::exists('accounts', 'id')) {
                    $fail(" This " . $attr . " is invaled ");
                }
            }],
            'lab_id'        => ['required_if:send_id,lab_id', function ($attr, $value, $fail) {
                if (!auth('lab')->user()->labs->find($value)) {
                    $fail(" This " . $attr . " is invaled");
                }
            }],
            'patieon_analys_id'    => ['required', 'exists:patieon_analys,id',new cheackPatientAnalysIdRule(request()->patient_id)
            ],
            'shipping_cost' => ['required', 'numeric', 'min:1'],
            'total_amount'  => ['required', 'numeric', 'min:1'],

        ];
    }
}
