<?php

namespace App\Http\Controllers;

use App\Models\Analyz;
use App\Models\Section;
use App\Models\TestUnit;
use App\Models\Antibiotic;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SectionRequest;
use App\Http\Resources\SectionResource;
use App\Http\Requests\SectionLabRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\sectionAnalysRequest;
use App\Http\Resources\AnalysisNameResource;
use App\Http\Resources\MainAnalysResource;
use App\Http\Resources\MainSectionResource;
use App\Http\Resources\AnalysResource;
use App\Http\Resources\getPatinetAnalysisResource;
use App\Models\Doctor;
use App\Models\Lab;

class AnalyzController extends Controller
{



    public function anlalysId(Request $request)
    {

        $rules = [
            'analys_id' => ['required', function ($attribute, $value, $fail) {
                if (!auth('lab')->user()->analyz->find($value)) {
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

    public function patientAnalysis(Request $request)
    {

        $data = DB::table('patients as p')->where('p.id', $request->id)
            ->join('patieon_analys as ps', 'ps.patient_id', '=', 'p.id')

            ->leftJoin('sections as s', 's.id', '=', 'ps.section_id')
            ->leftJoin('analyzs as a', 'a.id', '=', 'ps.analyz_id')
            ->leftJoin('admin_tupes as t','t.id','s.admin_tupe_id')
            ->leftJoin('admin_tupes as ta','t.id','a.admin_tupe_id')
            ->select(
                'ps.id as patient_analalys_id',
                'p.id as patient_id',
                'p.name as patient_name',
                's.id as section_id',
                'a.id as anlalys_id',
                's.name as section_name',
                's.test_print_name as test_print_name',
                'a.test_print_name as analys_name',
                't.image as section_tupe',
                'ta.image as analys_tupe'

            )->get();
        return getPatinetAnalysisResource::collection($data);

    }
    public function get_antibiotic_from_section(Request $request)
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


    public function getAnalysis(SectionLabRequest $request)
    {
        $ids = explode(',', $request->section_ids);

        return AnalysisNameResource::collection(auth('lab')->user()->analyz->whereIn('section_id',$ids));
    }


    public function analyzForSection(SectionLabRequest $request)
    {

        $analyz = auth('lab')->user()->mainSection
                ->find($request->section_id)
                ->analyz()
                ->create([
           
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
                    
                        'account_id' => auth('lab')->user()->id,

        ]);
        if ($request->antibiotic == 1) {
            foreach ($request->subject as $subject) {

                $analyz->antibiotic()->create([
                    'subject' => $subject,
                ]);
            }
            return response()->json(['message' => 'add analys successfully _ '], 201);
        }
        return response()->json(['message' => 'add analys successfully  '], 201);
    }



    public function mainAnalysId(Request $request)
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

            return new  AnalysResource(Section::find($request->section_id));
        } else {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
    }


    public function deleteAnalys(Request $request)
    {

        $rules = [
            'analys_id' => ['required', function ($attribute, $value, $fail) {
                if (!auth('lab')->user()->analyz->find($value)) {
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

    ////////////////////
    public function sectionsWithAnalysisFilter()
    {

        $data = DB::table('analyzs as a')

            ->where('a.account_id', auth('lab')->user()->id)
            ->join('sections as s', 's.id', '=', 'a.section_id')
            ->selectRaw(
                'a.id as analys_id,
                s.name as section_name,
                a.test_print_name as analysis_name,
                DATE_FORMAT(a.created_at,"%Y-%m-%d") as datetime,
                a.price_for_patient,
                a.price_for_lap,
                a.price_for_company'
            )
            ->get();
        $countOfAnalysis = DB::table('analyzs')->where('account_id', auth('lab')->user()->id)->count();
        $countOfSetions = DB::table('sections')->where('account_id', auth('lab')->user()->id)
            ->where('type', Section::MAINSECTION)->count();

        return response()->json([
            'data' => $data,
            'countOfSections' => $countOfSetions,
            'countOfAnalysis' => $countOfAnalysis
        ]);

    }

    public function patientAnalysisFilter()
    {
        $data = DB::table('patients as p')->where('p.account_id', auth('lab')->user()->id)

            ->join('patieon_analys as ps', 'ps.patient_id', '=', 'p.id')

            ->leftJoin('doctors as d', 'd.id', '=', 'ps.doctor_id')
            ->leftJoin('labs as l', 'l.id', '=', 'ps.lab_id')
            ->leftJoin('companies as c', 'c.id', '=', 'ps.company_id')

            ->leftJoin('sections as s', 's.id', '=', 'ps.section_id')
            ->leftJoin('analyzs as a', 'a.id', '=', 'ps.analyz_id')


            ->select(
                'ps.id as patient_analalys_id',
                'p.id as patient_id',
                'p.name as patient_name',

                's.test_print_name as main_test_print_name',

                'a.test_print_name as analys_name',

                'd.name as doctor_name',
                'c.name as company_name',
                'l.lab_name as lab_name',


            )->get();
            
            $doctors=Doctor::where('account_id',auth('lab')->user()->id)->count();
            
            $labs=Lab::where('account_id',auth('lab')->user()->id)->count();
            
            $countOfPatients = DB::table('patients as p')->where('p.account_id', auth('lab')->user()->id)->count();
            
            $countOfExaminations = Section::AccoutMainAnalys()->count() + Analyz::AccountAnalys()->count();

                return response()->json([
                    'data'=>$data,
                    'labs'=>$labs,
                    'doctors'=>$doctors,
                    'countOfExaminations'=>$countOfExaminations,
                    'countOfPatients'=>$countOfPatients,
                ]);
    }
}
