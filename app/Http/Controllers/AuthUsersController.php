<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthUsersController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
           'name' => 'required|string',
           'email' => 'required|string|unique:users',
           'password' => 'required|string|min:7 '
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $user->save();
        return response()->json(['message'=> 'Registered Successfully']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required|string'
        ]);
        $credentials= request(['email','password']);
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token =$tokenResult->token;
        $token->expires_at = Carbon::now()->addweeks(1);
        $token->save();

        return response()->json(['data'=>[
            'user'=> Auth::user(),
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ]]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        $refreshTokenRepository = app('Laravel\Passport\RefreshTokenRepository');
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($request->user()->token()->id);

        return response()->json(['message' => 'You are logged out'],200);
    }

    public function changePassword()
    {

        return response()->json(['message' => 'Your password had changed successfully'],200);
    }

    public function resetPassword()
    {

    }
}
