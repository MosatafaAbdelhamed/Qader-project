<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewVolunteerApplication extends Notification
{
    use Queueable;

    protected $application;
    protected $volunteer;
    protected $opportunity;

    public function __construct($application,$volunteer, $opportunity)
    {
        $this->application = $application;
        $this->volunteer = $volunteer;
        $this->opportunity = $opportunity;
    }

    public function via($notifiable)
    {
        return ['database' , 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'application_id' => $this->application->application_id,
        'volunteer_id' => $this->volunteer->volunteer_id,
        'volunteer_name' => $this->volunteer->name,
        'volunteer_email' => $this->volunteer->email,
        'volunteer_phone' => $this->volunteer->phone_number,
        'opportunity_id' => $this->opportunity->opportunity_id,
        'opportunity_title' => $this->opportunity->title,
        'message' => "قام {$this->volunteer->name} بالتقديم على الفرصة \"{$this->opportunity->title}\"",
        ];
    }
    public function toBroadcast($notifiable)
    {
    return new BroadcastMessage([
        'application_id' => $this->application->application_id,
        'message' => "قام {$this->volunteer->name} بالتقديم على الفرصة \"{$this->opportunity->title}\"",
        'volunteer_id' => $this->volunteer->id,
        'opportunity_id' => $this->opportunity->id,
        'type' => 'new_application'
    ]);
    }
}
