<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Str;

class RegisterControllerOrg extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User
     */
    public function register(Request $request)
    {
        try {
            //validated
            $validateUser = Validator::make($request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'location' => 'required|string|max:255',
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
                'password' => Hash::make($request->password),
                'location' => $request->location,
                // 'api_token' => Str::random(lenght: 60),
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
