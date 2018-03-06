<?php namespace NGAFID\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'NGAFID\Console\Commands\Inspire',
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		//$schedule->command('inspire')
		//		 ->hourly();

        //automatically handle web import when called by CRON job
        $result = \DB::select("SELECT count(`id`) AS 'count' FROM fdmdm2.`jobs`");
        $count = $result[0]->count;

        for($i = 0; $i < $count; $i++){
            $schedule->command('queue:work')->withoutOverlapping();//->everyFiveMinutes();
        }
	}

}
