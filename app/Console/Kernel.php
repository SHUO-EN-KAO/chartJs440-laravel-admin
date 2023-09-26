<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        $id = 'NBS';
        $date = date('Y-m-d');

        // php artisan schedule:work
        $schedule->exec(
            "php artisan command_newUserApiData:store --id=$id --date=$date"
        )->everyMinute();

        $schedule->exec(
            "php artisan command_userPaymentApiData:store --id=$id --date=$date"
        )->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    protected $commands = [
        \App\Console\Commands\Command_newUserApiDataStore::class,
        \App\Console\Commands\Command_userPaymentApiDataStore::class,

    ];
}
