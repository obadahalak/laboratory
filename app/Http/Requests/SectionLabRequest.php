<?php

namespace App\Http\Requests;

use App\Models\Section;
use App\Models\TestUnit;
use App\Models\TestMethod;
use App\Rules\mainSectionRule;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\SectionRule;
use Random\Engine\Secure;

class SectionLabRequest extends FormRequest
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
            'section_id' => 'section',
            'test_code' => 'test code',
            'test_print_name' => 'test print name',
            'price_for_patient' => 'price for patient',
            'price_for_lap' => 'price for lap',
            'price_for_company' => 'price for company',
            'test_method_id' => 'test method',
            'test_unit_id' => 'test unit',
            'tupe_id' => 'tupe',
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
            'once' => ['required', 'boolean'],
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
                if (!auth('lab')->user()->Genders->find($value)) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            }],
            'mane_report.*.h' => 'required',
            'mane_report.*.l' => 'required',
        ];

        $methods = [
            'test_unit_id' => ['required', function ($attribute, $value, $fail) {
                if (!auth('lab')->user()->testUnit->find($value)) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            }],
            'test_method_id' => ['required', function ($attribute, $value, $fail) {
                if (!auth('lab')->user()->testMethod->find($value)) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            }],

            'tupe_id' => ['required', 'exists:admin_tupes,id'],
        ];
        $basicRules['test_code'][1] = 'unique:sections,test_code';
        $rulesIfOnceTrue = [...$basicRules, ...$rulesForClassReport, ...$methods];
        $rulesIfOnceFalse = ['name' => 'required|min:2|max:255', 'once' => 'required|boolean'];


        switch ($this->method()) {
            case 'GET':
                return [
                    'section_ids' => [new mainSectionRule()],
                ];
                break;
            case 'POST':

                if (request()->route()->getName() === 'analyzForSection') {
                    $basicRules['test_code'][1] = 'unique:analyzs,test_code';
                    $analyzForSection = [...$methods, ...$rulesForClassReport, ...$basicRules, 'section_id' => ['required', new SectionRule()]];
                    return  $analyzForSection;
                } else {

                    if (request()->once == true)
                        return $rulesIfOnceTrue;
                    else
                        return $rulesIfOnceFalse;
                }
                break;


                if (request()->route()->getName() == "updateMainAnalys") {
                    if (request()->mainAnalys) {
                        $basicRules['test_code'][1] = 'unique:sections,test_code,' . request('section_id');
                        $basicRules['once'][0] = 'nullable';

                        $AvilableId = [
                            'section_id' => ['required', function ($attribute, $value, $fail) {
                                if (!auth('lab')->user()->mainAnalys->find($value)) {
                                    $fail('The ' . $attribute . ' is invalid.');
                                }
                            }],
                        ];
                        return [...$basicRules, ...$AvilableId, ...$methods, ...$rulesForClassReport];
                    } else {
                        $basicRules['test_code'][1] = 'unique:analyzs,test_code,' . request('analys_id');
                        $basicRules['once'][0] = 'nullable';
                        $AvilableId = [
                            'analys_id' => ['required', function ($attribute, $value, $fail) {
                                if (!auth('lab')->user()->analyz->find($value)) {
                                    $fail('The ' . $attribute . ' is invalid.');
                                }
                            }],
                        ];
                        return [...$basicRules, ...$AvilableId, ...$methods, ...$rulesForClassReport];
                    }
                }

            case 'PUT':

                return [
                    'name' => 'required|min:2|max:255',
                    'section_id' => 'required|exists:sections,id'
                ];
                break;

            case 'DELETE':
                return [];
        }
    }
}
