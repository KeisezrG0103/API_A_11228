<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registrationData = $request->all();
        $validatedData = Validator::make($registrationData, [
            'name' => 'required|max:55',
            'email' => 'required',
            'password' => 'required',
            'no_telp' => 'required'
        
        ]);
        if ($validatedData->fails()) {
            return response(['message' => 'Registration failed', 'errors' => $validatedData->errors()], 400);
        }

        $registrationData['status'] = 0;
        $registrationData['password'] = bcrypt($request->password);


        $user = User::create($registrationData);


        return response([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }


    public function login(Request $request)
    {
        $loginData = $request->all();
        $validatedData = Validator::make($loginData, [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validatedData->fails()) {
            return response(['message' => 'Login failed', 'errors' => $validatedData->errors()], 400);
        }

        if (!auth()->attempt($loginData)) {
            return response(['message' => 'Invalid credentials']);
        }
        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response([
            'message' => 'Login success',
            'user' => auth()->user(),
            'access_token' => $accessToken,
            "token_type" => "Bearer",
        ]);
    }
}
