<?php

namespace App\Jobs\Media\Instagram;

use App\Models\Media\Instagram\InstagramPost;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdatePublishedInstagramPosts implements ShouldQueue {
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
	protected $instagramPosts;

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
	 * @param InstagramPost $instagramPosts
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function handle(InstagramPost $instagramPosts) {
		$logMessage = '';
		$logMessage .= Carbon::now()->toDayDateTimeString() . ' UpdatePusblishedInstPosts: ';

		$this->$instagramPosts = $instagramPosts;
		foreach ($this->$instagramPosts->all() as $post) {
			/**
			 * @var InstagramPost $post
			 */

			$post->getMedia();

			//$result = $post->updateFromInstagram();
			//$logMessage .= $post->getPostId() . '-' . $result . ' ';
		}

		dump($logMessage);
	}

}
