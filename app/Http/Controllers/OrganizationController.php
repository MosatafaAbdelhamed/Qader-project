<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;


class OrganizationController extends Controller
{

    //
    public function index()
    {
        $organizations = Organization::select('id', 'name', 'location', 'description')->get();
        return response()->json(['organizations' => $organizations], 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $organization = Organization::create($data);
        return response()->json(['message' => 'Organization created successfully', 'organization' => $organization], 201);
    }

    /**
     * عرض مؤسسة معينة عبر ID.
     */
    public function show($id)
    {
        $organization = Organization::find($id);
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }
        return response()->json(['organization' => $organization], 200);
    }

    /**
     * تحديث بيانات مؤسسة.
     */
    public function update(Request $request, $id)
    {
        $organization = Organization::find($id);
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'location' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
        ]);

        $organization->update($data);
        return response()->json(['message' => 'Organization updated successfully', 'organization' => $organization], 200);
    }

    /**
     * حذف مؤسسة.
     */
    public function destroy($id)
    {
        $organization = Organization::find($id);
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }

        $organization->delete();
        return response()->json(['message' => 'Organization deleted successfully'], 200);
    }
}
