<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Application;

class ApplicationStatusUpdated extends Notification
{
    use Queueable;

    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'application_id' => $this->application->application_id,
            'opportunity_title' => $this->application->opportunity->title,
            'status' => $this->application->status,
            'message' => "تم تحديث حالة طلبك إلى: {$this->application->status} للفرصة \"{$this->application->opportunity->title}\"",
        ];
    }
}
