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
                'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'phone' => 'required|regex:/^01[0-9]{9}$/',
            ]);

            $imgPath = null;

if ($request->hasFile('img')) {
    $file = $request->file('img');
    $filename = time() . '_' . $file->getClientOriginalName();
    $file->move(public_path('uploads/organizations'), $filename);
    $imgPath = 'uploads/organizations/' . $filename;
}

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
                'phone' => $request->phone,
                'img' => $imgPath,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Organization Created Successfully',
                'organization' => [
                    'id' => $organization->organization_id,
                    'name' => $organization->name,
                    'email' => $organization->email,
                    'location' => $organization->location,
                    'phone' => $organization->phone,
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

