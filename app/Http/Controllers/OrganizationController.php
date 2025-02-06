<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;

class OrganizationController extends Controller
{
    //
    public function index()
{
    $organizations = Organization::select('name', 'location', 'description')->get();
    return response()->json($organizations);
}
}
