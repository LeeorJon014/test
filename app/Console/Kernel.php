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
        // $schedule->command('inspire')->hourly();
        $schedule->command('update:property-status')->daily(); // update the property status to Published in Site if it is validated fro 30 days or more
        
        //once the system is running in the server, 
        //connect to the server then run the command 
        //crontab -e
        //it will open a text editor, then paste the command below:
        //* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
        //click save, then the scheduler will now run.

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
