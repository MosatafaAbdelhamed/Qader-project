<?php

namespace App\Http\Controllers\Api;

use App\Models\Volunteer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterControllerVol extends Controller
{
    public function registervol(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'email' => 'required|email|unique:volunteers,email',
                'phone_number' => 'required|string|max:11|min:11',
                'password' => 'required|min:8',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 422);
            }

            $volunteer = Volunteer::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Volunteer Created Successfully',
                'volunteer' => $volunteer,
                'token' => $volunteer->createToken("API TOKEN")->plainTextToken
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create volunteer',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
