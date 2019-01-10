<?php

namespace App\Console;

use App\Jobs\Media\Instagram\GetInstagramPosts;
use App\Jobs\Media\Vk\GetVkPosts;
use App\Jobs\Media\Vk\UpdatePublishedVkPosts;
use App\Jobs\Media\Instagram\UpdatePublishedInstagramPosts;
use App\Jobs\Media\Youtube\GetYoutubeVideos;
use App\Jobs\Media\Youtube\UpdatePublishedYoutubeVideos;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
			Commands\debug::class,
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule) {
		// $schedule->command('inspire')
		//          ->hourly();

		//$schedule->job(new GetVkPosts(true, true))
		//		->everyMinute();
		$schedule->job(new UpdatePublishedVkPosts(true, true))
				->everyMinute();

		//$schedule->job(new GetInstagramPosts(true, true))
		//		->everyMinute();
		//$schedule->job(new UpdatePublishedInstagramPosts(true, true))
		//		->everyTenMinutes();

		//$schedule->job(new GetYoutubeVideos(true, true))
		//	->everyMinute();
		//$schedule->job(new UpdatePublishedYoutubeVideos(true, true))
		//	->everyMinute();
		//$schedule->job(new UpdatePublishedYoutubeVideos(true, true))
		//	->everyTenMinute();
	}

	/**
	 * Register the commands for the application.
	 *
	 * @return void
	 */
	protected function commands() {
		$this->load(__DIR__ . '/Commands');

		require base_path('routes/console.php');
	}
}
