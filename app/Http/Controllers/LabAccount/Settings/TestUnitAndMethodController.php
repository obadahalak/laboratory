<?php

namespace  App\Http\Controllers\LabAccount\Settings;

use App\Models\TestUnit;
use App\Models\adminTupe;
use App\Models\SendMethod;
use App\Models\TestMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\testUnitRequest;
use App\Http\Requests\SendMethodRequest;
use App\Http\Requests\testMethodRequest;
use App\Http\Resources\AdminTupeResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\SendMethodResource;

class TestUnitAndMethodController extends Controller

{


    //test units
    public function getTestUnits()
    {
        return response()->json(auth('lab')->user()->testUnit);
    }


    public function createTestUnit(testUnitRequest $request)
    {

        auth('lab')->user()->testUnit()->create([
            'unit' => $request->test_unit,
        ]);
        return response()->json(['message' => 'test_unit created successfully'], 201);
    }
    public function updateTestUnit(testUnitRequest $request)
    {

        TestUnit::find($request->test_unit_id)->update([
            'unit' => $request->test_unit,
        ]);
        return response()->json(['message' => 'testUnit updated '], 200);
    }

    public function deleteTestUnit(testUnitRequest $request)
    {

        TestUnit::find($request->test_unit_id)->delete();
        return response()->json(['message' => 'testUnit deleted '], 200);
    }


    ///test Methods


    ////test methods
    public function testMethods()
    {
        return response()->json(auth('lab')->user()->testMethod);
    }

    public function createTestMethod(testMethodRequest $request)
    {

        auth('lab')->user()->testMethod()->create([
            'test_method' => $request->test_method,
        ]);
        return response()->json(['message' => 'test_method created successfully'], 201);
    }
    public function updateTestMethod(testMethodRequest $request)
    {

        TestMethod::find($request->test_method_id)->update([
            'test_method' => $request->test_method,
        ]);
        return response()->json(['message' => 'testMethod updated '], 200);
    }

    public function deleteTestMethod(testMethodRequest $request)
    {

        TestMethod::find($request->test_method_id)->delete();
        return response()->json(['message' => 'testMethod deleted '], 200);
    }

    public function getTupes()
    {

        return response()->json(['data' => AdminTupeResource::collection(adminTupe::latest()->get())]);
    }
}
