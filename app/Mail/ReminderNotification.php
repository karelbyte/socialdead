<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReminderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($dat)
    {
        $this->data = $dat;
    }

    public function build()
    {
        $subject =  $this->data['user_name'] . ' usuario SocialDead, correo de recordatorio!';
        return $this->view('emails.reminders_notification')->subject($subject);
    }
}
