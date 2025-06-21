<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Volunteer;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    //
    public function index()
    {
    $reports = Report::with(['volunteer' => function ($q) {
        $q->select('volunteer_id', 'phone_number');
        }])
    ->select('id', 'title', 'description', 'location','phone', 'urgency', 'created_at', 'volunteer_id')
        ->with('volunteer')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return response()->json($reports, 200);
    }


    public function store(Request $request)
    {
    $volunteer = Auth::user();

    if (!($volunteer instanceof Volunteer)) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $data = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'location' => 'required|string',
        'phone' => 'nullable|string',
        'urgency' => 'required|in:low,medium,high',
    ]);

    $data['volunteer_id'] = $volunteer->volunteer_id;

    $report = Report::create($data);

    return response()->json(['message' => 'تم إرسال البلاغ بنجاح', 'report' => $report], 201);
}
}
