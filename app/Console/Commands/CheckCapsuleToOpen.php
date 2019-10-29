<?php

namespace App\Console\Commands;

use App\Events\NotificationEvent;
use App\Http\Resources\Notify;
use App\Jobs\SendEmailJob;
use App\Mail\NotificationConstableCapsuleInTerm;
use App\Models\Capsule;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckCapsuleToOpen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check-cap-open';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checar si la capsula procede para su apertura';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $capsulas = Capsule::query()->with( 'user', 'constables')
            ->where('capsules.activate', 1)
            ->where('capsules.securitys', 1)
            ->whereRaw('DATEDIFF(now(), capsules.opendate) = 0')
            ->get();

        // Log::alert($capsulas);

      // Informar a alguaceas
      foreach ($capsulas as $capsula) {

           foreach ($capsula->constables as $constable) {
                $UserConstable = User::query()->find($constable->user_uid);

                $info = 'Ha creado un capsula de la cual te a nombrado albacea, esta ya esta en termino!';

                $data_email = [
                    'from' => $capsula->user->full_names,
                    'to' => $UserConstable->full_names,
                    'note' => $info,
                    'url_to_response' => 'http://socialdead.es/#/capules'
                ];

                $data = Notification::query()->create([
                    'type_id' => 7,  // NOTIFICACION DE ALBACEA PARA APETURA
                    'moment' => Carbon::now(),
                    'from_user' => $capsula->user->uid,
                    'to_user' => $constable->user_uid,
                    'note' =>  $info,
                    'status_id' => 1, // NO VISTO
                    'data' => $capsula->id
                ]);

               broadcast(new NotificationEvent($constable->user_uid, new Notify($data)))->toOthers();

               SendEmailJob::dispatch($UserConstable->email, new NotificationConstableCapsuleInTerm($data_email))->onConnection('mails');
            }
           $capsula->activate = 2; // INFOMADO AL ALBACEA Y ESPERANDO AUTORIZACION
           $capsula->save();
        }

    }
}
