<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // CREANDO UN USUARIO DE PRUEBA
        /*for ($i=1930; $i<=2915; $i++) {
            \App\Models\User::query()->create([
                'full_names' => 'karel puerto diaz',
                'email' => 'karelpuerto'. $i .'@gmail.com',
                'password' => \Illuminate\Support\Facades\Hash::make('12345'),
                'sex_id' => 1,
                'secret' => \Illuminate\Support\Str::random(25),
                'birthdate' => $i.'-10-07'
            ]);
        }*/

        $users =  \App\Models\User::query()->where('uid', '<>', 'e0a026e1-5dd7-4e60-83ca-c9d8ef374182')->get();

        foreach ($users as $user) {
            $moment = \Carbon\Carbon::now();
            // CONPROBANDO POLITICAS DE ENVIO
            if (\App\Models\Notification::isFeasibleToNotify($user->uid, 'e0a026e1-5dd7-4e60-83ca-c9d8ef374182', $moment))
                return response()->json('Se bloqueo la notificación por exceder las políticas de envió 
            de notificaciones diarias al mismo destinatario!');

            // Guardando notificacion en la db
            $data = \App\Models\Notification::query()->create([
                'type_id' => 1,
                'moment' => $moment,
                'from_user' => $user->uid,
                'to_user' => 'e0a026e1-5dd7-4e60-83ca-c9d8ef374182',
                'note' => 'esto es una prueba',
                'status_id' => 1 // NO VISTO
            ]);
            // ENVIANDO NOTIFICACION POR EMAIL SI ESTA ACTIVA ESA CONDICION
           /* if ($data->toUser->settingNotifications->notification_email === 1) {
                $data_email = [
                    'from' => $data->fromUser->full_names,
                    'to' => $data->toUser->full_names,
                    'note' => 'esto es una prueba'
                ];
                \Illuminate\Support\Facades\Mail::to($data->touser->email)->send(new \App\Mail\UserNotification($data_email));
            }*/

            broadcast(new \App\Events\NotificationEvent('e0a026e1-5dd7-4e60-83ca-c9d8ef374182', new \App\Http\Resources\Notify($data)))->toOthers();
        }


    }
}
