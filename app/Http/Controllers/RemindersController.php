<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReminderResource;
use App\Models\Reminder;
use Illuminate\Http\Request;

class RemindersController extends Controller
{
    public function getList(Request $request) {

        $data = Reminder::query()
            ->where('user_uid', $request->user()->uid)
            ->get();

        return ReminderResource::collection($data);
    }

    public function ReminderDelete(Request $request) {
        Reminder::query()
            ->where('id', $request->id)
            ->delete();
        return http_response_code(200);
    }

    public function saveReminder(Request $request) {
        Reminder::query()
            ->create([
                'user_uid' => $request->user()->uid,
                'moment' => $request->moment,
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'note' => $request->note,
                'type' => 1,
                'item_id' => $request->item_id,
                'recurrent' => $request->recurrent
            ]);

        $data = Reminder::query()
            ->where('user_uid', $request->user()->uid)
            ->get();

        return  ReminderResource::collection($data);
    }

    public function updateReminder(Request $request) {
        Reminder::query()->where('id', $request->id)
            ->update([
                'moment' => $request->moment,
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'note' => $request->note,
                'type' => 1,
                'item_id' => $request->item_id,
                'recurrent' => $request->recurrent
            ]);

        $data = Reminder::query()
            ->where('user_uid', $request->user()->uid)
            ->get();

        return  ReminderResource::collection($data);
    }
}
