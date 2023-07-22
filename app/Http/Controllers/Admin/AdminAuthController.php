<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AdminAuthRequest;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    public function auth(Request $request)
    {

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin ) {
            throw ValidationException::withMessages([
                'email' => ['email  is incorrect'],
            ]);
        }
            if ( !Hash::check($request->password, $admin->password)) {
                throw ValidationException::withMessages([
                    'email' => ['password is incorrect'],
                ]);
        } else {
            $token = $admin->createToken($request->email);
            return response()->json(['token' => $token->accessToken]);
        }
    }

    private function cheackPassword($admin, $old_password)
    {
        if (Hash::check($old_password, $admin->password)) return true;
        return false;
    }
    public function profileAdmin(AdminAuthRequest  $request)
    {
        $return = ['update profile sucessfully'];
        $fails = [' old password  not correct'];
        $admin = auth('admin')->user();
        $updatedFiled = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->old_password) {
            if ($this->cheackPassword($admin, $request->old_password) == true) {

                $updatedFiled = array_merge($updatedFiled, [
                    'password' => $request->new_password,
                ]);
            } else {
                return response()->json(['message' => $fails], 422);
            }
        }
        auth('admin')->user()->update($updatedFiled);
        return response()->json(['message' => $return]);

    }

    public function getProfile()
    {

        $data = Admin::find(auth('admin')->user()->id);
        return response()->json($data);
    }
    public function logout()
    {
         auth('admin')->user()->token()->revoke();
         return response()->json('logged out');
    }
}
