<?php

namespace App\Http\Controllers\LabAccount;

use App\Models\Analyz;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Trait\sortingSection;
use App\Http\Controllers\Controller;
use App\Http\Requests\SortingSectionRequest;
use App\Http\Requests\SectionLabRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\MainAnalysResource;
use App\Http\Resources\MainSectionResource;


class SectionController extends Controller
{

    use sortingSection;

    public function moveUp(SortingSectionRequest $request)
    {
        return $this->moveUpItem($request->section_id,'lab');
    }
    public function moveDown(SortingSectionRequest $request)
    {
        return $this->moveDownItem($request->section_id,'lab');
    }
    public function SectionsForPatient()
    {

        $mainSection = MainSectionResource::collection(Section::AccoutMainSection()->withWhereHas('analyz:id,section_id,test_print_name,price_for_patient,price_for_lap,price_for_company')->get());
        $mainAnalys = MainAnalysResource::collection(auth('lab')->user()->Section->where('type', Section::MAINANALYSIS));

        $data = [...$mainAnalys, ...$mainSection];
        return response()->json($data);
    }
    public function sections()
    {


        $mainSection =  MainSectionResource::collection(Section::AccoutMainSection()->get());

        $mainAnalys = MainAnalysResource::collection(auth('lab')->user()->Section->where('type', Section::MAINANALYSIS));

        $data = [...$mainAnalys, ...$mainSection];

        return response()->json($data);
    }


    public function store(SectionLabRequest $request)
    {
        $return = [];

        if ($request->once == 1) {


            $analyz = auth('lab')->user()->Section()->create([
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
                'account_id' => auth('lab')->user()->id,
                'once' => true,
                'role' => Section::ACCOUNT,
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
            }

            return response()->json(['message' => $return]);
        } else {


            auth('lab')->user()->Section()->create([
                'name' => $request->name,
                'role' => Section::ACCOUNT,
                'type' => Section::MAINSECTION,
            ]);
            $return['Section'] = ' Section-Created';

            return $return;
        }
    }

    public function updateMainSection(Request $request)
    {
        $rules = [
            'name' => 'required|min:2|max:255',
            'section_id' => ['required', function ($attribute, $value, $fail) {
                if (!auth('lab')->user()->mainSection->find($value)) {
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
    public function mainSectionId(Request $request)
    {

        $rules = [
            'section_id' => ['required', function ($attribute, $value, $fail) {
                if (!auth('lab')->user()->mainSection->find($value)) {
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

    public function updateMainAnalys(SectionLabRequest $request)
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


    public function deleteMainSection(Request $request)
    {
        $rules = [
            'section_id' => ['required', function ($attribute, $value, $fail) {
                if (!auth('lab')->user()->mainSection->find($value)) {
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
                if (!auth('lab')->user()->mainAnalys->find($value)) {
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
