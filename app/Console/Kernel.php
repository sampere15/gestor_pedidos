<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        //  Comprobación de si hay pedidos pendientes de cursar
        $schedule->call(function()
        {
            app('App\Http\Controllers\UtilController')->comprobarpedidospendientescursar();
        })->dailyAt("08:00");

        $schedule->call(function()
        {
            app('App\Http\Controllers\UtilController')->comprobarpedidospendientescomunicar();
        })->cron("0 8,10,12,14,16 * * *");
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
