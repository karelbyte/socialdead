<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use App\Http\Resources\NotifyFull;
use App\Http\Resources\NotifySettings;
use App\Jobs\SendEmailJob;
use App\Mail\UserNotification;
use App\Models\Notification;
use App\Http\Resources\Notify;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{

    public function offAllNotification(Request $request) {
        $request->user()->notifications()->where('status_id', 1)->update(['status_id' => 3]);
        return http_response_code(200);
    }

    public function offNotification(Request $request) {
        $request->user()->notifications()->where('id', $request->notification)->update(['status_id' => 3]);
        return http_response_code(200);
    }

    public function getNotificatios(Request $request) {
        $notifications =  $request->user()->notifications;
        $data = [
            'notifications' => Notify::collection($notifications->take(5)),
            'cant' =>  count($request->user()->notifications)
        ];
        return $data;
    }

    public function getNotificatiosAll(Request $request) {
        return NotifyFull::collection($request->user()->notificationsAll);
    }

    public function store(Request $request) {
        $moment = Carbon::now();
        // CONPROBANDO POLITICAS DE ENVIO
        if (Notification::isFeasibleToNotify($request->user()->uid, $request->uid, $moment))
            return response()->json('Se bloqueo la notificación por exceder las políticas de envió 
            de notificaciones diarias al mismo destinatario!');

        // Guardando notificacion en la db
        $data = Notification::query()->create([
            'type_id' => $request->type,
            'moment' => $moment,
            'from_user' => $request->user()->uid,
            'to_user' => $request->uid,
            'note' => $request->msj,
            'status_id' => 1 // NO VISTO
        ]);
        // ENVIANDO NOTIFICACION POR EMAIL SI ESTA ACTIVA ESA CONDICION
        
        if ($data->toUser->settingNotifications->notification_email === 1) {
            $data_email = [
                'from' => $data->fromUser->full_names,
                'to' => $data->toUser->full_names,
                'note' => $request->msj
            ];
            SendEmailJob::dispatch($data->touser->email, new UserNotification($data_email))->onConnection('mails');
        }
        broadcast(new NotificationEvent($request->uid, new Notify($data)))->toOthers();
        return response()->json('Se envió la notificación!');
    }

    public function updateNotificationsSettings(Request $request) {
        $request->user()->settingNotifications()->update($request->all());
        return http_response_code(200);
    }

    public function getSettings(Request $request) {
        if ($request->user()->settingNotifications === null) {
            $request->user()->settingNotifications()->create([
                'notification_sound' => 1,
                'notification_email' => 1,
                'notification_reminders' => 1,
                'chat_sound' => 1,
            ]);
        }
        return new NotifySettings($request->user()->settingNotifications);
    }

    public function eraser(Request $request) {
        Notification::query()->where('id', $request->id)->delete();
        return NotifyFull::collection($request->user()->notificationsAll);
    }
}
