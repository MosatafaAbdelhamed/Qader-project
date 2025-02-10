<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
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
