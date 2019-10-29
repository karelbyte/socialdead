<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
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

       /* $user = User::query()->find($this->constables[1]['user_uid']);

        $constable1 = new UserOnly($user);

        if (count($this->constables) > 1) {
            $user1 = User::query()->find($this->constables[1]['user_uid']);
            $constable2 = $user1->uid;
        } else {
            $constable2 = null;
        }*/

        $emails = (count($this->emails) >0) ? $this->emails->map(function ($itm) {
            return [
                'id' => $itm['id'],
                'value'=> $itm['email']
            ];}) : [[
            'id' =>'534645457u4574',
            'value'=> ''
        ]];

        $filesStore = Storage::disk('public')->files( $this->user_uid .'/capsules/capsule'. $this->id);

        $files = collect($filesStore)->map( function ($f) {
            return ['name' => basename($f), 'type' => 'save'];
        });

        $constables = $this->constables->pluck('user_uid');

        return [
            'id' => $this->id,
            'moment' => Carbon::parse($this->opendate)->format('d-m-Y'),
            'opendate' =>$this->opendate,
            'time_ago' =>  Carbon::parse($this->opendate)->diffForHumans(null, false, false, 2),
            'title' => $this->title,
            'subtitle' =>  $this->subtitle,
            'note' => $this->note,
            'constable1' => isset($constables[0]) ? $constables[0] : '',
            'constable2' => isset($constables[1]) ? $constables[1] : '',
            'emails' => $emails,
            'images' => $this->photos()->pluck('item_id'),
            'medias' => $this->medias->map(function ($itm) {
                return [
                    'type' => $itm->type,
                    'id' => $itm->item_id
                ];
            }),
            'securitys' => (bool) $this->securitys,
            'files' => $files,
            'users' => $this->shares()->pluck('to_user'),
            'recurrent' => (bool) $this->recurrent,
            'activate' =>  $this->activate
        ];

    }
}
