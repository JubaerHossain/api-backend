<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    // login method

    public function login(Request $request)
    {
        // return $request;
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|string|max:24',
            'password' => 'required|string|min:8|',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Operation Fail',
                'data' => $validator->errors()
            ], 500);
            return response()->json(['error' => $validator->errors()], 400);
        }
        $user = User::where(request(['email']))->first(); 
            // If user not found
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => "No user found",
                    'data' => ["email" =>["No user found"]]
                ], 500);
            }
        $credentials = request(['email', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => "Email and Password doesn't match",
                'data' => ["email" =>["Email and Password doesn't match"]]
            ], 500);
        }

        return $this->createToken($token);
    }

    function profile(){
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function createToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 360
        ]);
    }
}
