<?php

namespace App\Http\Controllers\Admin;

use App\Models\Gender;
use App\Models\Section;
use App\Models\TestUnit;
use App\Models\adminTupe;
use App\Models\TestMethod;
use App\Models\AdminSetting;
use Illuminate\Http\Request;
use App\Rules\testMethodRule;
use App\Http\Trait\UploadImages;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\GenderRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SectionRequest;
use App\Http\Resources\GenderResource;
use App\Http\Resources\SectionResource;
use App\Http\Resources\AdminTupeResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\AdminSettingRequest;

class AdminSettingController extends Controller
{

    use UploadImages;

    public function getTupes()
    {
        return response()->json(['data' => AdminTupeResource::collection(auth('admin')->user()->adminTupe)]);
    }

    public function addTypeOfTupe(AdminSettingRequest $request)
    {
        $path=$request->image->store('tupeImages','public');
        auth('admin')->user()->adminTupe()->create(['tupe' => $request->tupe, 'image'=>'public/'.$path]);
        return response()->json(['message' => 'inserted successfully'], 201);
    }

    public function deleteTupe(AdminSettingRequest $request)
    {
        if ($request->ids) {

            auth('admin')->user()->adminTupe->find($request->ids)->each(function ($tupe) {
                $tupe->delete();
            });
            return response()->json(['message' => 'Tupes deleted']);
        } else {

            auth('admin')->user()->adminTupe->find($request->id)->delete();
            return response()->json(['message' => 'Tupe deleted']);
        }
    }

    public function updateTupe(AdminSettingRequest $request)
    {
        $tupe=auth('admin')->user()->adminTupe->find($request->id);
        $tupe->update(['tupe' => $request->tupe]);
        if($request->image){
           return  $this->updateTupeImage($tupe, $request->image);
        }
        return response()->json(['message' => 'tupe updated successfully']);
    }


    ////test methods
    public function testMethods()
    {
        return response()->json(TestMethod::whereNull('account_id')->latest()->get(['id', 'test_method']));
    }

    public function createTestMethod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_method' => 'required|min:3|max:255',
        ]);

        if (!$validator->fails()) {
            TestMethod::create([
                'test_method' => $request->test_method,
            ]);
            return response()->json(['message' => 'test_method created successfully'], 201);
        } else {
            return response()->json($validator->errors(),422);
        }
    }
    public function updateTestMethod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_method' => 'required|min:3|max:255',
            'test_method_id' => ['required', function ($attribute, $value, $fail) {
                if (!TestMethod::whereNull('account_id')->find($value)) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            },],
        ]);

        if (!$validator->fails()) {
            TestMethod::find($request->test_method_id)->update([
                'test_method' => $request->test_method,
            ]);
            return response()->json(['message' => 'testMethod updated '], 200);
        } else {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
    }

    public function deleteTestMethod(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'test_method_id' => ['required', function ($attribute, $value, $fail) {
                if (!TestMethod::whereNull('account_id')->find($value)) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            },],
        ]);

        if (!$validator->fails()) {
            TestMethod::find($request->test_method_id)->delete();
            return response()->json(['message' => 'testMethod deleted '], 200);
        } else {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
    }



    //test units
    public function getTestUnits()
    {
        return response()->json(TestUnit::whereNull('account_id')->latest()->get(['id', 'unit']));
    }


    public function createTestUnit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_unit' => 'required|min:3|max:255',
        ]);

        if (!$validator->fails()) {
            TestUnit::create([
                'unit' => $request->test_unit,
            ]);
            return response()->json(['message' => 'test_unit created successfully'], 201);
        } else {
              return response()->json(['error' => $validator->errors()->all()], 422);
        }
    }
    public function updateTestUnit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_unit' => 'required|min:2|max:255',
            'test_unit_id' => ['required', function ($attribute, $value, $fail) {
                if (!TestUnit::whereNull('account_id')->find($value)) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            },],
        ]);

        if (!$validator->fails()) {
            TestUnit::find($request->test_unit_id)->update([
                'unit' => $request->test_unit,
            ]);
            return response()->json(['message' => 'testMethod updated '], 200);
        } else {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
    }

    public function deleteTestUnit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'test_unit_id' => ['required', function ($attribute, $value, $fail) {
                if (!TestUnit::whereNull('account_id')->find($value)) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            },],
        ]);

        if (!$validator->fails()) {
            TestUnit::find($request->test_unit_id)->delete();
            return response()->json(['message' => 'testUnit deleted '], 200);
        } else {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
    }


    ///

    public function indexGender()
    {
        $Genders = GenderResource::collection(Gender::whereNull('account_id')->latest()->get());
        return response()->json($Genders);
    }


    public function storeGender(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
        ]);
        if (!$validation->fails()) {
            Gender::create([
                'name' => $request->name,
            ]);

            return response()->json(['message' => 'gender created '], 201);
        } else {

            return response()->json(['error' => $validation->errors()->all()], 422);
        }
    }

    public function UpdateGender(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|min:2|max:255',
            'gender_id' => 'required|exists:genders,id',
        ]);
        if (!$validation->fails()) {
            Gender::find($request->gender_id)->update([
                'name' => $request->name,
            ]);
            return response()->json(['message' => 'gender updated '], 200);
        } else {
            return response()->json(['error' => $validation->errors()->all()], 422);
        }
    }


    public function DeleteGender(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'gender_id' => ['required', function ($attr, $value, $fail) {
                if (!Gender::whereNull('account_id')->find($value)) {
                    $fail('The ' . $attr . ' is invalid.');
                }
            }],
        ]);
        if (!$validation->fails()) {
            Gender::find($request->gender_id)->delete();
            return response()->json(['message' => 'gender deleted '], 200);
        } else {
            return response()->json(['error' => $validation->errors()->all()], 422);
        }
    }


    ////////


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
