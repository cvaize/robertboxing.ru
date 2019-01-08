<?php

namespace App\Jobs;

use App\Models\Media\Vk\VkPost;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class UpdatePublishedPosts implements ShouldQueue {
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * @var bool
	 */
	protected $debug;

	/**
	 * @var bool
	 */
	protected $cache;

	protected $vkPosts;

	/**
	 * UpdatePublishedPosts constructor.
	 * @param bool $debug
	 * @param bool $cache
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
	public function handle(VkPost $vkPosts) {
		dump(Carbon::now()->toDayDateTimeString());
		dump('UpdatePusblishedPosts started');

		$this->vkPosts = $vkPosts;
		foreach ($this->vkPosts->published() as $item) {
			if (0 !== count($item)) {
				/**
				 * @var VkPost $post
				 */

				foreach ($item as $post) {
					dump('Updating post with id ' . $post->getPostId() . ': ' . $post->updateFromVk());
				}
			}

			//dump($item->getPostId() );
			//dump('Updating post with id ' . $item->getPostId() . ': ' . $item->updateFromVk());
		}

		dump('===========================');
	}
}
