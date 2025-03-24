<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;


class OrganizationController extends Controller
{

    public function index()
    {
        $organizations = Organization::select('organization_id', 'name', 'location')->paginate(10);
        return response()->json(['organizations' => $organizations], 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',

        ]);

        $organization = Organization::create($data);
        return response()->json(['message' => 'Organization created successfully', 'organization' => $organization], 201);
    }


    public function update(Request $request, $organization_id)
    {
        $organization = Organization::find($organization_id);
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'location' => 'sometimes|string|max:255',

        ]);

        $organization->update($data);
        return response()->json(['message' => 'Organization updated successfully', 'organization' => $organization], 200);
    }

    /**
     * حذف مؤسسة.
     */
    public function destroy($organization_id)
    {
        $organization = Organization::find($organization_id);
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }

        $organization->delete();
        return response()->json(['message' => 'Organization deleted successfully'], 200);
    }

    public function search(Request $request)
{
    $query = Organization::query();

    if ($request->has('keyword')) {
        $query->where('name', 'LIKE', '%' . $request->keyword . '%')
              ->orWhere('location', 'LIKE', '%' . $request->keyword . '%');
    }

    $organizations = $query->select('organization_id', 'name', 'location')->paginate(10);

    return response()->json($organizations, 200);
}

}
