<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use App\Http\Resources\Notify;
use App\Http\Resources\ReminderResource;
use App\Mail\UserNotification;
use App\Models\Audio;
use App\Models\Notification;
use App\Models\Photo;
use App\Models\Reminder;
use App\Models\ReminderShare;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class RemindersController extends Controller
{
    public function getList(Request $request) {
        $data = Reminder::query()->with('audios', 'medias')
            ->where('user_uid', $request->user()->uid)
            ->orderBy('moment', 'desc')
            ->get();
        return ReminderResource::collection($data);
    }

    public function ReminderDelete(Request $request) {
        Reminder::query()
            ->where('id', $request->id)
            ->delete();
        return http_response_code(200);
    }

    public function AcceptReminder(Request $request) {
        Reminder::query()
            ->where('id', $request->id)
            ->update(['extend' => null]);
        return http_response_code(200);
    }

    public function saveReminder(Request $request) {
        $item = $request->item;
        $reminder = Reminder::query()
            ->create([
                'user_uid' => $request->user()->uid,
                'moment' => Carbon::parse($item['moment']),
                'title' => $item['title'],
                'subtitle' => $item['subtitle'],
                'note' => $item['note'],
                'type' => 1,
                'clone' => $item['recurrent'],
                'recurrent' => $item['extend']
            ]);
        foreach ($request->images as $image) {
            $reminder->details()->create([
                'type' => 3, //FOTOS
                'item_id' => $image
            ]);
        }

        foreach ($request->medias as $media) {
            $reminder->details()->create([
                'type' => $media['type'],
                'item_id' => $media['id']
            ]);
        }

        $data = Reminder::query()
            ->where('user_uid', $request->user()->uid)
            ->orderBy('moment', 'desc')
            ->get();

        return  ReminderResource::collection($data);
    }

    public function updateReminder(Request $request) {

        $item = $request->item;
        Reminder::query()->where('id', $item['id'])
            ->update([
                'moment' => Carbon::parse($item['moment']),
                'title' => $item['title'],
                'subtitle' => $item['subtitle'],
                'note' => $item['note'],
                'recurrent' => $item['recurrent']
            ]);


        $reminder = Reminder::query()->find($item['id']);

        if ($reminder->details !== null) {
            $reminder->details()->delete();
        }

        foreach ($request->images as $image) {
            $reminder->details()->create([
                'type' => 3, //FOTOS
                'item_id' => $image
            ]);
        }

        foreach ($request->medias as $media) {
            $reminder->details()->create([
                'type' => $media['type'],
                'item_id' => $media['id']
            ]);
        }

        $data = Reminder::query()
            ->where('user_uid', $request->user()->uid)
            ->orderBy('moment', 'desc')
            ->get();

        return  ReminderResource::collection($data);
    }

    public function saveShareUser ($uidUser, $idReminder, $user_owner) {
         $reminder = Reminder::query()->find($idReminder);
         $extendReminder = $reminder->replicate();
         $extendReminder->user_uid = $uidUser;
         $extendReminder->extend = true;
         $extendReminder->save();
         // NOTIFICANDO
        // Guardando notificacion en la db
        $data = Notification::query()->create([
            'type_id' => 3, // EXTENDER RECORDATORIO
            'moment' => Carbon::now(),
            'from_user' => $user_owner,
            'to_user' => $uidUser,
            'note' => 'Recordatorio compartido',
            'status_id' => 1 // NO VISTO
        ]);
        // ENVIANDO NOTIFICACION POR EMAIL SI ESTA ACTIVA ESA CONDICION
        if ($data->toUser->settingNotifications->notification_email === 1) {
            $data_email = [
                'from' => $data->fromUser->full_names,
                'to' => $data->toUser->full_names,
                'note' => 'Recordatorio compartido'
            ];
            Mail::to($data->touser->email)->send(new UserNotification($data_email));
        }
        broadcast(new NotificationEvent($uidUser, new Notify($data)))->toOthers();
        //-----------------
         foreach ($reminder->details as $det) {
              switch ($det['type']) {
                  case 1:
                      $video = Video::query()->find($det['item_id']);
                      $patch_owner =  $user_owner . '/videos/' .  $video->url;
                      $patch_user = storage_path('app/public/') . $uidUser. '/videos/';
                      $str = strlen($video->url);
                      $pureName = substr($video->url, 0,  $str-4);
                      File::exists($patch_user) or File::makeDirectory($patch_user, 0777, true, true);
                      Storage::disk('public')->copy($patch_owner,  $uidUser. '/videos/'  . $pureName . 'S.MP4');
                      // COPIA DE LA IMAGEN DE TUMBS

                      $patch_owner_tumbs =  $user_owner . '/videos/' .   $pureName . '.png';
                      Storage::disk('public')->copy($patch_owner_tumbs,  $uidUser. '/videos/'  .  $pureName . 'S.png');
                      // --------------------
                      $new = Video::query()->create([
                          'user_uid' => $uidUser,
                          'moment' => Carbon::now(),
                          'url' =>  $pureName . 'S.MP4',
                          'title' =>  'sin titulo',
                          'subtitle' =>  'sin subtitulo'
                      ]);
                      break;
                  case 2:
                      $audio = Audio::query()->find($det['item_id']);
                      $patch_owner =  $user_owner . '/audios/' .  $audio->url;
                      $patch_user = storage_path('app/public/') . $uidUser. '/audios/';
                      File::exists($patch_user) or File::makeDirectory($patch_user, 0777, true, true);
                      Storage::disk('public')->copy($patch_owner, $patch_user  .  $audio->url);
                      $new = Audio::query()->create([
                          'user_uid' => $uidUser,
                          'moment' => Carbon::now(),
                          'url' =>  $audio->url,
                          'title' =>  'sin titulo',
                          'subtitle' =>  'sin subtitulo'
                      ]);
                      break;
                  case 3:
                   $photo = Photo::query()->find($det['item_id']);
                   $patch_owner = storage_path('app/public/') . $user_owner . '/photos/' .  $photo->url;
                   $img = Image::make($patch_owner);
                   $patch_user = storage_path('app/public/') . $uidUser. '/photos/';
                   File::exists($patch_user) or File::makeDirectory($patch_user, 0777, true, true);
                   $img->save($patch_user .  $photo->url);
                   $new = Photo::query()->create([
                          'user_uid' => $uidUser,
                          'moment' => Carbon::now(),
                          'url' =>  $photo->url,
                          'title' =>  'sin titulo',
                          'subtitle' =>  'sin subtitulo'
                   ]);
                  break;
              }
            $extendReminder->details()->create([
                'type' => $det['type'],
                'item_id' =>  $new->id
            ]);

         }
    }

    public function shareReminder(Request $request) {
        $extends = $request->userExtend;
        foreach ($request->sharelist as $userUid ) {
            $found = ReminderShare::query()->where('reminder_id', $request->item_id)
                ->where('to_user', $userUid)->first();
            if ($found === null) {
                ReminderShare::query()->create([
                    'reminder_id' => $request->item_id,
                    'to_user' => $userUid,
                    'from_user' => $request->user()->uid,
                    'extend' => in_array($userUid, $extends, true)
                ]);
                if (in_array($userUid, $extends, true)) {
                    $this->saveShareUser($userUid, $request->item_id, $request->user()->uid);
                }
            }
        }
        return http_response_code(200);
    }

    public function OffNotyReminder(Request $request) {
        $notification = Notification::query()->find($request->notification);
        $notification->update(['status_id' =>  2]);  // LEIDO
        return http_response_code(200);
    }
}
