<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = User::create([
             'email'    => $request->email,
             'password' => $request->password,
         ]);

        $token = auth()->login($user);

        return $this->respondWithToken($token);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);
        if ($token = JWTAuth::attempt($credentials)) {
            return $this->respondWithToken($token);
        }
    
        return response()->json(['success' => false, 'message' => 'Your Data Didn\'t Match Our Record!'], 200);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            
            'success' => true,
            'access_token' => $token,
            'user' => $this->me()->getOriginalContent(),
        ]);
    }

    public function me()
    {

        return response()->json(Auth::User());
    }


}
