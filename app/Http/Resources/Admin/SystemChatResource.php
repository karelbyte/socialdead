<?php

namespace App\Http\Resources\Admin;

use App\Models\Admin\AdminUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class SystemChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->type === 'text')  $da = [ 'text'=> $this->msj ];
        if ($this->type === 'emoji') $da = [ 'emoji'=> $this->msj ];
        if ($this->type === 'file')  {
            $file = storage_path('app/public/') . $this->user_uid . '/files/' . $this->msj;
            $data = base64_encode(file_get_contents($file));
            $src = 'data: '. mime_content_type($file).';base64,'.$data;
            $da = [
                'file' => [
                'name'=> $this->msj ,
                'url'=> $src,
                'mime' => mime_content_type($file)
                ]
             ];
        }

        $patch = storage_path('app/public/') . '/social/social.png';

        $avatar = Image::make($patch )->resize(200, 150)->encode('data-url', 50)->encoded;

        $fromUser = AdminUser::query()->find($this->user_uid);

        if ($fromUser === null) {

            $fromUser = User::query()->find($this->user_uid);

            $name = $fromUser->full_names;
        } else {
            $name = '(SOCIALDEAD) ' . $fromUser->names;
        }

         return [
            'id' => $this->id,
            'type' => $this->type,
            'avatar' => $avatar,
            'author' => $this->user_uid,
            'name' => $name,
            'data' => $da,
            'status_id' =>  $this->status_id,
            'moment' => Carbon::parse($this->created_at)->diffForHumans(),
        ];
    }
}
