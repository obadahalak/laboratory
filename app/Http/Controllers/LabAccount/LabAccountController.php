<?php

namespace App\Http\Controllers\LabAccount;

use App\Models\Account;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Trait\UploadImages;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LabAccountRequest;

use App\Http\Requests\PriceStatusRequest;
use App\Http\Resources\LabProfileResource;
use Illuminate\Validation\ValidationException;

class LabAccountController extends Controller
{
    use UploadImages;
    public function accountAuth(Request $request)
    {

        $account = Account::where('email', $request->email)->first();

        if (!$account) {
            throw ValidationException::withMessages([
                'email' => ['email is incorrect'],
            ]);
        }

        if (!Hash::check($request->password, $account->password)) {
            throw ValidationException::withMessages([
                'email' => ['password is incorrect'],
            ]);
        } else {
            $token = $account->createToken($request->email);
            return response()->json(['token' => $token->accessToken]);
        }
    }

    public function account()
    {
        return new LabProfileResource(auth('lab')->user());
    }

    public function updateLab(LabAccountRequest $request)
    {

        $authLab = auth('lab')->user();

        if ($request->src) {
            return $this->UpdateAccountImage($authLab, $request->src);
        }

        $authLab->update([
            'lab_name' => $request->lab_name,
            'phone' => $request->phone,

        ]);
        return response()->json(['message' => 'updated profile  successfully']);
    }

    public function updatePriceStatus(PriceStatusRequest $request){
        auth('lab')->user()->update([
            'price_status'=>$request->status,
        ]);
        return response()->json(['message' => 'updated price status successfully']);
    }
    public function priceStatus(){
       return  auth('lab')->user()->price_status==1 ? 1 : 0;
    }
    public function logout()
    {
        auth('lab')->user()->token()->revoke();
        return response()->json('logged out');
    }
}
