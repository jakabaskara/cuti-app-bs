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
        // Sync employee data from API every night at midnight
        $schedule->command('employees:sync')
            ->everyMinute('00:00')
            ->onSuccess(function () {
                \Log::info('Employee sync scheduled task completed successfully');
            })
            ->onFailure(function () {
                \Log::error('Employee sync scheduled task failed');
            });
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
