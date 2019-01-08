<?php

namespace App\Jobs\Media\Instagram;

use App\Models\Media\Instagram\InstagramPost;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use PhpParser\Builder;
use Vinkla\Instagram\Instagram;
use Vinkla\Instagram\InstagramException;

class GetInstagramPosts implements ShouldQueue {
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * @var bool
	 */
	private $debug;

	/**
	 * @var bool
	 */
	private $cache;

	/**
	 * @var
	 */
	private $client;

	/**
	 * @var
	 */
	private $instagramPosts;

	/**
	 * @var int
	 */
	protected $countPosts = 3;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(bool $debug = false, bool $cache = false) {
		$this->debug = $debug;
		$this->cache = $cache;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle(InstagramPost $instagramPosts) {
		$this->instagramPosts = $instagramPosts;
		$getInstagramPosts = array_reverse($this->getPosts(), true);
		$logMessage = '';

		$logMessage .= Carbon::now()->toDayDateTimeString() . ' Get instagram posts: ';


		foreach ($getInstagramPosts as $getInstagramPost) {
			$getInstagramPost = json_decode(json_encode($getInstagramPost), true);
			$payload = [];

			if (0 !== count($this->instagramPosts::where('post_id', $getInstagramPost['id'])->get()))
				continue;

			$payload['media'] = ['images' => [], 'videos' => []];

			if ('carousel' === $getInstagramPost['type']) {
				foreach ($getInstagramPost['carousel_media'] as $media) {
					if ('image' === $media['type']) {
						$image = $media['images']['standard_resolution'];
						array_push($payload['media']['images'], $image);
					} elseif ('video' === $media['type']) {
						$video = $media['videos']['standard_resolution'];
						array_push($payload['media']['videos'], $video);
					}
				}
			} elseif ('video' === $getInstagramPost['type']) {
				$video = $getInstagramPost['videos']['standard_resolution'];
				array_push($payload['media']['videos'], $video);
			} elseif ('image' === $getInstagramPost['type']) {
				$image = $getInstagramPost['images']['standard_resolution'];
				array_push($payload['media']['images'], $image);
			}

			if (null !== $getInstagramPost['caption']) {
				$payload['caption'] = $this->getCaptionText($getInstagramPost['caption']['text']);
			} else {
				$payload['caption'] = '';
			}

			if (0 !== count($getInstagramPost['tags'])) {
				$payload['tags'] = $this->getHashtags($getInstagramPost['tags']);
			} else {
				$payload['tags'] = '';
			}

			$logMessage .= $getInstagramPost['link'] . ' ';

			$frd = ['post_id' => $getInstagramPost['id'], 'link' => $getInstagramPost['link'], 'posted_at' => $getInstagramPost['created_time'], 'is_deleted' => 0, 'payload' => $payload];
			$this->instagramPosts->create($frd);

		}

		dump($logMessage);
	}

	/**
	 * @param array $tagsArray
	 * @return string
	 */
	private function getHashtags(array $tagsArray): string {
		$tags = '';
		foreach ($tagsArray as $tag) {
			$tags .= '#' . $tag . ' ';
		}
		trim($tags);

		return $tags;
	}

	/**
	 * @param string $text
	 * @return string
	 */
	private function getCaptionText(string $text): string {
		$captionText = preg_replace('/#\S*\w/iu', '', $text);
		$captionText = trim($captionText);

		return $captionText;
	}

	/**
	 * @return null|Instagram
	 */
	public function getClient() {
		if (null === $this->client) {
			$token = $this->getToken();
			if (null !== $token) {
				$this->client = new Instagram($token . '&count=' . $this->getCountPosts());
			}
		}
		return $this->client ?? null;
	}

	/**
	 * @return mixed|null
	 */
	public function getToken() {
		return env('INSTAGRAM_API') ?? null;
	}

	/**
	 * @return array|null
	 */
	public function getPosts() {
		try {
			$client = $this->getClient();
			if (null !== $client) {
				$response = $client->media();
				//$result = json_decode($response, true);
			}
		} catch (InstagramException $exception) {
			Log::critical('method getPost failed', ['message' => $exception->getMessage(), 'line' => $exception->getLine(), 'code' => $exception->getCode()]);
		}

		return $response ?? null;
	}

	/**
	 * @return int
	 */
	public function getCountPosts(): int {
		return $this->countPosts;
	}
}
