<?php

namespace App\Http\Controllers\LabAccount\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\specializationRequest;
use App\Models\Job_title;
use App\Models\Specialization;
use Illuminate\Http\Request;

class specializationController extends Controller
{
   
    public function index()
    {
        $spec =auth('lab')->user()->specialization;
        return response()->json($spec);
    }

   
  
    public function store(specializationRequest $request)
    {
        $spec = auth('lab')->user()->specialization()->create($request->validated());

        return response()->json($spec,201);
    }

  
    public function show($id)
    {
        $spec = auth('lab')->user()->specialization->find($id);

        return response()->json($spec);
    }

  
    public function update(Request $request, $id)
    {
        $spec = Specialization::where('id' , $id)->first();
        $input['name'] = $request->name ?? $spec->name;

        $data =  $spec->update($input);

        return response()->json($spec);
    }

    public function destroy($id)
    {
        $specialization = Specialization::find( $id);
        if($specialization){

            $specialization->delete();
            
            return response()->json('deleted' , 200);
        }
        return response()->json(['error'=>'Specialization id invalid'] , 422);


    }
}
