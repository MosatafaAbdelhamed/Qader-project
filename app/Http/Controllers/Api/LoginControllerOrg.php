<?php

namespace App\Http\Controllers\Api;

use App\Models\Organization;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginControllerOrg extends Controller
{
    public function login_org(Request $request)
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
                ], 401);
            }

            $organization = Organization::where('email', $request->email)->first();

            if (!$organization || !Hash::check($request->password, $organization->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid email or password.',
                ], 401);
            }

            if (!$organization instanceof Organization) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found, cannot generate token',
                ], 500);
            }

            $token = $organization->createToken("API TOKEN")->plainTextToken;


            return response()->json([
                'status' => true,
                'message' => 'Organization Logged In Successfully',
                'organization' => [
                    'id' => $organization->organization_id,
                    'name' => $organization->name,
                    'email' => $organization->email,
                    'location' => $organization->location,
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
