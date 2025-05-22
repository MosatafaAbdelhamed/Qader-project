<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewVolunteerApplication extends Notification
{
    use Queueable;

    protected $volunteer;
    protected $opportunity;

    public function __construct($volunteer, $opportunity)
    {
        $this->volunteer = $volunteer;
        $this->opportunity = $opportunity;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'volunteer_id' => $this->volunteer->id,
            'volunteer_name' => $this->volunteer->name,
            'volunteer_email' => $this->volunteer->email,
            'opportunity_id' => $this->opportunity->id,
            'opportunity_title' => $this->opportunity->title,
            'message' => "قام {$this->volunteer->name} بالتقديم على الفرصة \"{$this->opportunity->title}\"",
        ];
    }
}
