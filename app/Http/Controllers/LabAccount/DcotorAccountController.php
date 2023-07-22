<?php

namespace App\Http\Controllers\LabAccount;

use App\Models\Doctor;;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Http\Requests\DoctorRequest;
use App\Http\Resources\DoctorResource;
use App\Models\Analyz;

class DcotorAccountController extends Controller
{


    public function reports()
    {
        $data = DB::table('patients as p')
        ->where('p.account_id', auth('lab')->user()->id)
        ->where('doctor_id', '!=', null)
        ->join('patieon_analys as ps', 'ps.patient_id', '=', 'p.id')

        ->leftJoin('sections as s', 's.id', '=', 'ps.section_id')
        ->leftJoin('analyzs as a', 'a.id', '=', 'ps.analyz_id')
        ->selectRaw(
            'ps.id as patient_analys_id ,
             DATE_FORMAT(ps.created_at , "%Y-%m-%d")  as date_of_created ,
              s.test_print_name as section_name ,
              a.test_print_name as analys_name ,
              p.name as patient_name ,
              price_doctor as price_doctor'
        )->get();

        $ammount_doctor = 0;
        foreach ($data as $docotrs) {
            $ammount_doctor += $docotrs->price_doctor;
        }

        $countOfPatients = DB::table('patients as p')->where('p.account_id', auth('lab')->user()->id)->count();
        $countOfExaminations = Section::AccoutMainAnalys()->count() + Analyz::AccountAnalys()->count();
        return response()->json([
            'data'=>$data,
            'ammount'=>$ammount_doctor,
            'countOfPatients'=>$countOfPatients,
            'countOfExaminations'=>$countOfExaminations,

        ]);
    }
    public function storeDoctor(DoctorRequest $request)
    {

        $doctor = auth('lab')->user()->doctors()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'address' => $request->address,
            'ratio' => $request->ratio,
        ]);
        if ($request->src) {

            $path = $request->src->store('doctorImages', 'public');

            $doctor->DoctorImage()->create([
                'src' => 'public/' . $path,
            ]);
        }

        return response()->json(['message' => 'doctor account created successfully']);
    }

    public function Docotors()
    {
        $doctors = DB::table('doctors as d')->where('d.account_id', auth('lab')->user()->id)

            ->join('accounts as a', 'a.id', '=', 'd.account_id')

            ->select(
                'd.id as doctor_id',
                'd.name as doctor_name',
                'd.email as doctor_email',
                'd.phone as doctor_phone',
                'd.address as doctor_address',
                'd.ratio as doctor_ratio',
                'd.gender as doctor_gender',
                'd.created_at as doctor_created_at',
                'd.password as doctor_password',
            )
            ->paginate(10);

        return  DoctorResource::collection($doctors);
    }

   
    public function show($id)
    {
        return  DoctorResource::collection(auth('lab')->user()->doctors->find($id));
    }


    public function update(DoctorRequest $request)
    {
        $doctor = Doctor::find($request->id);
        $doctor->update([
            'name'=>$request->name,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'email'=>$request->email,
            'password'=>$request->password,
            'gender'=>$request->gender,
            'ratio'=>$request->ratio,
        ]);
      

        if (isset($request->src)) {

            $path = $request->src->store('doctorImages', 'public');

            $doctor->DoctorImage()->update([
                'src' => 'public/' . $path,
            ]);
        }

        return response()->json($doctor, 200);
    }

    


    public function destroy(DoctorRequest $request)
    {
        $ids = explode(',', $request->doctor_ids);

        return  Doctor::whereIn('id', $ids)->delete();
    }
}
