<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registrationData = $request->all();
        $validatedData = Validator::make($registrationData, [
            'name' => 'required|max:55',
            'email' => 'required|email|unique:users|min:8',
            'password' => 'required|min:8',
            'image' => ['required', 'mimes:jpg,jpeg,png', 'max:2048'],
            'no_telp' => ['required', 'numeric', 'regex:/^08[0-9]{9,11}$/'],
        ]
    );



        if ($validatedData->fails()) {
            return response(['message' => 'Registration failed', 'errors' => $validatedData->errors()], 400);
        }

        $YY = Carbon::now()->format('y');
        $MM = Carbon::now()->format('m');
        $I = 1;

        $userId = $YY . '.' . $MM .'.'. $I;
        while(User::find($userId)){
            $I++;
            $userId = $YY . '.' . $MM .'.'. $I;
        }

        $registrationData['status'] = 0;
        $registrationData['password'] = bcrypt($request->password);

        $user = User::create([
            'id' => $userId,
            'name' => $registrationData['name'],
            'email' => $registrationData['email'],
            'password' => $registrationData['password'],
            'no_telp' => $registrationData['no_telp'],
            'status' => $registrationData['status'],
            'image' => $registrationData['image']
        ]);
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
