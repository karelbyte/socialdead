<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Intervention\Image\Facades\Image;

class CapsuleUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = User::query()->find($this->constables[0]['user_uid']);
        $constable1 = new UserOnly($user);

        if (count($this->constables) > 1) {
            $user = User::query()->find($this->constables[1]['user_uid']);
            $constable2 = new UserOnly($user);
        } else {
            $constable2 = null;
        }

        $emails = (count($this->emails) >0) ? $this->emails->map(function ($itm) {
        return [
            'id' => $itm['id'],
            'value'=> $itm['email']
        ];}) : [];

        $patch = storage_path('app/public/') . '/social/capsule.png';
        $thumbs  = Image::make($patch )->encode('data-url',90)->encoded;

        return [
            'id' => $this->id,
            'opendate' => $this->opendate,
            'time_ago' => Carbon::parse($this->opendate)->diffForHumans(),
            'title' => $this->title,
            'subtitle' =>  $this->subtitle,
            'note' => $this->note,
            'constable1' => $constable1,
            'constable2' =>   $constable2,
            'emails' => $emails,
            'recurrent' => (bool) $this->recurrent,
             'thumbs' => $thumbs
        ];
    }
}
