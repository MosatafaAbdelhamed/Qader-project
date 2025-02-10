<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Http\Request;

class OpportunitiesController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $opportunities = Opportunity::select('id', 'title', 'description', 'location')->get();
        return response()->json($opportunities, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $opportunity = Opportunity::create($data);
        return response()->json(['message' => 'Opportunity created successfully', 'opportunity' => $opportunity], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $opportunity = Opportunity::find($id);
        if (!$opportunity) {
            return response()->json(['message' => 'Opportunity not found'], 404);
        }
        return response()->json($opportunity, 200);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $opportunity = Opportunity::find($id);
        if (!$opportunity) {
            return response()->json(['message' => 'Opportunity not found'], 404);
        }

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'category_id' => 'sometimes|exists:categories,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $opportunity->update($data);
        return response()->json(['message' => 'Opportunity updated successfully', 'opportunity' => $opportunity], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $opportunity = Opportunity::find($id);
        if (!$opportunity) {
            return response()->json(['message' => 'Opportunity not found'], 404);
        }

        $opportunity->delete();
        return response()->json(['message' => 'Opportunity deleted successfully'], 200);
    }

}
