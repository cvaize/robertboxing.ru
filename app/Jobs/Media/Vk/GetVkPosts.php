<?php

namespace App\Jobs\Media\Vk;

use App\Models\Media\Vk\VkPost;
use ATehnix\VkClient\Client;
use ATehnix\VkClient\Exceptions\VkException;
use ATehnix\VkClient\Requests\Request;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GetVkPosts implements ShouldQueue {
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
	private $vkPosts;

	/**
	 * @var int
	 */
	protected $vkGroupId = -174135295;

	/**
	 * @var string
	 */
	protected $vkGroupName = 'robertboxing';

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
	 * @param VkPost $vkPosts
	 */
	public function handle(VkPost $vkPosts) {
		$this->vkPosts = $vkPosts;
		$idPinnedPost = null;
		$hasPinned_needToUpdate = false;
		$hasntPinned_needToUpdate = false;
		$indexPosts = 0;
		$getVkPosts = array_reverse($this->getPosts()['response']['items'], true);

		$logMessage = '';

		$dateNow = Carbon::now()->toDayDateTimeString();
		$logMessage .= $dateNow . ' Get VK posts: ';

		foreach ($getVkPosts as $getVkPost) {
			$isPinned = $getVkPost['is_pinned'] ?? 0;
			if ($isPinned) {
				$idPinnedPost = $getVkPost['id'];
				$hasPinned_needToUpdate = true;
			} else {
				if (0 === $indexPosts) {
					$hasntPinned_needToUpdate = true;
				}
			}

			$logMessage .= $getVkPost['id'] . ' ';

			if (0 !== count($this->vkPosts::where('post_id', $getVkPost['id'])->get()))
				continue;

			if ($this->vkPosts->getVkGroupId() !== $getVkPost['from_id'])
				continue;

			if (array_key_exists('copy_history', $getVkPost)) {
				$indexAttachments = 0;
				foreach ($getVkPost['copy_history'][0]['attachments'] as $attachment) {
					if ('link' === $attachment['type']) {
						$url = $attachment['link']['url'];
						$parseLink = parse_url($url);
						if ('youtu.be' === $parseLink['host']) {
							$id = substr($parseLink['path'], 1);
							$isBlock = $this->vkPosts->isBlockedVideo($id);
							$getVkPost['copy_history'][0]['attachments'][$indexAttachments]['link']['is_blocked'] = $isBlock;

							if ($isBlock) {
								$link = '/' . preg_replace('/\//', '\/', $url) . '/';
								$getVkPost['copy_history'][0]['text'] = preg_replace($link, '', $getVkPost['copy_history'][0]['text']);
							}
						}
					}
					$indexAttachments++;
				}

				if ("" !== $getVkPost['copy_history'][0]['text']) {
					$getVkPost['copy_history'][0]['text'] = $this->replaceLinks($getVkPost['copy_history'][0]['text']);
					$getVkPost['copy_history'][0]['text'] = $this->replaceVkLinks($getVkPost['copy_history'][0]['text']);
				}

				$payload = ['post_author' => $getVkPost['from_id'], 'is_pinned' => $isPinned, 'copy_history' => 1, 'copy_history_data' => $getVkPost['copy_history']];
			} else {
				$indexAttachments = 0;
				foreach ($getVkPost['attachments'] as $attachment) {
					if ('link' === $attachment['type']) {
						$url = $attachment['link']['url'];
						$parseLink = parse_url($url);
						if ('youtu.be' === $parseLink['host']) {
							$id = substr($parseLink['path'], 1);
							$isBlock = $this->vkPosts->isBlockedVideo($id);
							$getVkPost['attachments'][$indexAttachments]['link']['is_blocked'] = $isBlock;

							if ($isBlock) {
								$link = '/' . preg_replace('/\//', '\/', $url) . '/';
								$getVkPost['text'] = preg_replace($link, '', $getVkPost['text']);
							}
						}
					}
					$indexAttachments++;
				}
				$getVkPost['text'] = $this->replaceLinks($getVkPost['text']);
				$getVkPost['text'] = $this->replaceVkLinks($getVkPost['text']);

				$payload = ['post_author' => $getVkPost['from_id'], 'is_pinned' => $isPinned, 'copy_history' => 0, 'post_attachments' => $getVkPost['attachments']];
			}

			$frd = ['post_id' => $getVkPost['id'], 'post_text' => $getVkPost['text'], 'posted_at' => $getVkPost['date'], 'is_deleted' => 0, 'payload' => $payload];
			$this->vkPosts->create($frd);
			$indexPosts++;
		}

		if ($hasPinned_needToUpdate || $hasntPinned_needToUpdate) {
			$this->vkPosts->pinned()->chunk(10, function ($posts) use ($idPinnedPost) {
				/**
				 * @var VkPost $post
				 */
				foreach ($posts as $post) {
					if ($post->{'post_id'} !== $idPinnedPost) {
						$post->setPayload('is_pinned', 0);
						$post->save();
					}
				}
			});

			$existPost = $this->vkPosts->exist($idPinnedPost)->get();
			if (0 !== count($existPost)) {
				foreach ($existPost as $post) {
					$post->setPayload('is_pinned', 1);
					$post->save();
				}
			}
		}

		dump($logMessage);

		//return view('posts.index');
	}

	/**
	 * @param string $text
	 *
	 * @return string
	 */
	public function replaceVkLinks(string $text): string {
		$countVkLinks = preg_match_all("/\[[^]]*\]/ui", $text, $vkLinks, PREG_PATTERN_ORDER);
		foreach ($vkLinks[0] as $vkLink) {
			$pieces = preg_split('/[\[\]\|]/', $vkLink);
			$target = '/\[' . $pieces[1] . '\|' . $pieces[2] . '\]/ui';

			if (!preg_match('/id|club/', $pieces[1]))
				continue;

			$newLink = '<a class="vk-post-link" target="_blank" href="https://vk.com/' . $pieces[1] . '">' . $pieces[2] . '</a>';
			$text = preg_replace($target, $newLink, $text);
		}

		return $text;
	}

	/**
	 * @param string $text
	 *
	 * @return string
	 */
	public function replaceLinks(string $text): string {
		$countLinks = preg_match_all("/(https|http):\/\/[^\s()]*/ui", $text, $links, PREG_PATTERN_ORDER);
		foreach ($links[0] as $link) {
			$newLink = '<a class="vk-post-urllink" target="_blank" href="' . $link . '">' . $link . '</a>';
			$link = '/' . preg_replace('/\//', '\/', $link) . '/';
			$text = preg_replace($link, $newLink, $text);
		}

		return $text;
	}

	/**
	 * @return Client
	 */
	public function getClient(): Client {
		if (null === $this->client) {
			$this->client = new Client('5.85');
			$token = $this->getToken();
			if (null !== $token) {
				$this->client->setDefaultToken($token);
			}
		}
		return $this->client;
	}

	/**
	 * @return mixed|null
	 */
	public function getToken() {
		return env('VK_SERVICE_API') ?? null;
	}

	/**
	 * @return array|null
	 */
	public function getPosts() {
		try {
			$client = $this->getClient();
			$request = new Request('wall.get', ['domain' => $this->getVkGroupName(), 'count' => $this->getCountPosts()]);
			$response = $client->send($request);
		} catch (VkException $exception) {
			Log::critical('method getPost failed', ['message' => $exception->getMessage(), 'line' => $exception->getLine(), 'code' => $exception->getCode()]);
		}

		return $response ?? null;
	}

	/**
	 * @return int
	 */
	public function getVkGroupId(): int {
		return $this->vkGroupId;
	}

	/**
	 * @return string
	 */
	public function getVkGroupName(): string {
		return $this->vkGroupName;
	}

	/**
	 * @return int
	 */
	public function getCountPosts(): int {
		return $this->countPosts;
	}
}
