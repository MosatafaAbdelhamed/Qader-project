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

    if ($volunteer->img) {
        $volunteer->img = asset($volunteer->img);
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

        if (!$file->isValid()) {
            return response()->json(['error' => 'الملف غير صالح'], 422);
        }

        $filename = time() . '_' . $file->getClientOriginalName();
        $destinationPath = public_path('uploads/volunteers');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0775, true);
        }

        try {
            $file->move($destinationPath, $filename);
            $data['img'] = 'uploads/volunteers/' . $filename;
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'فشل في رفع الصورة',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    $volunteer->update($data);

    if ($volunteer->img) {
        $volunteer->img = asset($volunteer->img);
    }

    return response()->json([
        'message' => 'Profile updated successfully',
        'volunteer' => $volunteer
    ], 200);
}

}
