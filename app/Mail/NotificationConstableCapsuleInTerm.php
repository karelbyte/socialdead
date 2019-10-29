<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationConstableCapsuleInTerm extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($dat)
    {
        $this->data = $dat;
    }

    public function build()
    {
        $subject = 'Elisa de SocialDead SocialDead, correo de notificación!';
        return $this->view('emails.capsule_constable_notification_in_term')->subject($subject);
    }
}
