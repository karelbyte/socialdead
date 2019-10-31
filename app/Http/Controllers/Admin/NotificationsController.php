<?php

namespace App\Http\Controllers\Admin;

use App\Events\NotificationEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Notify;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{

    public function store(Request $request) {

        $moment = Carbon::now();

        // Guardando notificacion en la db
        $data = Notification::query()->create([
            'type_id' => $request->type,
            'moment' => $moment,
            'from_user' => $request->user()->uid,
            'to_user' => $request->uid,
            'note' => $request->msj,
            'status_id' => 1
        ]);

        // ENVIANDO NOTIFICACION POR EMAIL SI ESTA ACTIVA ESA CONDICION
        /*
        if ($data->toUser->settingNotifications->notification_email === 1) {
            $data_email = [
                'from' => $data->fromUser->full_names,
                'to' => $data->toUser->full_names,
                'note' => $request->msj
            ];
            SendEmailJob::dispatch($data->touser->email, new UserNotification($data_email))->onConnection('mails');
        }*/
        broadcast(new NotificationEvent($request->uid, new Notify($data)))->toOthers();

        return response()->json('Se envió la notificación!');
    }

}
