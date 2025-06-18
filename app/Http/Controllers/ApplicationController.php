<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use App\Models\Opportunity;
use App\Models\Volunteer;
use App\Models\Organization;
use App\Notifications\NewVolunteerApplication;
use App\Notifications\ApplicationStatusUpdated;


class ApplicationController extends Controller
{
    public function apply($opportunity_id)
    {
        $authUser = Auth::user();

if (!$authUser || !($authUser instanceof Volunteer)) {
    return response()->json(['message' => 'Unauthorized'], 403);
}

$volunteer = Volunteer::find($authUser->volunteer_id);

        if (!$volunteer || !($volunteer instanceof Volunteer)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $existingApplication = Application::where('volunteer_id', $volunteer->volunteer_id)
            ->where('opportunity_id', $opportunity_id)
            ->first();

        if ($existingApplication) {
            return response()->json(['message' => 'You already applied to this opportunity'], 400);
        }

        $opportunity = Opportunity::find($opportunity_id);

        if (!$opportunity) {
            return response()->json(['message' => 'Opportunity not found'], 404);
        }

        $application = Application::create([
            'volunteer_id' => $volunteer->volunteer_id,
            'opportunity_id' => $opportunity_id,
            'status' => 'pending',
            'date' => now()->toDateString(),

        ]);


        $organization = Organization::findOrFail($opportunity->organization_id);
        $organization->notify(new NewVolunteerApplication($volunteer, $opportunity));

        return response()->json([
            'message' => 'Application submitted successfully',
            'application' => $application
        ], 201);
    }

    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,accepted,rejected',
    ]);

    $application = Application::findOrFail($id);

    $authUser = auth()->user();

    if (!($authUser instanceof Organization)) {
        return response()->json(['message' => 'غير مسموح لك بتعديل هذا الطلب'], 403);
    }

    if ($application->opportunity->organization_id !== $authUser->organization_id) {
        return response()->json(['message' => 'غير مسموح لك بتعديل هذا الطلب'], 403);
    }

    $application->status = $request->status;
    $application->save();

    $application->volunteer->notify(new ApplicationStatusUpdated($application));

    return response()->json([
        'message' => 'تم تحديث حالة الطلب بنجاح',
        'application' => $application,
    ]);
}
public function myApplications()
{
    $authUser = Auth::user();

    if (!$authUser || !($authUser instanceof Volunteer)) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $applications = Application::where('volunteer_id', $authUser->volunteer_id)
        ->with(['opportunity.category', 'opportunity.organization'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return response()->json([
        'applications' => $applications
    ], 200);
}

}
