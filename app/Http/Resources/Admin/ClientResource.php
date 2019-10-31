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

class ClientResource extends JsonResource
{
    use Zodiac;

    public function toArray($request)
    {
        $avatar = $this->avatar === null ? Image::make($this->symbol($this->birthdate)['url'])->encode('data-url')
            : Image::make(storage_path('app/public/') . $this->uid . '/profile/avatar/' . $this->avatar)->resize(150, 150)->encode('data-url', 50);

        $inStore = UserStore::query()->where('user_uid', $this->uid)->first();

        $count_contact = Contact::query()->where('user_uid', $this->uid)->count();

       return [
         'uid' => $this->uid,
         'names' =>  $this->full_names,
         'status'  => (string) $this->status_id,
         'email' => $this->email,
         'avatar' => $avatar->encoded,
         'gigas' =>  $inStore !== null ? $inStore->gigas : 0,
         'inuse' =>  $inStore !== null ? $inStore->inuse :0,
         'contacts' => $count_contact
       ];
    }
}
