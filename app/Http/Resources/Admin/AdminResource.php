<?php

namespace App\Http\Resources\Admin;

use App\Models\Contact;
use App\Models\UserStore;
use App\Traits\UserFileStore;
use App\Traits\Zodiac;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AdminResource extends JsonResource
{

    public function toArray($request)
    {
        $patch = storage_path('app/public/') . '/social/social.png';

        $avatar = Image::make($patch )->resize(200, 150)->encode('data-url', 50)->encoded;

       return [
         'uid' => $this->uid,
         'names' =>  $this->names,
         'status'  => (string) $this->status_id,
         'email' => $this->email,
         'avatar' => $avatar
       ];
    }
}
