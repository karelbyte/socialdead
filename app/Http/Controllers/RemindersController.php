<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReminderResource;
use App\Models\Reminder;
use App\Models\ReminderShare;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RemindersController extends Controller
{
    public function getList(Request $request) {

        $data = Reminder::query()->with('audios', 'medias')
            ->where('user_uid', $request->user()->uid)
            ->get();
         //  return    $data;
        return ReminderResource::collection($data);
    }

    public function ReminderDelete(Request $request) {
        Reminder::query()
            ->where('id', $request->id)
            ->delete();
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

    public function saveShareUser ($uidUser, $idReminder) {
         $reminder = Reminder::query()->find($idReminder);
         $extendReminder = $reminder->replicate();
         $extendReminder->user_uid = $uidUser;
         $extendReminder->extend = false;
         $extendReminder->save();
         $extendReminder->details()->attach($reminder->details);
    }
    public function shareReminder(Request $request) {
        foreach ($request->sharelist as $userUid ) {
            ReminderShare::query()->create([
                'reminder_id' => $request->item_id,
                'to_user' => $userUid,
                'from_user' =>  $request->user()->uid,
            ]);
            if  ($request->extend) {
                $this->saveShareUser($userUid, $request->item_id);
            }
        }
        return http_response_code(200);
    }
}
