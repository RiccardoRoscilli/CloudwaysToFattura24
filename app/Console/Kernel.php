<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // Crea ordini trimestrali il primo giorno del trimestre alle 00:00
        $schedule->command('orders:create-quarterly')->quarterlyOn(1, '00:00');
        
        // Invia report Fattura24 il primo giorno del trimestre alle 01:00 (dopo la creazione degli ordini)
        $schedule->command('fattura24:send-daily-report')->quarterlyOn(1, '01:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
