<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterControllerVol extends Controller
{
    //
    public function registervol(Request $request)
    {
        try {
            //validated
            $validateUser = Validator::make($request->all(),
            [
                'name' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|string|max:11',
                'password' => 'required|min:8',
                // 'api_token' => Str::random(lenght: 60),
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ],401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                // 'api_token' => Str::random(lenght: 60)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully ',
                'token' => $user->createToken("API TOKEN")->plainTextToken

            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'faild create',
                'error' =>  $th->getMessage()
            ],500);
        }
    }
}
