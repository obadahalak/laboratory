<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResultRequest extends FormRequest
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
            case 'POST':
        return [

            'patient_id' => ['required' , 'exists:patients,id'],
            'histo_result' => [Rule::requiredIf(fn () => !request()->main_report_result && !request()->culture_report_result), 'array'],
            'histo_result.*.text' => ['string'],


            'culture_report_result' => [Rule::requiredIf(fn () => !request()->histo_result && !request()->main_report_result), 'array'],
            'culture_report_result.*.sub_test' => ['string'],
            'culture_report_result.*.help_result' => ['string' , 'nullable'],

            'main_report_result' => [Rule::requiredIf(fn () => !request()->culture_report_result && !request()->histo_result), 'array'],
            'main_report_result.*.H' => ['string', 'numeric'],
            'main_report_result.*.L' => ['string', 'numeric'],
            'main_report_result.*.result' => ['string', 'numeric' , 'nullable'],


             ];
             break;
             case 'PUT':

                 return [
                    'histo_result' => [Rule::requiredIf(fn () => !request()->main_report_result && !request()->culture_report_result), 'array'],
                    'histo_result.*.text' => ['string'],


                    'culture_report_result' => [Rule::requiredIf(fn () => !request()->histo_result && !request()->main_report_result), 'array'],
                    'culture_report_result.*.sub_test' => ['string'],
                    'culture_report_result.*.help_result' => ['string' , 'nullable'],

                    'main_report_result' => [Rule::requiredIf(fn () => !request()->culture_report_result && !request()->histo_result), 'array'],
                    'main_report_result.*.H' => ['string', 'numeric'],
                    'main_report_result.*.L' => ['string', 'numeric'],
                    'main_report_result.*.result' => ['string', 'numeric' , 'nullable'],
                 ];
                 break;
         }
     }
     }
