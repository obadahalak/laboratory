<?php

namespace App\Http\Controllers;

use App\Models\Analyz;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Section;
use App\Models\Staff;
use App\Models\Store;
use Illuminate\Http\Request;

class homeController extends Controller
{
    public function numbers()
    {
        $auth = auth('lab')->user()->id;
        $doctors = Doctor::where('account_id', $auth)->count();
        $staff = Staff::where('account_id', $auth)->count();
        $pateint = Patient::where('account_id', $auth)->count();
        $section_number = Section::where('account_id', $auth)
            ->where('once', 1)
            ->count();
        $analysis_number = Analyz::where('account_id', $auth)->count();

        $total_number_of_analysis = $section_number + $analysis_number;

        $today_section = Section::where('account_id', $auth)
            ->where('once', 1)
            ->whereDay('created_at', now()->day)->count();
        $today_analysis = Analyz::where('account_id', $auth)
            ->whereDay('created_at', now()->day)->count();

        $total_today = $today_section + $today_analysis;

        $last_ten_petient = Patient::where('account_id', $auth)->latest()->take(10)->get();
        $my_doctors = Doctor::where('account_id', $auth)->take(5)->get();

        $my_store = Store::with('test_unit')->where('account_id', $auth)->take(10)->get();

        $response = [
            'message'  => 'done',
            'doctors'  => $doctors,
            'staff'   => $staff,
            'pateint'   => $pateint,
            'total_number_of_analysis'  => $total_number_of_analysis,
            'total_today'   => $total_today,
            'last_ten_petient' => $last_ten_petient,
            'myDoctors'     => $my_doctors,
            'my_store'     => $my_store,
        ];
        return response()->json($response);
    }
}
