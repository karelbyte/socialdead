<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubReminderToUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;

    public function __construct($dat)
    {
        $this->data = $dat;
    }

    public function build()
    {
        $subject =  $this->data['user_name'] . ' usuario SocialDead, correo de ayuda con recordatorio!';
        return $this->view('emails.user_notification_reminders')->subject($subject);
    }
}
