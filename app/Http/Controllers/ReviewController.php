<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;


class ReviewController extends Controller
{
    //
    public function store(Request $request)
{
    $volunteer = Auth::user();

    if (!$volunteer || !($volunteer instanceof \App\Models\Volunteer)) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $data = $request->validate([
        'organization_id' => 'required|exists:organizations,organization_id',
        'rating' => 'required|integer|between:1,5',
        'comment' => 'nullable|string|max:1000',
    ]);

    $alreadyAccepted = Application::join('opportunities', 'applications.opportunity_id', '=', 'opportunities.opportunity_id')
    ->where('applications.volunteer_id', $volunteer->volunteer_id)
    ->where('applications.status', 'accepted')
    ->where('opportunities.organization_id', $data['organization_id'])
    ->exists();

    if (!$alreadyAccepted) {
    return response()->json(['message' => 'لا يمكنك تقييم المؤسسة إلا بعد القبول في إحدى فرصها'], 403);
    }

    $existing = Review::where('volunteer_id', $volunteer->volunteer_id)
        ->where('organization_id', $data['organization_id'])
        ->first();

    if ($existing) {
        return response()->json(['message' => 'You already reviewed this organization'], 400);
    }

    $review = Review::create([
        'volunteer_id' => $volunteer->volunteer_id,
        'organization_id' => $data['organization_id'],
        'rating' => $data['rating'],
        'comment' => $data['comment'] ?? null,
    ]);

    return response()->json(['message' => 'تم إرسال التقييم بنجاح', 'review' => $review], 201);


}

}
