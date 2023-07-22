<?php

namespace App\Http\Requests;

use App\Models\Section;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class patientValidation extends FormRequest
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

    public function cheachMainSection($ids, $value)
    {
        $countOfIds = count($ids);
        $countOfSections = auth('lab')->user()->Section->find($value);

        if ($countOfIds == $countOfSections->count())
            return true;

        return false;
    }
    public function cheachAnalys($ids, $value)
    {
        $countOfIds = count($ids);
        $countOfAnalysis = auth('lab')->user()->analyz->find($value);

        if ($countOfIds == $countOfAnalysis->count())
            return true;

        return false;
    }

    public function attributes()
    {
        return [
            'main_analysis'=> 'main analyze',
            'analyses_id'=> 'analyse',
            'sections_id'=>'section',
            'data.doctor_id'=>'doctor',
            'data.ratio_price'=>' ratio price',
            'data.price_doctor'=>'doctor price',
            'data.company_id'=>'company',
            'data.price_company'=>'price company',
            'data.lab_id'=>'lab',
            'data.price_lab'=>'price lab',
            'data.send_method'=>'send method',
            'data.emergency'=>'emergency',
            'data.notes'=>'notes',
            'data.payment_method_id'=>'payment method',
            'data.send_method_id'=>'send method',
            'patient.email'=>'patient email',
            'patient.name'=>'patient name',
            'patient.phone_number'=>'patient phone number',
            'patient.age'=>'patient age',
            'patient.gender_id'=>'patient gender',
            'patient.address'=>'patient address',
            'patient.date_of_visit'=>'patient date of visit',
            'patient.receive'=>'patient date of receive',
            'patient.price_analysis'=>'patient price analysis',
            'patient.duo'=>'duo',
            'patient.discount'=>'discount',
            'patient.paid_up'=>'paidup',

        ];
    }
    public function messages()
    {
        return [
            'main_analysis.required'=>'please select one analyze at less',
            'discount.required_without'=>'The discount field is required',
            'duo.required_without'=>'The duo field is required',
            'paid_up.required_without'=>'The paid_up field is required',
            'price_analysis.required_without'=>'The price analysis field is required',

        ];
    }

    public function rules()
    {
        $patientRules = [
            'patient'=>'array',
            'patient.name' => 'required_without:patientId|min:3|max:15',
            'patient.age' => 'required_without:patientId|integer|min:1|max:100',
            'patient.email' => ['required_without:patientId', 'email', 'unique:patients,email'],
            'patient.address' => 'required_without:patientId|max:255',
            'patient.phone_number' => 'required_without:patientId',
            'patient.gender_id' => ['required_without:patientId', 'integer', function ($attribute, $value, $fail) {
                if (!auth('lab')->user()->Genders->find($value)) {
                    $fail('The gender is invalid.');
                }
            }],
            'patient.date_of_visit' => 'required_without:patientId|date|date_format:Y-m-d',
            'patient.receive_of_date' => 'required_without:patientId|date|date_format:Y-m-d',

            'patient.price_analysis' => 'required_without:patientId|numeric',
            'patient.paid_up' => 'required_without:patientId|numeric',
            'patient.duo' => 'required_without:patientId|numeric',
            'patient.discount' => 'required_without:patientId|nullable|numeric',
        ];

        $mainSection = [
            'sections_id' => ['array', function ($attribute, $value, $fail) {

                if (!$this->cheachMainSection(request()->sections_id, $value)) {
                    $fail('section selected  invalid');
                }
            }]
        ];
        $mainAnalys = [
            'main_analysis' => [Rule::requiredIf( ! request()->sections_id ) ,'array'],
            'main_analysis.*' => [function ($attribute, $value, $fail) {
                if (!Section::AccoutMainAnalys()->find($value)) {
                    $fail('main analys invalid');
                }
            }]
        ];
        $Analysis = [
            'analyses_id' => [
                'required_with:sections_id',
                 'array', function ($attribute, $value, $fail) {

                    if (!$this->cheachAnalys(request()->analyses_id, $value)) {
                        $fail('analyze selected invalid');
                    }
                }
            ]

        ];


        $AnalysisRules = [

            'data.send_method_id' => ['required', 'integer', function ($attribute, $value, $fail) {

                if (!auth('lab')->user()->sendMethod->find($value)) {
                    $fail('The send method is invalid.');
                }
            }],


            'data.price_doctor' => 'required_with:data.doctor_id',
            'data.ratio_price' => 'required_with:data.doctor_id',
            'data.doctor_id' => [
                'required_without_all:data.company_id,data.lab_id', 'integer',
                function ($attribute, $value, $fail) {
                    if (!auth('lab')->user()->doctors->find($value)) {
                        $fail('The doctor selected is invalid.');
                    }
                }
            ],

            'data.price_company' => ['required_with:data.company_id'],

            'data.company_id' => [
                'required_without_all:data.doctor_id,data.lab_id',

                'integer', function ($attribute, $value, $fail) {
                    if (!auth('lab')->user()->companies->find($value)) {
                        $fail('The company selected is invalid.');
                    }
                }
            ],

            'data.price_lab' => 'required_with:data.lab_id',
            'data.lab_id' => [
                'required_without_all:data.doctor_id,data.company_id',
                'integer', function ($attribute, $value, $fail) {
                    if (!auth('lab')->user()->labs->find($value)) {
                        $fail('The lab selected is invalid.');
                    }
                }
            ],


            'data.emergency' => 'required|boolean',
            'data.notes' => 'required',

            'data.payment_method_id' => ['required', 'integer', function ($attribute, $value, $fail) {
                if (!auth('lab')->user()->paymentMethod->find($value)) {
                    $fail('The payment method is invalid.');
                }
            }]

        ];
        return [
            ...$mainSection, ...$Analysis, ...$AnalysisRules, ...$patientRules, ...$mainAnalys,
            'price_analysis' => 'required_without:patient|numeric',
            'paid_up' =>  ['required_without:patient', 'numeric'],
            'duo' =>  ['required_without:patient', 'numeric'],
            'discount' =>  ['required_without:patient', 'numeric', 'nullable'],
            'patientId' => [

                'required_without:patient', function ($attribute, $value, $fail) {
                    if (!auth('lab')->user()->patients->find($value)) {
                        $fail('the patient selected invalid');
                    }
                },

            ]
        ];
    }
}
