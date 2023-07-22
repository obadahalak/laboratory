<?php

namespace App\Http\Controllers\LabAccount;

use App\Models\Account;
use App\Models\Patient;
use App\Models\SendPatient;
use Illuminate\Http\Request;
use App\Models\PatieonAnalys;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Notifications\Action;
use App\Http\Resources\PatientResource;
use App\Http\Requests\SendPetientRequest;
use App\Http\Resources\IndexPatientResource;
use App\Http\Resources\RecivedPatientRecource;
use App\Http\Resources\ReceivedPatientsResource;
use App\Http\Resources\getSendingPateintResource;

class sendPatientController extends Controller
{


    public function getRecivedAnalys(Request $request){
           $patient=Patient::with('gender:id,name')->find($request->patientId);
       $data=RecivedPatientRecource::collection(SendPatient::where('recived_id',auth('lab')->user()->id)->where('patient_id',$request->patientId)->with(['patieonAnalys'])->get()->pluck('patieonAnalys'));
        return response()->json(['patient'=>$patient,'data'=>$data]);
    }
    public function get_analysis_pateint()
    {
        $data = PatieonAnalys::with('patient', 'section', 'analyz')->wherehas('patient')->paginate(10);
        return response()->json($data);
    }

    public function allAccounts()
    {
        return  Account::select('id', 'name')->latest()->get()->except(auth('lab')->user()->id);
    }


    public function sendPetient(SendPetientRequest $request)
    {
        $key = $request->send_id;

        auth('lab')->user()->SendPatient()->create([
            'patient_id' => $request->patient_id,
            $key => $request->lab_id ?? $request->recived_id,
            'patieon_analys_id' => $request->patieon_analys_id,
            'shipping_cost' => $request->shipping_cost,
            'total_amount' => $request->total_amount
        ]);
        return response()->json(['message' => 'send Patient successfully'], 201);
    }


    public function getPatientData()
    {
        return PatientResource::collection(Patient::where('account_id', auth('lab')->user()->id)->with(['patieonAnalysWithData', 'gender'])->paginate(10));
    }
    public function indexPatient()
    {

        $data =  DB::table('patients as p')->where('p.account_id', auth('lab')->user()->id)
            ->join('genders as g', 'g.id', '=', 'p.gender_id')
            ->join('patieon_analys as ps', 'ps.patient_id', '=', 'p.id')
            ->leftJoin('doctors as d', 'd.id', '=', 'ps.doctor_id')
            ->leftJoin('labs as l', 'l.id', '=', 'ps.lab_id')
            ->leftJoin('companies as c', 'c.id', '=', 'ps.company_id')
            ->select(

                'p.id as patient_id',
                'p.name as patient_name',
                'p.email as patient_email',
                'p.date_of_visit as patient_date_of_visit',
                'p.phone_number as patient_phone_number',
                'p.age as patient_age',
                'g.name as patient_gender',
                'd.name as doctor_name',
                'c.name as company_name',
                'l.lab_name as lab_name',

            )->groupBy(['ps.patient_id'])->orderBy('p.id', 'desc')->paginate(10);

        return IndexPatientResource::collection($data);
    }

    public function patientsSendingAndResived(Request $request)
    {

        $auth_id=auth()->user('lab')->id;
        
        $data = DB::table('send_patients as s_p')
            ->where('s_p.account_id', $auth_id)
            ->Orwhere('s_p.recived_id', $auth_id)

            ->join('patieon_analys  as pa_an', 's_p.patieon_analys_id', '=', 'pa_an.id')
            ->join('patients  as pa', 'pa_an.patient_id', '=', 'pa.id')

            ->join('genders  as g_patient', 'g_patient.id', '=', 'pa.gender_id')
            ->leftJoin('doctors as d', 'd.id', '=', 'pa_an.doctor_id')
            ->leftJoin('labs as l', 'l.id', '=', 'pa_an.lab_id')
            ->leftJoin('companies as c', 'c.id', '=', 'pa_an.company_id')
            ->select(
                's_p.id as send_patients_id',
                's_p.account_id as account_id',
                's_p.recived_id as recived_id',

                'pa_an.id as patient_analysis_id',
                'pa_an.isComplete as isComplete',
                'pa.id as patient_id',
                'pa.name as patient_name',
                'pa.email as patient_email',
                'pa.date_of_visit as patient_date_of_visit',
                'pa.phone_number as patitne_phone_number',
                'g_patient.name as gender_patient_name',
                'pa.age as patient_age',

                'd.name as doctor_name',
                'c.name as company_name',
                'l.lab_name as lab_name',

            )
            ->paginate(10);
        return  getSendingPateintResource::collection($data);
    }
}
