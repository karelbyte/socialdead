<?php

namespace App\Http\Resources;

use App\Models\Audio;
use App\Models\Photo;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Intervention\Image\Facades\Image;

class ReminderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       $thumbs = '';
       if (count($this->photos) > 0) {
           $item = $this->photos->first();
           $photo = Photo::query()->find($item['item_id']);
           $thumbs = Image::make(storage_path('app/public/') . $this->user_uid . '/photos/' . $photo->url)->resize(150, 150)->encode('data-url', 50)->encoded;
       } else {
           if (count($this->medias) > 0) {
               $item = $this->medias->first();
               if ((int) $item['type'] === 1) {
                   $video = Video::query()->find($item['item_id']);
                   $str = strlen($video->url);
                   $pureName = substr($video->url, 0,  $str-4);
                   $patch = storage_path('app/public/') . $this->user_uid . '/videos/' . $pureName . '.png';
                   $thumbs = Image::make($patch)->resize(150, 150)->encode('data-url', 50)->encoded;
               } else {
                   $patch = storage_path('app/public/') . '/social/audio_aux.jpg';
                   $thumbs  = Image::make($patch )->resize(200, 150)->encode('data-url', 50)->encoded;
               }
           }
       }
        return [
            'id' => $this->id,
            'moment' => Carbon::parse($this->moment)->format('d-m-Y'),
            'moment2' => Carbon::parse($this->moment)->format('d-m-Y'),
            'time_ago' => Carbon::parse($this->moment)->diffForHumans(),
            'title' => $this->title,
            'subtitle' =>  $this->subtitle,
            'note' => $this->note,
            'recurrent' => (bool) $this->recurrent,
            'extend' => (bool) $this->extend,
            'images' => $this->photos()->pluck('item_id'),
            'medias' => $this->medias->map(function ($itm) {
                return [
                  'type' => $itm->type,
                  'id' => $itm->item_id
                ];
            }),
            'thumbs' => $thumbs
        ];
    }
}
