<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class VolunteerNotificationController extends Controller
{
    public function index(Request $request)
    {
        $volunteer = auth('volunteer')->user();

        $notifications = $volunteer->notifications()->orderBy('created_at', 'desc')->get();

        return response()->json(['notifications' => $notifications]);
    }
}
