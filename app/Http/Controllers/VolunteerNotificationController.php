<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;


class VolunteerNotificationController extends Controller
{
    public function index(Request $request)
    {
        $volunteer = auth('volunteer')->user();

        $notifications = $volunteer->notifications()
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

return response()->json([
    'notifications' => $notifications,

]);
}
public function markAsRead($id)
{
    $user = auth()->user();

    $notification = $user->notifications()->where('id', $id)->first();

    if (!$notification) {
        return response()->json(['message' => 'Notification not found'], 404);
    }

    $notification->markAsRead();

    return response()->json(['message' => 'Notification marked as read']);
}

}
