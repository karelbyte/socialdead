<?php

namespace App\Console\Commands;

use App\Mail\UserAccountConfirm;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class UserMailToConfirmAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:email-reconfirm';

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
        // RENVIANDO NOTIFICACION DE CONFIRMACION
        $user_to_notify = User::query()->whereNull('email_verified_at')
            ->whereRaw('datediff(now(), created_at) >= 8' )
            ->get();
        foreach ($user_to_notify as $user) {

            $mail_data = [
                'user_name' => $user->full_names,
                'user_email' => $user->email,
                'url_confirm' => url('/'). '/confirmacion-de-cuenta/' . $user->secret
            ];
            Mail::to($user->email)->send(new UserAccountConfirm($mail_data));
        }
    }
}
