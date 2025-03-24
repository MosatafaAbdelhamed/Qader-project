<?php

namespace App\Http\Controllers\Api;

use App\Models\Organization;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterControllerOrg extends Controller
{
    public function register(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:organizations,email',
                'password' => 'required|min:8',
                'location' => 'required|string|max:255',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 422);
            }

            $organization = Organization::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'location' => $request->location,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Organization Created Successfully',
                'organization' => [
                    'id' => $organization->organization_id,
                    'name' => $organization->name,
                    'email' => $organization->email,
                    'location' => $organization->location,
                ],
                'token' => $organization->createToken("API TOKEN")->plainTextToken
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create organization',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}

