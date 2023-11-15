<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function read(){
        $user = User::all();
        if(count($user) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $user
            ], 200);
        }else{
            return response([
                'message' => 'Empty',
                'data' => null
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $updatedData = $request->all();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User Not Found',
            ], 404);
        }

        $validatedData = Validator::make($updatedData, [
            'name' => 'required|max:55',
            'email' => 'required|email|unique:users|min:8',
            'password' => 'required|min:8',
            'image' => ['required', 'mimes:jpg,jpeg,png', 'max:2048'],
            'no_telp' => ['required', 'numeric', 'regex:/^08[0-9]{9,11}$/'],
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Update User Failed',
                'data' => $validatedData->errors()
            ], 400);
        }

        $updatedData['password'] = bcrypt($request->password);
        $user->update($updatedData);

        return response()->json([
            'success' => true,
            'message' => 'Success Update User',
            'data' => $user
        ], 200);
    }

    public function delete($id){
        $user = User::find($id);
        if(is_null($user)){
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }

        if($user->delete()){
            return response([
                'message' => 'Delete User Success',
                'data' => $user,
            ], 200);
        }else{
            return response([
                'message' => 'Delete User Failed',
                'data' => null,
            ], 400);
        }
    }

    public function search($id){
        $user = User::find($id);
        if(is_null($user)){
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }else{
            return response([
                'message' => 'Retrieve User Success',
                'data' => $user,
            ], 200);
        }

    }
}
