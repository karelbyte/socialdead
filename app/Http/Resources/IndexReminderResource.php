<?php

namespace App\Http\Resources;

use App\Models\Photo;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\Collection;

class IndexReminderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $fotos = new Collection();
        foreach ($this->photos as $phot) {
            $photo = Photo::query()->find($phot['item_id']);
            $thumbs = Image::make(storage_path('app/public/') . $this->user_uid . '/photos/' . $photo->url)->resize(150, 150)->encode('data-url', 50)->encoded;
            $dat = [
                'id' => $photo->id,
                'thumbs' => $thumbs
            ];
            $fotos->add($dat);
        }
      $videos = new Collection();
        foreach ($this->videos as $vid) {
            $video = Video::query()->find($vid['item_id']);
            $str = strlen($video->url);
            $pureName = substr($video->url, 0,  $str-4);
            $patch = storage_path('app/public/') . $this->user_uid . '/videos/' . $pureName . '.png';
            $thumbs = Image::make($patch)->resize(150, 150)->encode('data-url', 50)->encoded;
            $dat = [
                'id' => $video->id,
                'thumbs' => $thumbs
            ];
            $videos->add($dat);
        }
     $audios = new Collection();
      $patch = storage_path('app/public/') . '/social/audio_aux.jpg';
        $thumbs  = Image::make($patch )->resize(200, 150)->encode('data-url', 50)->encoded;
        foreach ($this->audios as $au) {
            $dat = [
                'id' => $au['item_id'],
                'thumbs' => $thumbs
            ];
            $audios->add($dat);
        }

        return [
            'cron' => Str::uuid(),
            'user' => [
                'avatar' =>  Image::make(storage_path('app/public/') . '/social/reminder.png' )->resize(200, 150)->encode('data-url', 50)->encoded,
            ],
            'id' => $this->id,
            'moment' => (int) Carbon::parse($this->moment)->timestamp,
            'time_ago' => Carbon::parse($this->moment)->diffForHumans(),
            'title' => $this->title,
            'subtitle' => 'Un dia como hoy pero de ' . Carbon::parse($this->moment)->diffForHumans() . ' programastes este recordatorio',
            'note' => $this->note,
            'rating' => $this->rating,
            'type' => 4, // Recordatorio
            'photos' => $fotos,
            'videos' => $videos,
            'audios' => $audios
        ];
    }
}
