<?php

namespace App\Console\Commands;

use App\Mail\UserAccountConfirm;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DeleteOutTimeAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:eraser';

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
        // ELIMINAR CUENTAS PASADO 10 DIAS
         User::query()->whereNull('email_verified_at')
            ->whereRaw('datediff(now(), created_at) >= 10' )
            ->delete();
    }
}
