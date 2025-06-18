<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Organization;

class OrganizationProfileController extends Controller
{
    public function update(Request $request)
    {
        $organization = Auth::user();

        if (!$organization || !($organization instanceof Organization)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:organizations,email,' . $organization->id,
            'location' => 'sometimes|string|max:255',
            'phone' => 'sometimes|regex:/^01[0-9]{9}$/',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);


        if ($request->hasFile('img')) {
        $file = $request->file('img');

        if (!$file->isValid()) {
            return response()->json(['error' => 'الملف غير صالح'], 422);
        }

        $filename = time() . '_' . $file->getClientOriginalName();
        $destinationPath = public_path('uploads/organizations');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0775, true);
        }

        try {
            $file->move($destinationPath, $filename);
            $data['img'] = 'uploads/organizations/' . $filename;
        } catch (\Exception $e) {
            return response()->json([
            'error' => 'فشل في رفع الصورة',
            'details' => $e->getMessage()
        ], 500);
        }
    }

        $organization->update($data);

        if ($organization->img) {
        $organization->img = asset($organization->img);
        }


        return response()->json([
            'message' => 'Profile updated successfully',
            'organization' => $organization
        ], 200);
    }

    public function show()
    {
        $organization = Auth::user();

        if (!$organization || !($organization instanceof Organization)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        if ($organization->img) {
        $organization->img = asset($organization->img);
        }


        return response()->json($organization, 200);
    }
}

