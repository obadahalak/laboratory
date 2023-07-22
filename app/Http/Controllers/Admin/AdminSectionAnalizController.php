<?php

namespace App\Http\Controllers\Admin;

use App\Models\Analyz;
use App\Models\Section;
use App\Models\TestUnit;
use App\Models\adminTupe;
use Random\Engine\Secure;
use App\Models\Antibiotic;
use App\Models\TestMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Trait\sortingSection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SectionRequest;
use App\Http\Resources\AnalysResource;
use App\Http\Resources\SectionResource;
use App\Http\Resources\AdminTupeResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\AdminSettingRequest;
use App\Http\Resources\MainAnalysResource;

use App\Http\Resources\MainSectionResource;
use App\Http\Requests\SortingSectionRequest;
use App\Http\Resources\AnalysisNameResource;

class AdminSectionAnalizController extends Controller
{


    use sortingSection;

    public function moveUp(SortingSectionRequest $request)
    {

        return $this->moveUpItem($request->section_id,'admin');
    }
    public function moveDown(SortingSectionRequest $request)
    {
        return $this->moveDownItem($request->section_id,null);
    }
    public function sections()
    {
        $mainSection = MainSectionResource::collection(Section::AdminMainSection()->latest()->get());

        $mainAanlys = MainAnalysResource::collection(Section::with(['adminTupe', 'testUnit', 'testMethod'])->AdminMAINANALYSIS()->latest()->get());

        $data = [...$mainAanlys, ...$mainSection];

        return response()->json($data);
    }

    public function getAnalys(SectionRequest $request)
    {
        return AnalysisNameResource::collection(Analyz::where('section_id', $request->section_id)->get());
    }

    public function createSections(SectionRequest $request)
    {

        $return = [];


        if ($request->once == 1) {


            $analyz = Section::create([
                'test_code' => $request->test_code,
                'test_print_name' => $request->test_print_name,
                'price_for_patient' => $request->price_for_patient,
                'price_for_lap' => $request->price_for_lap,
                'price_for_company' => $request->price_for_company,
                'test_method_id' => $request->test_method_id,
                'test_unit_id' => $request->test_unit_id,
                'admin_tupe_id' => $request->tupe_id,
                'class_report' => $request->hsitopology != null  ? ['hsitopology' => $request->hsitopology]  : ($request->mane_report != null ? ['mane_report' => $request->mane_report] : ['culture_report' => $request->culture_report]),
                'antibiotic' => $request->antibiotic,
                'once' => true,
                'role' => Section::ADMIN,
                'type' => Section::MAINANALYSIS,
            ]);

            $return['Analytic'] = 'Analytic-Created';
            if ($request->antibiotic == 1) {
                foreach ($request->subject as $subject) {

                    $analyz->antibiotic()->create([
                        'subject' => $subject,
                    ]);
                }
                $return['antibiotic'] = 'antibiotic-Created';
            } else
                return response()->json(['message' => $return]);
        } else {

            Section::create([
                'name' => $request->name,
                'role' => Section::ADMIN,
                'type' => Section::MAINSECTION,
            ]);
            $return['Section'] = ' Section-Created';

            return $return;
        }
        return response()->json(['message' => $return]);
    }



    public function anlalysId(Request $request)
    {

        $rules = [
            'analys_id' => ['required', function ($attribute, $value, $fail) {
                if (!Analyz::where('account_id', null)->find($value)) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            }],
        ];
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            return new  AnalysResource(Analyz::find($request->analys_id));
        } else {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
    }




    public function mainSectionId(Request $request)
    {

        $rules = [
            'section_id' => ['required', function ($attribute, $value, $fail) {
                if (!Section::where('account_id', null)->where('type', Section::MAINSECTION)->find($value)) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            }],
        ];
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            return   new MainSectionResource(Section::find($request->section_id));
        } else {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
    }

    public function mainAnalysId(Request $request)
    {


        $rules = [
            'section_id' => ['required', function ($attribute, $value, $fail) {
                if (!Section::where('account_id', null)->where('type', Section::MAINANALYSIS)->find($value)) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            }],
        ];
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            return new  AnalysResource(Section::find($request->section_id));
        } else {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
    }

    public function updateMainSection(Request $request)
    {
        $rules = [
            'name' => 'required|min:2|max:255',
            'section_id' => ['required', function ($attribute, $value, $fail) {
                if (!Section::where('role', Section::ADMIN)->find($value)) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            },],
        ];
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            Section::find($request->section_id)->update([
                'name' => $request->name,
            ]);
            return response()->json(['message' => 'updated successfully']);
        } else {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
    }
    public function analyzForSection(SectionRequest $request)
    {

        $section = Section::AdminMainSection()->find($request->section_id);

        $analyz = $section->analyz()->create([
            'test_code' => $request->test_code,
            'test_print_name' => $request->test_print_name,
            'price_for_patient' => $request->price_for_patient,
            'price_for_lap' => $request->price_for_lap,
            'price_for_company' => $request->price_for_company,
            'test_method_id' => $request->test_method_id,
            'test_unit_id' => $request->test_unit_id,
            'admin_tupe_id' => $request->tupe_id,
            'class_report' => $request->hsitopology != null  ? ['hsitopology' => $request->hsitopology]  : ($request->mane_report != null ? ['mane_report' => $request->mane_report] : ['culture_report' => $request->culture_report]),
            'once' => true,
            'antibiotic' => $request->antibiotic,
        ]);
        if ($request->antibiotic) {
            foreach ($request->subject as $subject) {

                $analyz->antibiotic()->create([
                    'subject' => $subject,
                ]);
            }
            $return['antibiotic'] = 'antibiotic-Created';
        }
        return response()->json(['message' => 'add analys successfully '], 201);
    }


    public function updateMainAnalys(SectionRequest $request)
    {
        if ($request->mainAnalys) {


            $section = Section::find($request->section_id);
            $section->update([
                'test_code' => $request->test_code,
                'test_print_name' => $request->test_print_name,
                'price_for_patient' => $request->price_for_patient,
                'price_for_lap' => $request->price_for_lap,
                'price_for_company' => $request->price_for_company,
                'test_method_id' => $request->test_method_id,
                'test_unit_id' => $request->test_unit_id,
                'admin_tupe_id' => $request->tupe_id,
                'class_report' => $request->hsitopology != null  ? ['hsitopology' => $request->hsitopology]  : ($request->mane_report != null ? ['mane_report' => $request->mane_report] : ['culture_report' => $request->culture_report]),
                'antibiotic' => $request->antibiotic,
            ]);
            $return['section'] = 'section-updated';
            if ($request->antibiotic == 1) {
                foreach ($request->subject as $subject) {

                    $section->antibiotic()->create([
                        'subject' => $subject,
                    ]);
                }
                $return['antibiotic'] = 'antibiotic-Created';
            }
            return $return;
        } else {

            $section = Analyz::find($request->analys_id);
            $section->update([
                'test_code' => $request->test_code,
                'test_print_name' => $request->test_print_name,
                'price_for_patient' => $request->price_for_patient,
                'price_for_lap' => $request->price_for_lap,
                'price_for_company' => $request->price_for_company,
                'test_method_id' => $request->test_method_id,
                'test_unit_id' => $request->test_unit_id,
                'admin_tupe_id' => $request->tupe_id,
                'class_report' => $request->hsitopology != null  ? ['hsitopology' => $request->hsitopology]  : ($request->mane_report != null ? ['mane_report' => $request->mane_report] : ['culture_report' => $request->culture_report]),
                'antibiotic' => $request->antibiotic,
            ]);
            $return['section'] = 'section-updated';
            if ($request->antibiotic == 1) {
                foreach ($request->subject as $subject) {

                    $section->antibiotic()->create([
                        'subject' => $subject,
                    ]);
                }
                $return['antibiotic'] = 'antibiotic-Created';
            }
            return $return;
        }
    }

    public function createIntiAnalys(Request $request)
    {

        $rules = [
            'subject' => 'required',
            'section_id' => ['required', function ($attribute, $value, $fail) {
                if (!Analyz::find($value)) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            }],
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            Antibiotic::create([
                'subject' => $request->subject,
                'analyz_id' => $request->section_id,
            ]);
            return response()->json(['message' => 'sucess'], 201);
        } else {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
    }

    public function createInti(Request $request)
    {

        $rules = [
            'subject' => 'required',
            'section_id' => ['required', function ($attribute, $value, $fail) {
                if (!Section::find($value)) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            }],
        ];
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            Antibiotic::create([
                'subject' => $request->subject,
                'section_id' => $request->section_id,
            ]);
            return response()->json(['message' => 'sucess'], 201);
        } else {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
    }


    public function get_inti_from_section(Request $request)
    {

        if ($request->key == 'section_id') {
            $data = Section::with('antibiotic')->where('id', $request->id)->first();

            return response()->json($data);
        }
        if ($request->key == 'analysis_id') {
            $data = Analyz::with('antibiotic')->where('id', $request->id)->first();
            return response()->json($data);
        }
    }


    public function deleteAnalys(Request $request)
    {
        $rules = [
            'analys_id' => ['required', function ($attribute, $value, $fail) {
                if (!Analyz::where('account_id', null)->find($value)) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            }],
        ];
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            Analyz::find($request->analys_id)->delete();
            return response()->json(['message' => 'deleted success']);
        } else {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
    }



    public function deleteMainSection(Request $request)
    {
        $rules = [
            'section_id' => ['required', function ($attribute, $value, $fail) {
                if (!Section::AdminMainSection()->find($value)) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            }],
        ];
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            Section::find($request->section_id)->delete();
            return response()->json(['message' => 'deleted successfully']);
        } else {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
    }

    public function deleteMainAnalys(Request $request)
    {
        $rules = [
            'section_id' => ['required', function ($attribute, $value, $fail) {
                if (!Section::AdminMAINANALYSIS()->find($value)) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            }],
        ];
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            Section::find($request->section_id)->delete();
            return response()->json(['message' => 'deleted successfully']);
        } else {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
    }
}
