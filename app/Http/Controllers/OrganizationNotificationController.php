<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class OrganizationNotificationController extends Controller
{
    public function index(Request $request)
    {
        $organization = auth('organization')->user();

        $notifications = $organization->notifications()->orderBy('created_at', 'desc')->get();

        return response()->json(['notifications' => $notifications]);
    }
}
