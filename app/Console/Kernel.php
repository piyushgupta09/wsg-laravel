<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Clean the temp directory daily
        $schedule->command('clean:temp')->daily();

        // Daily backup at 1 AM
        $schedule->command('backup:run')->dailyAt('01:00');

        // Weekly backup at 2 AM on Sundays
        $schedule->command('backup:run')->weeklyOn(0, '02:00');

        // Monthly backup at 3 AM on the first day of the month
        $schedule->command('backup:run')->monthlyOn(1, '03:00');
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
