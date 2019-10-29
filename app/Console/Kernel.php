<?php

namespace App\Console;

use App\Console\Commands\CheckCapsuleToOpen;
use App\Console\Commands\DeleteChatMessageMore30Day;
use App\Console\Commands\DeleteOutTimeAccount;
use App\Console\Commands\MailReminderYearly;
use App\Console\Commands\UserMailToConfirmAccount;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use SebastianBergmann\Environment\Console;
use function Psy\debug;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        DeleteChatMessageMore30Day::class,
        DeleteOutTimeAccount::class,
        UserMailToConfirmAccount::class,
        MailReminderYearly::class,
        CheckCapsuleToOpen::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command( DeleteChatMessageMore30Day::class)->dailyAt('1:00')->timezone('Europe/Madrid');
         $schedule->command( DeleteOutTimeAccount::class)->dailyAt('2:00')->timezone('Europe/Madrid');
         $schedule->command( UserMailToConfirmAccount::class)->dailyAt('3:00')->timezone('Europe/Madrid');
         $schedule->command( MailReminderYearly::class)->dailyAt('4:00')->timezone('Europe/Madrid');
         $schedule->command( CheckCapsuleToOpen::class)->dailyAt('5:00')->timezone('Europe/Madrid');
         $schedule->command('queue:work mails --queue=mails')->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
