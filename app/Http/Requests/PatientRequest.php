<?php

namespace App\Http\Requests;

use Exception;
use App\Models\Patient;
use App\Models\Section;
use App\Rules\AnalysRule;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;

// use Illuminate\Validation\Validator;
use App\Rules\cheackPatientAnalysIdRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class PatientRequest extends FormRequest
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
            'gender_id'=> 'gender',
            'phone_number'=>' phone number',
            'analyzes.*.payment_method_id' => 'payment method',
            'date_of_visit'=> 'date of visit',
            'receive_of_date'=> 'receive of date',
            'send_method_id'=> 'send method id',

            'analyzes.*.doctor_id'=> 'doctor',
            'analyzes.*.price_doctor'=>'price for doctor',
            'analyzes.*.ratio_price'=>'ratio for doctor',
            'analyzes.*.company_id'=> 'company',
            'analyzes.*.price_company'=>'price for company',
            'analyzes.*.price_lab'=>'price for lab',
            'analyzes.*.lab_id'=>'lab',
            'analyzes.*.section_id'=>'section',
            'analyzes.*.analyz_id'=>'analyze',

            'analyzes.*.emergency' =>'emergency',
            'analyzes.*.notes' =>'note',


            'analyzes.*.price_analysis' =>'price for analyze',
            'analyzes.*.paid_up' =>'paid up',
            'analyzes.*.duo' =>'duo',
            'analyzes.*.discount' =>'discount',


        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $patientIdRule = ['patientId' => ['required', function ($attribute, $value, $fail) {
            if (!auth('lab')->user()->patients->find($value)) {
                $fail("the patient invalid");
            }
        },]];

        $analysId = ['analysId' => new cheackPatientAnalysIdRule(request()->patientId)];

        $patientRules = [
            'name' => 'required|min:3|max:15',
            'age' => 'required|integer|min:1|max:100',
            'email' => ['required', 'email', 'unique:patients,email'],
            'address' => 'required|max:255',
            'phone_number' => 'required',
            'gender_id' => ['required', 'integer', function ($attribute, $value, $fail) {
                if (!auth('lab')->user()->Genders->find($value)) {
                    $fail('The gender is invalid.');
                }
            }],
            'date_of_visit' => 'required|date|date_format:Y-m-d',
            'receive_of_date' => 'required|date|date_format:Y-m-d',

            'price_analysis' => ['required','numeric'],
            'paid_up' => 'required|numeric',
            'duo' => 'required|numeric',
            'discount' => 'nullable|numeric',
        ];

        $AnalysisRules = [

            'analyzes.*.send_method_id' => ['required', 'integer', function ($attribute, $value, $fail) {

                if (!auth('lab')->user()->sendMethod->find($value)) {
                    $fail('The send method is invalid.');
                }
            }],


            'analyzes.*.price_doctor' => 'required_with:analyzes.*.doctor_id',
            'analyzes.*.ratio_price' => 'required_with:analyzes.*.doctor_id',
            'analyzes.*.doctor_id' => [
                'required_without_all:analyzes.*.company_id,analyzes.*.lab_id',

                'integer',
                function ($attribute, $value, $fail) {
                    if (!auth('lab')->user()->doctors->find($value)) {
                        $fail('The doctor is invalid.');
                    }
                }
            ],

            'analyzes.*.price_company' => ['required_with:analyzes.*.company_id'],

            'analyzes.*.company_id' => [
                'required_without_all:analyzes.*.doctor_id,analyzes.*.lab_id',

                'integer', function ($attribute, $value, $fail) {
                    if (!auth('lab')->user()->companies->find($value)) {
                        $fail('The company is invalid.');
                    }
                }
            ],

            'analyzes.*.price_lab' => 'required_with:analyzes.*.lab_id',
            'analyzes.*.lab_id' => [
                'required_without_all:analyzes.*.doctor_id,analyzes.*.company_id',
                'integer', function ($attribute, $value, $fail) {
                    if (!auth('lab')->user()->labs->find($value)) {
                        $fail('The lab is invalid.');
                    }
                }
            ],


            'analyzes.*.section_id' => ['nullable','array', new AnalysRule(request()->analyzes ?? ''), function ($attribute, $value, $fail) {
                if (!auth('lab')->user()->Section->find($value)) {
                    $fail('The section is invalid.');
                }


            }],


            'analyzes.*.analyz_id' => ['nullable', 'integer', function ($attribute, $value, $fail) {
                if (!auth('lab')->user()->analyz->find($value)) {
                    $fail('The analyze  is invalid.');
                }
            }],

            'analyzes.*.emergency' => 'required|boolean',
            'analyzes.*.notes' => 'required',



            'analyzes.*.payment_method_id' => ['required', 'integer', function ($attribute, $value, $fail) {
                if (!auth('lab')->user()->paymentMethod->find($value)) {
                    $fail('The payment method is invalid.');
                }
            }]

        ];
        $rules = [
            ...$patientRules,
            ...$AnalysisRules
        ];

        switch ($this->method()) {

            case 'POST':
                return ['analyzes'=>'required',...$rules];
                break;
            case 'PUT':

                $patientRules['email'][2] = Rule::unique('patients', 'email')->ignore(request()->patientId);
                $updateRules = [
                    ...$patientIdRule,
                    ...$patientRules,

                ];
                if (request()->analysId) {
                    return   [...$AnalysisRules, ...$updateRules, ...$analysId];
                }
                return $updateRules;


                break;
            case 'GET':
                if ($this->route()->getName() === 'getPatient') {

                    return   ['patientId' => 'required|exists:patients,id'];
                }
                break;

            case 'DELETE':

                return   $patientIdRule;

                break;
        }
    }
}
