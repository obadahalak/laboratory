<?php

namespace App\Http\Requests;

use App\Models\Analyz;
use App\Models\Gender;
use App\Models\Section;
use App\Models\TestUnit;
use App\Models\TestMethod;
use App\Rules\mainSectionRule;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class SectionRequest extends FormRequest
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
            'section_id'=>'section',
            'test_code' => 'test code',
            'test_print_name' => 'test print name',
            'price_for_patient' =>'price for patient',
            'price_for_lap' =>'price for lap',
            'price_for_company' =>'price for company',
            'test_method_id'=>'test method',
            'test_unit_id'=>'test unit',
            'tupe_id'=>'tupe',
            'hsitopology'=>'hsitopology',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        $basicRules = [
            'once' => ['required','boolean'],
            // 'test_code' => ['required', 'unique:sections,test_code'],
            'test_code' => ['required'],
            'test_print_name' => 'required|min:2|max:255',
            'price_for_patient' => 'required|min:1|numeric',
            'price_for_lap' => 'required|min:1|numeric',
            'price_for_company' => 'required|min:1|numeric',
            'antibiotic' => 'required|boolean',
            'subject' => 'required_if:antibiotic,1',
        ];

        $rulesForClassReport = [
            'hsitopology' => [Rule::requiredIf(fn () => !request()->culture_report && !request()->mane_report), 'array'],
            'hsitopology.*.text' => ['required'],


            'culture_report' => [Rule::requiredIf(fn () => !request()->hsitopology && !request()->mane_report), 'array'],
            'culture_report.*.text' => ['required'],

            'mane_report' => [Rule::requiredIf(fn () => !request()->culture_report && !request()->hsitopology), 'array'],
            'mane_report.*.normal_range' => 'required',
            'mane_report.*.gender' => ['required', function ($attribute, $value, $fail) {
                if (!Gender::AdminGenders()->find($value)) {
                    $fail('The gender is invalid.');
                }
            }],
            'mane_report.*.h' => 'required',
            'mane_report.*.l' => 'required',
        ];

        $methods = [
            'test_unit_id' => ['required', function ($attribute, $value, $fail) {
                if (!TestUnit::whereNull('account_id')->find($value)) {
                    $fail('The test unit  is invalid.');
                }
            }],
            'test_method_id' => ['required', function ($attribute, $value, $fail) {
                if (!TestMethod::whereNull('account_id')->find($value)) {
                    $fail('The test method is invalid.');
                }
            }],


            'tupe_id' => ['required', 'exists:admin_tupes,id'],
        ];


        $rulesIfOnceTrue = [...$basicRules, ...$rulesForClassReport, ...$methods];
        $rulesIfOnceFalse = ['name' => 'required|min:2|max:255', 'once' => 'required|boolean'];
        switch ($this->method()) {

            case 'GET':
                return ['section_id' =>
                ['required', function ($attribute, $value, $fail) {
                    if (!Section::AdminMainSection()->find($value)) {
                        $fail('The section  is invalid.');
                    }
                }]];
                break;
            case 'POST':
            if (request()->route()->getName() == "adminUpdateMainAnalys") {
                    if (request()->mainAnalys) {
                    $basicRules['test_code'][1]='unique:sections,test_code,'.request('section_id');
                        $basicRules['once'][0] = 'nullable';

                        $AvilableId = [
                            'section_id' => ['required', function ($attribute, $value, $fail) {
                                if (! Section::where('account_id',null)->where('type',Section::MAINANALYSIS) ->find($value)) {
                                    $fail('The section is invalid.');
                                }
                            }],
                        ];
                        return [...$basicRules, ...$AvilableId, ...$methods, ...$rulesForClassReport];
            } else {
                         $basicRules['test_code'][1]='unique:analyzs,test_code,'.request('analys_id');
                        $basicRules['once'][0] = 'nullable';
                        $AvilableId = [
                            'analys_id' => ['required', function ($attribute, $value, $fail) {
                                if (!Analyz::where('account_id',null)->find($value)) {
                                    $fail('The analys is invalid.');
                                }
                            }],
                        ];
                        return [...$basicRules, ...$AvilableId, ...$methods, ...$rulesForClassReport];
                    }
                }

                if (request()->route()->getName() == 'analyzForSectionAdmin') {

                    return [
                        'section_id' => ['required', function ($attribute, $value, $fail) {

                            if (!Section::AdminMainSection()->find($value)) {
                                $fail('The section is invalid.');
                            }
                        }],
                        'test_code' => 'required|unique:analyzs,test_code',
                        // 'test_code' => 'required',
                        'test_print_name' => 'required|min:2|max:255',
                        'price_for_patient' => 'required|min:1|numeric',
                        'price_for_lap' => 'required|min:1|numeric',
                        'price_for_company' => 'required|min:1|numeric',
                        'antibiotic' => 'required|boolean',
                        'subject' => 'required_if:antibiotic,1',

                        'hsitopology' => [Rule::requiredIf(fn () => !request()->culture_report && !request()->mane_report), 'array'],
                        'hsitopology.*.text' => ['required'],


                        'culture_report' => [Rule::requiredIf(fn () => !request()->hsitopology && !request()->mane_report), 'array'],
                        'culture_report.*.text' => ['required'],

                        'mane_report' => [Rule::requiredIf(fn () => !request()->culture_report && !request()->hsitopology), 'array'],
                        'mane_report.*.normal_range' => 'required',
                        'mane_report.*.gender' => 'required',
                        'mane_report.*.h' => 'required',
                        'mane_report.*.l' => 'required',


                        'test_unit_id' => ['required', function ($attribute, $value, $fail) {


                            if (!TestUnit::whereNull('account_id')->find($value)) {
                                $fail('The test unit is invalid.');
                            }
                        }],
                        'test_method_id' => ['required', function ($attribute, $value, $fail) {


                            if (!TestMethod::whereNull('account_id')->find($value)) {
                                $fail('The test method is invalid.');
                            }
                        }],
                        'tupe_id' => [Rule::requiredIf(fn () => request()->once == 1), 'exists:admin_tupes,id'],

                    ];
                } else {

                    if (request()->once == true){
                        $basicRules['test_code'][1]='unique:sections,test_code';
                        $rulesIfOnceTrue = [...$basicRules, ...$rulesForClassReport, ...$methods];
                        return $rulesIfOnceTrue;
                    }
                else
                    return $rulesIfOnceFalse;

                }
                break;

            case 'PUT':
                return [
                    'name' => 'required|min:2|max:255',
                    'section_id' => 'required|exists:sections,id'
                ];
                break;
        }
    }
}
