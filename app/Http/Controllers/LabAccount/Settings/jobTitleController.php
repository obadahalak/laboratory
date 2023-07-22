<?php

namespace App\Http\Controllers\LabAccount\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobTitleRequest;
use App\Models\Job_title;
use Illuminate\Http\Request;

class jobTitleController extends Controller
{
   
    public function index()
    {
        $jobs = auth('lab')->user()->Jobtitle;
        return response()->json($jobs);
    }

  
    public function store(JobTitleRequest $request)
    {
        $jobs = auth('lab')->user()->Jobtitle()->create($request->validated());

        return response()->json($jobs,201);
    }

    public function show($id)
    {
        $jobs = auth('lab')->user()->Jobtitle->find($id);
        return response()->json($jobs);
    }

   
    public function update(Request $request, $id)
    {
        $jobs = Job_title::where('id' , $id)->first();
        $input['name'] = $request->name ?? $jobs->name;

        $data =  $jobs->update($input);

        return response()->json($jobs);
    }

  
    public function destroy($id)
    {
        $jobs = Job_title::find($id);
        if($jobs){

            $jobs->delete();
            
            return response()->json('deleted' , 200);
        }
        
        return response()->json(['error'=>'Job_title id invalid'] , 422);
    }
}
