<?php

namespace App\Http\Controllers\LabAccount\Settings;

use App\Models\Gender;
use App\Models\Job_title;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\GenderRequest;
use App\Http\Requests\JobTitleRequest;
use App\Http\Resources\GenderResource;

class genderController extends Controller
{


    public function index()
    {
        $Genders = GenderResource::collection(auth('lab')->user()->Genders);
        return response()->json($Genders);
    }


    public function store(GenderRequest $request)
    {
        auth('lab')->user()->Genders()->create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'gender created '], 201);
    }




    public function update(GenderRequest $request)
    {

        Gender::find($request->gender_id)->update([
            'name' => $request->name,
        ]);


        return response()->json(['message' => 'gender updated']);
    }


    public function delete(GenderRequest $request)
    {
        Gender::find($request->gender_id)->delete();
        return response()->json(['message' => 'gender deleted']);
    }
}
