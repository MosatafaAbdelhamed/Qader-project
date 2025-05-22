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
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/organizations'), $filename);
            $data['img'] = 'uploads/organizations/' . $filename;
        }

        $organization->update($data);

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

        return response()->json($organization, 200);
    }
}

