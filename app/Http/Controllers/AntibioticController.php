<?php

namespace App\Http\Controllers;

use App\Models\Analyz;
use App\Models\Section;
use App\Models\Antibiotic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AntibioticController extends Controller
{
    public function update(Request $request)
    {
        $rules = [
            'subject' => 'required|min:2|max:255',
            'intrputik_id' => ['required', function ($attribute, $value, $fail) {
                if (!Antibiotic::find($value)) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            }],
        ];
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            Antibiotic::find($request->intrputik_id)->update([
                'subject' => $request->subject,
            ]);
            return response()->json(['message' => 'updated successfully']);
        } else {
            return response()->json(['error' => $validator->errors()->all()],422);
        }
    }

    public function destroy(Request $request)
    {

        $rules = [

            'intrputik_id' => ['required', function ($attribute, $value, $fail) {
                if (!Antibiotic::find($value)) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            }],
        ];
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            Antibiotic::find($request->intrputik_id)->delete();
            return response()->json(['message' => 'deleted successfully']);
        } else {
            return response()->json(['error' => $validator->errors()->all()],422);
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
            return response()->json(['error' => $validator->errors()->all()],422);
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
            return response()->json(['error' => $validator->errors()->all()],422);
        }
    }
}
