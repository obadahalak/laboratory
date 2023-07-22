<?php

namespace App\Http\Controllers\LabAccount;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResultRequest;
use App\Http\Resources\ResultResource;
use App\Models\Patient;
use App\Models\PatieonAnalys;
use App\Models\Result;
use Illuminate\Http\Request;

class resultController extends Controller
{
    public function get_date(Request $request)
    {

        $result = Result::where('patient_id', $request->id)->exists();
        if (!$result) {
            $data = PatieonAnalys::where('patient_id', $request->id)->with('section', function ($q) {
                $q->where('once', 1);
            })
                ->with('analyz')

                ->get();

            return response()->json(ResultResource::collection($data));
        } else {
            $result_data = Result::where('patient_id', $request->id)->get();
            return response()->json($result_data);
        }
    }

    public function addResult(ResultRequest $request)
    {

        $result = Result::where('patient_id', $request->patient_id)->exists();



        if (!$result) {

            $input['patient_id'] = $request->patient_id;
            $input['histo_result'] = $request->histo_result;
            $input['main_report_result'] = $request->main_report_result;
            $input['culture_report_result'] = $request->culture_report_result;
            $input['anti_result'] = $request->anti_result;

            if ($request->isComplete) {
                $analysis = PatieonAnalys::find($request->petient_analysis_id);
                $analysis->update([
                    'isComplete' => 1,
                ]);
            }
            $data = Result::create($input);
            return response()->json('created...', 201);
        } else {
            $result_data = Result::where('patient_id', $request->patient_id)->first();

            $input['patient_id'] = $request->patient_id;
            $input['histo_result'] = $request->histo_result;
            $input['main_report_result'] = $request->main_report_result;
            $input['culture_report_result'] = $request->culture_report_result;
            $input['anti_result'] = $request->anti_result;
            if ($request->isComplete) {
                $analysis = PatieonAnalys::find($request->petient_analysis_id);
                $analysis->update([
                    'isComplete' => 1,
                ]);
            }
            $result_data->update($input);
            return response()->json('updated...', 201);
        }
    }

}
