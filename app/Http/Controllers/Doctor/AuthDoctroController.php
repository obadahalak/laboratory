<?php

namespace App\Http\Controllers\Doctor;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\PatieonAnalys;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\DoctorRequest;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\myPatientResource;
use Illuminate\Validation\ValidationException;

class AuthDoctroController extends Controller
{

    public function index()
    {
       return myPatientResource::collection( PatieonAnalys::where('doctor_id',auth('doctor')->user()->id)->with(['patient','section','analyz'])->paginate(10));

    }

    public function doctorAuth(Request $request)
    {

        $doctor = Doctor::where('email', $request->email)->first();



        if (!$doctor || !Hash::check($request->password, $doctor->password)) {
            throw ValidationException::withMessages([
                'email' => ['email or password is incorrect'],
            ]);
        } else {
            $token = $doctor->createToken($request->email);
            return response()->json(['token' => $token->accessToken]);
        }
    }

    public function doctorProfile()
    {
        $data=DB::table('doctors')
        ->select('name','email','gender','phone','address')
        ->whereId(auth('doctor')->user()->id)
        ->first();
        return response()->json($data);
    }

    public function updatePassword(DoctorRequest $request)
    {
        auth('doctor')->user()->update([
            'password' => $request->password,
        ]);
        return response()->json(['message' => 'Password updated']);
    }
}
