<?php

namespace App\Http\Controllers\LabAccount\Settings;

use App\Models\Gender;
use App\Models\Company;
use App\Models\Job_title;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\GenderRequest;
use App\Http\Requests\ComapnyRequest;
use App\Http\Requests\JobTitleRequest;
use App\Http\Requests\PaymentMethodRequest;
use App\Http\Resources\CompanyResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;

class payemntMethodContoller extends Controller
{


    public function index()
    {
        $payment = PaymentMethodResource::collection(auth('lab')->user()->paymentMethod);
        return response()->json($payment);
    }


    public function store(PaymentMethodRequest $request)
    {
        auth('lab')->user()->paymentMethod()->create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'payment method created '], 201);
    }

    public function update(PaymentMethodRequest $request)
    {

        PaymentMethod::find($request->payment_id)->update([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Payment Method updated '], 200);
    }



    public function delete(PaymentMethodRequest $request)
    {
        PaymentMethod::find($request->payment_id)->delete();
        return response()->json(['message' => 'PaymentMethod deleted']);
    }
}
