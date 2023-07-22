<?php

namespace App\Http\Controllers\LabAccount;

use App\Models\Patient;
use App\Models\Section;
use App\Rules\PatientRule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Http\Resources\PatientResource;
use App\Http\Requests\patientValidation;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ReceivedPatientsResource;
use App\Models\Analyz;

class PatientController extends Controller
{

    public function getPrice(Request $request){

        $key=$request->key;
        $getKey='';
        switch($key){
            case 'doctor_id':
                 $getKey= 'price_for_patient';
                break;
                case 'lab_id':

                 $getKey ='price_for_lap';
                break;
                case 'company_id':
                 $getKey='price_for_company';
                break;
        }

        $section=Section::find($request->section_id);
        // return $section;
        if($section->type==Section::MAINANALYSIS){
            // return $getKey;
            return $section->$getKey;
        }else{
            $analys=Analyz::find($request->analys_id);

            return $analys->$getKey;
        }
    }

    public function listPatient()
    {
        return   Patient::whereBelongsTo(auth('lab')->user())
        ->select('id', 'name', 'phone_number', 'price_analysis', 'paid_up', 'duo', 'discount')
        ->get();
    }
    public function storePatient(patientValidation $request)
    {

        $data = [];
        $sectionsWithAnalysis =  Section::whereIn('id', $request->sections_id)->with(['analyz' => function ($q)  use ($request) {
            $q->whereIn('id', $request->analyses_id);
        }])->get()->pluck('analyz');

        for ($index = 0; $index < count($sectionsWithAnalysis); $index++) {

            if (!count($sectionsWithAnalysis[$index])) {

                return  response()->json(
                    ['error' => 'the section doesn`t have any analysis'],
                    422
                );
            }

            foreach ($sectionsWithAnalysis[$index] as  $key => $dataa) {

                $newData = [
                    'section_id' => $dataa->section_id,
                    'analyz_id' => $dataa->id,

                ];
                array_push($data, $newData);
            }
        }

        for ($i = 0; $i < count($data); $i++) {
            $data[$i] += $request->data;
        }

        $sortdata = [];
        if ($request->main_analysis  && count($request->main_analysis)) {
            for ($i = 0; $i < count($request->main_analysis); $i++) {

                array_push($sortdata, ['section_id' =>  $request->main_analysis[$i]]);

                $sortdata[$i] += $request->data;
            }
            array_push($data, ...$sortdata);
        }
        if ($request->patientId) {

            $patient = Patient::find($request->patientId);
            $patient->update([
                "duo" => $request->duo,
                "price_analysis" => $request->price_analysis,
                "paid_up" => $request->paid_up,
                "discount" => $request->discount,
            ]);

            $patient->patieonAnalys()->createMany($data);
            return response()->json(['message' => 'created sucess'], 201);
        } else {

            $createPatient = auth('lab')->user()->patients()->create(
                $request->patient
            );

            $result['patient'] = 'patient created';
            $createPatient->patieonAnalys()->createMany($data);
        }


        $result['patientAnalys'] = 'patieon Analys created';
        return response()->json($result, 201);
    }

    public function update(PatientRequest $request)
    {


        $patient = Patient::find($request->patientId);
        $patient->update([
            'name' => $request->name,
            'age' => $request->age,
            'email' => $request->email,
            'address' => $request->address,
            'phone_number' =>  $request->phone_number,
            'gender_id' => $request->gender_id,
            'date_of_visit' => $request->date_of_visit,
            'receive_of_date' => $request->receive_of_date,
            'price_analysis' => $request->price_analysis,
            'paid_up' => $request->paid_up,
            'duo' => $request->duo,
            'discount' => $request->discount,
        ]);
        if ($request->analysId) {

            $getType = $request->type;

            if ($getType == 'doctor_id') {

                $patient->patieonAnalys->find($request->analysId)->update($request->list_analys[0] + [
                    'lab_id' => null,
                    'price_lab' => null,
                    'company_id' => null,
                    'price_company' => null,
                ]);
                return response()->json(['message' => 'Analys updated successfully']);
            }

            if ($getType == 'company_id') {

                $patient->patieonAnalys->find($request->analysId)->update($request->list_analys[0] + [
                    'lab_id' => null,
                    'price_lab' => null,
                    'doctor_id' => null,
                    'price_doctor' => null,
                    'price_doctor' => null,
                ]);
                return response()->json(['message' => 'Analys updated successfully']);
            }

            if ($getType == 'lab_id') {

                $patient->patieonAnalys->find($request->analysId)->update($request->list_analys[0] + [
                    'company_id' => null,
                    'price_company' => null,
                    'doctor_id' => null,
                    'price_doctor' => null,
                    'price_doctor' => null,
                ]);
                return response()->json(['message' => 'Analys updated successfully']);
            }


            // $patient=Patient::find(request()->patientId);
            $totalPrices = $patient->patieonAnalys->sum('price');
            $patient->update([
                'price_analysis' => $totalPrices,
            ]);
        }
        $totalPrices = $patient->patieonAnalys->sum('price');
        $patient->update([
            'price_analysis' => $totalPrices,
        ]);
        return response()->json(['message' => 'patient updated successfully']);
    }

    public function getPatient(PatientRequest $request)
    {
        return PatientResource::collection(Patient::whereId($request->patientId)->with(['patieonAnalysWithData', 'gender:id,name'])->get());
    }
    public function index()
    {
        return PatientResource::collection(auth('lab')->user()->patientsWithGender);
    }


    public function destroy(Request $request)
    {
        $rules = [
            'patient_ids' => [new PatientRule()],

        ];
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            $ids = explode(',', $request->patient_ids);
       
            return  Patient::whereIn('id', $ids)->delete();
        } else {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
    }
}
