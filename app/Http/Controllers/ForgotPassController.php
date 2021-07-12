<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPassRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ForgotPassController extends Controller
{
    public function forgotpassword(ForgotPassRequest $request)
    {
        $email = $request->input('email');
        if(User::where('email',$email)->doesntExists())
        {
            return response([
                'message' => 'User does not exist'
            ],404);
        }
        $token = Str::random(10);
        try {
        DB::table('password_rests')->insert([
            'email' => $email,
            'token' => $token
        ]);
        return response([
            'message'=>'Check your email'
        ]);
        }
        catch (\Exception $exception) {
            return response([
                'message' => $exception->getMessage()
            ],400);
        }
    }

    public function resetPassword() {
        $credentials = request()->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        $reset_password_status = Password::reset($credentials, function ($user, $password) {
            $user->password = $password;
            $user->save();
        });

        if ($reset_password_status == Password::INVALID_TOKEN) {
            return response()->json(["msg" => "Invalid token provided"], 400);
        }

        return response()->json(["msg" => "Password has been successfully changed"]);
    }
}
