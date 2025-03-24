<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class OpportunitiesController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $opportunities = Opportunity::select('opportunity_id', 'title', 'description', 'category_id', 'organization_id', 'start', 'end')
        ->with(['category', 'organization'])->paginate(10);

        return response()->json($opportunities, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $organization = auth()->user();

    $data = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'category_id' => 'required|exists:categories,category_id',
        'start' => 'nullable|date',
        'end' => 'nullable|date|after_or_equal:start',
    ]);


    $data['organization_id'] = $organization->organization_id;

    $opportunity = Opportunity::create($data);
    return response()->json(['message' => 'Opportunity created successfully', 'opportunity' => $opportunity], 201);
}

    /**
     * Display the specified resource.
     */
    public function myOpportunities(Request $request)
    {
    $organization = Auth::user();

    if (!$organization || !($organization instanceof Organization)) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $opportunities = Opportunity::where('organization_id', $organization->organization_id)
        ->with(['category', 'organization'])
        ->paginate(10);

    return response()->json($opportunities, 200);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $opportunity_id)
{
    $organization = auth()->user();
    $opportunity = Opportunity::where('opportunity_id', $opportunity_id)
        ->where('organization_id', $organization->organization_id)
        ->first();

    if (!$opportunity) {
        return response()->json(['message' => 'Opportunity not found or unauthorized'], 404);
    }

    $data = $request->validate([
        'title' => 'sometimes|string|max:255',
        'description' => 'sometimes|string',
        'category_id' => 'sometimes|exists:categories,category_id',
        'start' => 'nullable|date',
        'end' => 'nullable|date|after_or_equal:start',
    ]);

    $opportunity->update($data);
    return response()->json(['message' => 'Opportunity updated successfully', 'opportunity' => $opportunity], 200);
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($opportunity_id)
{
    $organization = auth()->user();
    $opportunity = Opportunity::where('opportunity_id', $opportunity_id)
        ->where('organization_id', $organization->organization_id)
        ->first();

    if (!$opportunity) {
        return response()->json(['message' => 'Opportunity not found or unauthorized'], 404);
    }

    $opportunity->delete();
    return response()->json(['message' => 'Opportunity deleted successfully'], 200);
}

    public function search(Request $request)
    {
        $query = Opportunity::query();

        if ($request->has('category_id')) {
        $query->where('category_id', $request->category_id);
        }

        if ($request->has('title')) {
            $query->where('title', 'LIKE', '%' . $request->title . '%');
        }

        if ($request->has('start')) {
            $query->whereDate('start', $request->start);
        }


        // if ($request->has('available_seats')) {
        //     $query->where('available_seats', '>=', $request->available_seats);
        // }

        if ($request->has('sort_by')) {
            $sortBy = $request->sort_by;
            $sortOrder = $request->get('sort_order', 'asc');

            $validColumns = ['title', 'start', 'available_seats', 'created_at'];
            if (in_array($sortBy, $validColumns)) {
                $query->orderBy($sortBy, $sortOrder);
            }
        }

        if ($request->has('keyword')) {
        $query->where(function ($q) use ($request) {
            $q->where('title', 'LIKE', '%' . $request->keyword . '%')
            ->orWhere('description', 'LIKE', '%' . $request->keyword . '%');
        });
    }


    $opportunities = $query->with(['category', 'organization'])->paginate(10);

    return response()->json($opportunities, 200);
}

}
