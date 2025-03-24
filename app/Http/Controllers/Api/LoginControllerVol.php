<?php

namespace App\Http\Controllers\Api;

use App\Models\Volunteer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginControllerVol extends Controller
{
    public function login_vol(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 422);
            }

            $volunteer = Volunteer::where('email', $request->email)->first();

            if (!$volunteer || !Hash::check($request->password, $volunteer->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid email or password.',
                ], 401);
            }

            if (!$volunteer instanceof Volunteer) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found, cannot generate token',
                ], 500);
            }

            $token = $volunteer->createToken("API TOKEN")->plainTextToken;


            return response()->json([
                'status' => true,
                'message' => 'Volunteer Logged In Successfully',
                'volunteer' => [
                'id' => $volunteer->volunteer_id,
                'name' => $volunteer->name,
                'email' => $volunteer->email,
                'phone_number' => $volunteer->phone_number,
            ],
            'token' => $token
        ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Login failed',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'User Logged out Successfully',
        ], 200);
    }
}
