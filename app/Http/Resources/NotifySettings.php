<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotifySettings extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'notification_sound' => (bool) $this->notification_sound,
            'notification_email' => (bool) $this->notification_email,
            'notification_reminders' => (bool) $this->notification_reminders,
            'chat_sound' => (bool) $this->chat_sound
        ];
    }
}
