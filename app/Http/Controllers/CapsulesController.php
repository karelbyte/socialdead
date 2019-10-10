<?php

namespace App\Http\Controllers;

use App\Http\Resources\CapsuleUserResource;
use App\Http\Resources\ReminderResource;
use App\Jobs\SendEmailJob;
use App\Mail\UserNotificationRecurrent;
use App\Models\Capsule;
use App\Models\Reminder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CapsulesController extends Controller
{
    public function getList(Request $request) {
        $data = Capsule::query()->with('details', 'constables', 'shares', 'emails')
            ->where('user_uid', $request->user()->uid)
            ->orderBy('moment', 'desc')
            ->get();
        // return $data;
        return CapsuleUserResource::collection($data);
    }

    public function save(Request $request) {
        $item = $request->all();
        $capsule = Capsule::query()
            ->create([
                'user_uid' => $request->user()->uid,
                'moment' => Carbon::now(),
                'opendate' => date('Y-m-d', strtotime($item['moment'])),
                'title' => $item['title'],
                'subtitle' => $item['subtitle'],
                'note' => $item['note'],
                'recurrent' => $item['recurrent'],
                'security' => 1,
            ]);

        $capsule->constables()->create([
           'user_uid' => $item['constable1'],
           'key' => Str::random(5)
        ]);
        if ($item['constable2'] !== null && $item['constable2'] !== '') {
            $capsule->constables()->create([
                'user_uid' => $item['constable1'],
                'key' => Str::random(5)
            ]);
        }

        foreach ($item['images'] as $image) {
            $capsule->details()->create([
                'type' => 3, //FOTOS
                'item_id' => $image
            ]);
        }

        foreach ($item['medias'] as $media) {
            $capsule->details()->create([
                'type' => $media['type'],
                'item_id' => $media['id']
            ]);
        }


        foreach ($item['emails'] as $email) {
            if ($email['value'] !== null) {
               $capsule->emails()->create([
                    'email' => $email['value'],
                    'token' => Str::uuid()->toString() . Carbon::now()->timestamp,
                    'status_id' => 1
                ]);
            }
        }

        foreach ($item['users'] as $userUid ) {
                $capsule->shares()->create([
                    'to_user' => $userUid,
               ]);
        }

        return  $capsule;
    }


    public function delete(Request $request) {
        Capsule::query()
            ->where('id', $request->id)
            ->delete();
        return http_response_code(200);
    }
}
