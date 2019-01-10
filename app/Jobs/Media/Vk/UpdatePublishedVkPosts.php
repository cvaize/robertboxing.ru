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

	/**
	 * @var
	 */
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
	 * @param VkPost $vkPosts
	 * @throws \Exception
	 */
	public function handle(VkPost $vkPosts) {
		$logMessage = '';
		$logMessage .= Carbon::now()->toDayDateTimeString() . ' UpdatePusblishedVkPosts: ';

		$this->vkPosts = $vkPosts;
		$lastTenPosts = $this->vkPosts->orderByDesc('id')->take(10)->get();

		foreach ($lastTenPosts as $post) {
			//$logMessage .= $post->getPostId() . '-' . $post->updateFromVk() . ' ';

			$post->updateFromVk();
		}
		dd('11111');
		dump($logMessage);
	}
}
