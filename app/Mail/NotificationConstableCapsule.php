<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationConstableCapsule extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($dat)
    {
        $this->data = $dat;
    }

    public function build()
    {
        $subject = $this->data['from'] . ' usuario SocialDead, correo de notificación!';
        return $this->view('emails.capsule_constable_notification')->subject($subject);
    }
}
