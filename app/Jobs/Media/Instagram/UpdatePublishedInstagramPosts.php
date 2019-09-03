<?php

namespace App\Jobs\Media\Instagram;

use App\Models\Media\Instagram\InstagramPost;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use InstagramAPI\Exception\InstagramException;

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
	protected $client;

	/**
	 * @var
	 */
	protected $clientGuzzle;

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
		$this->instagramPosts = $instagramPosts;
		//$lastTenPosts = $this->instagramPosts->posts()->take(50)->get();
		//
		//foreach ($lastTenPosts as $post) {
		//	/**
		//	 * @var InstagramPost $post
		//	 */
		//	$result = $post->updateFromInstagram();
		//	$logMessage .= $post->getId() . '-' . $result . ' ';
		//}

		//dd($lastTenPosts);

		$logMessage .= $this->getPostInstagram();

		dump($logMessage);
	}

	/**
	 * @return \InstagramAPI\Instagram
	 */
	private function getClient(): \InstagramAPI\Instagram {
		if (null === $this->client) {
			$username = env("INSTAGRAM_LOGIN");
			$password = env("INSTAGRAM_PASSWORD");

			$debug = false;
			$truncatedDebug = false;

			$this->client = new \InstagramAPI\Instagram($debug, $truncatedDebug);

			try {
				$this->client->login($username, $password);
			} catch (\InstagramAPI\Exception\InstagramException $exception) {
				Log::critical('method getClient failed', ['message' => $exception->getMessage(), 'line' => $exception->getLine(), 'code' => $exception->getCode()]);
			}
		}

		return $this->client ?? null;
	}

	/**
	 * @return Client
	 */
	public function getClientGuzzle(): Client {
		if (null === $this->clientGuzzle) {
			$this->clientGuzzle = new Client([
					'timeout' => 5,
					'http_errors' => false
			]);
		}

		return $this->clientGuzzle;
	}

	/**
	 * @return string
	 */
	private function getPostInstagram(): string {
		$client = $this->getClient();
		$result = 'Update Instagram posts (id-result): ';

		if (null !== $client) {

			$userId = $client->people->getUserIdForName('_r_robert_r_');
			// Starting at "null" means starting at the first page.
			$maxId = null;

			$countPages = 0;
			do {
				// Request the page corresponding to maxId.
				$response = $client->timeline->getUserFeed($userId, $maxId);

				// In this example we're simply printing the IDs of this page's items.
				foreach ($response->getItems() as $post) {
					$payload = [];
					$postId = $post->getId();
					$postUrl = $post->getItemUrl();
					$postedAt = $post->getDeviceTimestamp();

					/**
					 * @var InstagramPost $postSaved
					 */
					$postSaved = $this->instagramPosts->where('post_id', '=', $postId)->get()[0];
					$result .= $postSaved->getId();

					$payload['media'] = [];

					if ($post->isCarouselMedia()) {
						foreach ($post->getCarouselMedia() as $media) {
							if ($media->isVideoVersions()) {
								$video = [
										'first_frame' => $media->getImageVersions2()->getCandidates()[0]->getUrl(),
										'url' => $media->getVideoVersions()[0]->getUrl(),
										'isImage' => false,
										'isVideo' => true
								];
								$payload['media'][] = $video;
							} else {
								$image = [
										'url' => $media->getImageVersions2()->getCandidates()[0]->getUrl(),
										'isImage' => true,
										'isVideo' => false
								];
								$payload['media'][] = $image;
							}
						}
					} elseif ($post->isVideoVersions()) {
						$video = [
								'first_frame' => $post->getImageVersions2()->getCandidates()[0]->getUrl(),
								'url' => $post->getVideoVersions()[0]->getUrl(),
								'isImage' => false,
								'isVideo' => true
						];
						$payload['media'][] = $video;
					} else {
						$image = [
								'url' => $post->getImageVersions2()->getCandidates()[0]->getUrl(),
								'isImage' => true,
								'isVideo' => false
						];
						$payload['media'][] = $image;
					}

					if ($post->isCaption()) {
						$payload['caption'] = $post->getCaption()->getText();
					} else {
						$payload['caption'] = '';
					}

					$updateResult = $postSaved->updateFromInstagram($payload);
					$result .= '-' . $updateResult . ' ';
				}
				// Now we must update the maxId variable to the "next page".
				// This will be a null value again when we've reached the last page!
				// And we will stop looping through pages as soon as maxId becomes null.
				$maxId = $response->getNextMaxId();
				// Sleep for 5 seconds before requesting the next page. This is just an
				// example of an okay sleep time. It is very important that your scripts
				// always pause between requests that may run very rapidly, otherwise
				// Instagram will throttle you temporarily for abusing their API!
				sleep(5);
				$countPages++;
			} while ($maxId !== null && $countPages < 3); // Must use "!==" for comparison instead of "!=".
		}

		return $result;
	}

	/**
	 * Get status code of post: exist or not
	 *
	 * @param string $link
	 * @return int
	 */
	private function getCodeLink(string $link): int {
		$guzzleClient = $this->getClientGuzzle();
		try {
			$response = $guzzleClient->head($link);
			$code = $response->getStatusCode();
		} catch (ClientException $exception) {
			$code = $exception->getCode();
		}

		return $code;
	}

}
