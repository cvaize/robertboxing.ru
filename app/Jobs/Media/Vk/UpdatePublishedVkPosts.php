<?php

namespace App\Jobs\Media\Vk;

use App\Models\Media\Vk\VkPost;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class UpdatePublishedVkPosts implements ShouldQueue {
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
	 * UpdatePublishedVkPosts constructor.
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
		$logMessage = '';
		$logMessage .= Carbon::now()->toDayDateTimeString() . ' UpdatePusblishedVkPosts: ';

		$this->vkPosts = $vkPosts;
		foreach ($this->vkPosts->published() as $item) {
			if (0 !== count($item)) {
				/**
				 * @var VkPost $post
				 */

				foreach ($item as $post) {
					$logMessage .= $post->getPostId() . '-' . $post->updateFromVk() . ' ';
				}
			}

			//dump($item->getPostId() );
			//dump('Updating post with id ' . $item->getPostId() . ': ' . $item->updateFromVk());
		}

		dump($logMessage);
	}
}
