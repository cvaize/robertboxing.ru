<?php

namespace App\Console\Commands;


use App\Models\Media\Instagram\InstagramPost;
use App\Models\Media\Youtube\YoutubeVideo;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use InstagramAPI\Exception\InstagramException;
use InstagramAPI\Response\Model\Item;

class debug extends Command {
	use DispatchesJobs;
	/**
	 * @var string
	 * @since version
	 */
	protected $signature = 'debug';

	/**
	 * @var string
	 * @since version
	 */
	protected $description = 'Command description';

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
	protected $postsInst;

	/**
	 * @since debug constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * @param YoutubeVideo $video
	 */
	public function handle(InstagramPost $posts) {
		$this->postsInst = $posts;
		//$job = new GetVkPosts(true, true);
		//$this->dispatchNow($job);

		//$sss = new GetInstagramPosts(true, true);
		//dump($sss->handle($post));

		$getInstagramPosts = array_reverse($this->getPosts(), true);
		foreach ($getInstagramPosts as $post) {
			/**
			 * @var Item $post
			 */
			$url = $post->getImageVersions2()->getCandidates()[0]->getUrl();
			dump($post->getId() . ': ' . $this->getCodeLink($url));
			dump('NOW:');
			dump($url);
			dump('DataBase:');
			$firstFrame = $this->postsInst::where('post_id', '=', $post->getId())->get(['payload'])[0]->getPayload()['media'][0]['first_frame'] ?? null;
			dump($firstFrame);
			dump('-------------------');
		}
	}

	/**
	 * @return \InstagramAPI\Instagram|null
	 */
	public function getClient() {
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
	 * @return array|null
	 */
	public function getPosts() {
		try {
			$client = $this->getClient();
			if (null !== $client) {
				$userId = $client->people->getUserIdForName('_r_robert_r_');
				$maxId = null;
				$response = $client->timeline->getUserFeed($userId, $maxId);
				$items = $response->getItems();
			}
		} catch (InstagramException $exception) {
			Log::critical('method getPost failed', ['message' => $exception->getMessage(), 'line' => $exception->getLine(), 'code' => $exception->getCode()]);
		}

		return $items ?? null;
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