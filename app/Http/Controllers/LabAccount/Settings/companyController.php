<?php

namespace App\Http\Controllers\LabAccount\Settings;

use App\Models\Gender;
use App\Models\Job_title;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\GenderRequest;
use App\Http\Requests\ComapnyRequest;
use App\Http\Requests\JobTitleRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;

class companyController extends Controller
{


    public function index()
    {
        $companies = CompanyResource::collection(auth('lab')->user()->companies);
        return response()->json($companies);
    }


    public function store(ComapnyRequest $request)
    {
        auth('lab')->user()->companies()->create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'company created '], 201);
    }

    public function update(ComapnyRequest $request)
    {

        Company::find($request->company_id)->update([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'company updated '], 200);
    }



    public function delete(ComapnyRequest $request)
    {
        Company::find($request->company_id)->delete();
        return response()->json(['message' => 'company deleted']);
    }
}
