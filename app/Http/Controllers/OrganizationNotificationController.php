<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class OrganizationNotificationController extends Controller
{
    public function index(Request $request)
    {
        $organization = auth('organization')->user();

        $notifications = $organization->notifications()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function (DatabaseNotification $notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'data' => $notification->data,
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at,
                    'is_read' => $notification->read_at !== null,
                ];
            });

        return response()->json(['notifications' => $notifications]);
    }

    public function markAsRead($id)
    {
        $organization = auth('organization')->user();

        $notification = $organization->notifications()->where('id', $id)->first();

        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $notification->markAsRead();

        return response()->json(['message' => 'Notification marked as read']);
    }
}
