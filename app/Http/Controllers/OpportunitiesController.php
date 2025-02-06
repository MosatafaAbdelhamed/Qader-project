<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OpportunitiesController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $opportunities = Opportunity::select('title', 'description', 'location')->get();

        return response()->json($opportunities, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $opportunity = Opportunity::create($data);
        return response()->json($opportunity, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $opportunity = Opportunity::findOrFail($id);
        return response()->json($opportunity, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $opportunity = Opportunity::findOrFail($id);
        $opportunity->update($request->all());
        return response()->json($opportunity, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        Opportunity::destroy($id);
        return response()->json(['message' => 'Deleted Successfully'], 200);

    }




}
