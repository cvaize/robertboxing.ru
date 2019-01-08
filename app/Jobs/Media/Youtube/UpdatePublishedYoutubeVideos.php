<?php

namespace App\Jobs\Media\Youtube;

use App\Models\Media\Youtube\YoutubeVideo;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdatePublishedYoutubeVideos implements ShouldQueue {
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * @var bool
	 */
	protected $debug;

	/**
	 * @var bool
	 */
	protected $cache;

	/**
	 * @var
	 */
	protected $videos;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(bool $debug, bool $cache) {
		$this->debug = $debug;
		$this->cache = $cache;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle(YoutubeVideo $youtubeVideos) {
		$this->videos = $youtubeVideos;
		$logMessage = '';
		$logMessage .= Carbon::now()->toDayDateTimeString() . ' UpdatePusblishedYoutubeVideos: ';
		$publishedVideos = $this->videos->published()->get();

		foreach ($publishedVideos as $publishedVideo) {
			$publishedVideo->updateFromYoutube();
		}
	}
}
