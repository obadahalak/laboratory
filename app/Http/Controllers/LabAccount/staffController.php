<?php

namespace App\Http\Controllers\LabAccount;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaffRequest;
use App\Http\Requests\StaffUpdateRequest;
use App\Models\Staff;
use Illuminate\Http\Request;

class staffController extends Controller
{
    
    public function index()
    {
        $staff = Staff::with(['job' , 'spec'])->where('account_id' , auth('lab')->user()->id)->orderBy('id', 'desc')->paginate(10);
        return response()->json($staff);
    }

   
    public function store(StaffRequest $request)
    {
        $input = $request->validated();

        $input['account_id'] = auth('lab')->user()->id;
        if ($request->image)
        {
            $input['image'] = $request->image->store($request->name . '_staff' , 'public');

        }
        $staff = Staff::create($input);

        return response()->json($staff);
    }

   
    public function show($id)
    {
        $staff = Staff::with(['job' , 'spec'])->where('id' , $id)->first();

        return response()->json($staff);
    }

    public function update(StaffUpdateRequest $request, $stuffId)
    {
        $staff = Staff::find( $stuffId);
        $staff->update([
            'name'=>$request->name,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'email'=>$request->email ,
            'DOB'=>$request->DOB,
            'work_start'=>$request->work_start,
            'experiance'=>$request->experiance,
            'note'=>$request->note   ,
            'collage'=>$request->collage,
            'salary'=>$request->salary ,
            'work_time_start'=>$request->work_time_start  ,
            'work_time_end'=>$request->work_time_end    ,
            'specialization_id'=>$request->specialization_id,
            'job_title_id'=>$request->job_title_id     ,
        ]);
       

        if(isset($request->image))
        {
            $input['image'] = $request->image->store($request->name . '_staff' , 'public');
        }

       

        return response()->json($staff , 200);
    }

   
    public function destroy($id)
    {
        $staff = Staff::find($id);
        if($staff){

            $staff->delete();
            return response()->json(['message'=>'deleted']);
        }
        
        return response()->json(['message'=>'staff id invalid'],422);

    }
}
