<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('q');
        return response()->json(['message' => "نتائج البحث عن: $query"]);
    }

    /**
     * Search for a resource based on the query.
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        // إضافة البحث
        return response()->json(['message' => "نتائج البحث عن: $query"]);
    }


}
