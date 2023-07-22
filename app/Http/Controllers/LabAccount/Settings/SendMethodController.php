<?php

namespace  App\Http\Controllers\LabAccount\Settings;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendMethodRequest;
use App\Http\Resources\SendMethodResource;
use App\Models\SendMethod;
use Illuminate\Http\Request;

class SendMethodController extends Controller
{

    public function index()
    {
        $sendMethod = SendMethodResource::collection(auth('lab')->user()->sendMethod);
        return response()->json($sendMethod);
    }


    public function store(SendMethodRequest $request)
    {
        auth('lab')->user()->sendMethod()->create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'SendMethod created '], 201);
    }




    public function update(SendMethodRequest $request)
    {

        SendMethod::find($request->send_method_id)->update([
            'name' => $request->name,
        ]);


        return response()->json(['message' => 'SendMethod updated']);
    }


    public function delete(SendMethodRequest $request)
    {
        SendMethod::find($request->send_method_id)->delete();
        return response()->json(['message' => 'SendMethod deleted']);
    }
}
