<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\ExpireDeadlinedOrders::class,
        Commands\OrderSearchForDriver::class,
        Commands\OrderChangeStatusIfPaided::class,
        Commands\GetTodayImageFromAppServer::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
         $schedule->command('app:delete-expired-otps')->daily();
         $schedule->command('app:expire:orders');
        //  $schedule->command('app:order-search-for-driver')->everyTwoMinutes();
        $schedule->command('app:order-change-status-if-paided')->everyFiveMinutes();
         $schedule->command('app:order-search-for-driver')->everyThreeMinutes();
        //  $schedule->command('app:order-search-for-driver')->cron(\Carbon\Carbon::now()->addMinutes(2)->toCron());
         $schedule->command('app:get-today-image-from-app-server')->dailyAt('14:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        $this->load(base_path('/app/Domain/Otp/Commands'));

        require base_path('routes/console.php');
    }
}
