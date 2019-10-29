<?php

namespace App\Console\Commands;

use App\Jobs\SendEmailJob;
use App\Mail\UserNotificationRecurrent;
use App\Models\Reminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MailReminderYearly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:reminder-yearly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $remiders = Reminder::query()->with('emails', 'user')
            ->where('recurrent', 1)
            ->whereRaw('datediff(now(), moment) = 365')->get();
        foreach ($remiders as $remider) {
          foreach ($remider['emails'] as $email) {
                if ($email['status_id'] === 1) { // ACTIVO
                    $data_email = [
                        'from' => $remider->user->full_names,
                        'to' => $email['email'],
                        'title' => $remider['title'],
                        'subtitle' => $remider['subtitle'],
                        'note' => $remider['note'],
                        'moment' =>  date('Y-m-d', strtotime($remider['moment'])) . ' --- ' . Carbon::parse($remider['moment'])->diffForHumans(),
                        'url_to_cancel' => 'http://core.socialdead.es/recuerdos/remover/' .$email['token'],
                        // 'url_to_cancel' => 'http://socialdead.jet/recuerdos/remover/' . $remi->token,
                        'url_to_register' => 'http://socialdead.es'
                    ];
                    // enviar correo
                    SendEmailJob::dispatch($email['email'], new UserNotificationRecurrent($data_email))->onConnection('mails');
                }
            }
        }
    }
}
