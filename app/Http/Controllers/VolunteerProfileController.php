<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Volunteer;

class VolunteerProfileController extends Controller
{
    public function show()
    {
        $volunteer = Auth::user();

        if (!$volunteer || !($volunteer instanceof Volunteer)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($volunteer, 200);
    }

    public function update(Request $request)
    {
        $volunteer = Auth::user();

        if (!$volunteer || !($volunteer instanceof Volunteer)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:volunteers,email,' . $volunteer->id,
            'phone_number' => 'sometimes|regex:/^01[0-9]{9}$/',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/volunteers'), $filename);
            $data['img'] = 'uploads/volunteers/' . $filename;
        }

        $volunteer->update($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'volunteer' => $volunteer
        ], 200);
    }
}
