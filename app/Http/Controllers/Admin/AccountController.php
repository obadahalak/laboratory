<?php

namespace App\Http\Controllers\Admin;

use App\Models\Account;
use App\Models\TestUnit;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Trait\UploadImages;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AccountRequest;
use App\Http\Resources\AccountResource;
use Illuminate\Support\Facades\Validator;

// use Illuminate\Support\Facades\Response;

class AccountController extends Controller
{


    public function create(AccountRequest $request)
    {



        $code = Str::random(10);
        $account = Account::create([

            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'lab_name' => $request->lab_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'code'  => $code . '_' . $request->name,

        ]);

        $path = $request->src->store('accountImages', 'public');

        $account->AccountImage()->create([
            'src' => 'public/' . $path,
        ]);

        return response()->json(['message' => 'created successfully'], Response::HTTP_CREATED);
    }

    public function accounts()
    {
        return AccountResource::collection(Account::latest()->paginate(10));
    }
    public function account(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'patient_id' => ['required', 'exists:accounts,id'],
        ]);
        if (!$validator->fails())
            return  new AccountResource(Account::find($request->patient_id));
        else
            return response()->json(['error' => $validator->errors()], 422);
    }

    private function cheackPassword($account, $oldPassword)
    {
        if (Hash::check($oldPassword, $account->password)) return true;
        return false;
    }
    public function update(AccountRequest $request)
    {

        $result = ['account updated successfully'];
        $fails = ['old password not correct'];
        $account = Account::find($request->account_id);
        $updatedProfileAccount = [
            'name' => $request->name,
            'email' => $request->email,
            'lab_name' => $request->lab_name,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        $updatedProfileAccount = array_merge(
            $updatedProfileAccount,
            [
                'password' => $request->new_password
            ]
        );

        $account->update($updatedProfileAccount);
        return response()->json(['message' => $result]);
    }
}
