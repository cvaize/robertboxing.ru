<?php

namespace App\Models\Media\Vk;

use Alaouy\Youtube\Facades\Youtube;
use ATehnix\VkClient\Client;
use ATehnix\VkClient\Exceptions\VkException;
use ATehnix\VkClient\Requests\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\Media\Vk\VkPost
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Vk\VkPost exist($postId)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Vk\VkPost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Vk\VkPost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Vk\VkPost pinned()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Vk\VkPost query()
 * @mixin \Eloquent
 * @property int                             $id
 * @property int                             $post_id
 * @property string|null                     $post_text
 * @property \Illuminate\Support\Carbon      $posted_at
 * @property array|null                      $payload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Vk\VkPost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Vk\VkPost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Vk\VkPost wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Vk\VkPost wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Vk\VkPost wherePostText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Vk\VkPost wherePostedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Vk\VkPost whereUpdatedAt($value)
 * @property int|null                        $is_deleted
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Vk\VkPost whereIsDeleted($value)
 */
class VkPost extends Model {
	/**
	 * @var array
	 */
	protected $fillable = ['post_id', 'post_text', 'posted_at', 'is_deleted', 'payload'];

	/**
	 * @var array
	 */
	protected $casts = ['payload' => 'array'];

	/**
	 * @var array
	 */
	protected $dates = ['posted_at'];

	/**
	 * @var int
	 */
	protected $vkGroupId = -174135295;

	/**
	 * @var string
	 */
	protected $vkGroupName = 'robertboxing';

	/**
	 * @var
	 */
	protected $client;

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
	public function getKey(): int {
		return $this->{'id'};
	}

	/**
	 * @return int
	 */
	public function getPostId(): int {
		return $this->{'post_id'};
	}

	/**
	 * @return mixed|null
	 */
	public function getText() {
		return $this->{'post_text'} ?? null;
	}

	/**
	 * @param string $postText
	 */
	public function setText(string $postText) {
		$this->{'post_text'} = $postText;
	}

	/**
	 * @return string
	 */
	public function getPostedAt(): string {
		return $this->{'posted_at'};
	}

	/**
	 * @return bool
	 */
	public function isDeleted(): bool {
		return $this->{'is_deleted'};
	}

	/**
	 *
	 */
	public function setDeleted() {
		$this->{'is_deleted'} = 1;
	}

	/**
	 * @return mixed|null
	 */
	public function getPayload() {
		return $this->{'payload'} ?? null;
	}

	/**
	 * @param $key
	 * @param $value
	 */
	public function setPayload($key, $value) {
		$payload = $this->getPayload();
		if (null !== $payload) {
			$payload[$key] = $value;
			$this->{'payload'} = $payload;
		}
	}

	/**
	 * @return string
	 */
	public function getLink(): string {
		return 'https://vk.com/wall' . $this->getVkGroupId() . '_' . $this->getPostID();
	}

	/**
	 * @param $owner
	 * @param $id
	 * @return string
	 */
	public function getVideoLink($owner, $id): string {
		return 'https://vk.com/video' . $owner . '_' . $id;
	}

	/**
	 * @return bool
	 */
	public function hasText(): bool {
		return (null !== $this->{'post_text'}) ? true : false;
	}

	/**
	 * @return bool
	 */
	public function hasCopyHistory(): bool {
		$payload = $this->getPayload();
		return $payload['copy_history'];
	}

	/**
	 * @return mixed
	 */
	public function getCopyHistoryText() {
		$payload = $this->getPayload();
		return $payload['copy_history_data'][0]['text'];
	}

	/**
	 * @return string
	 */
	public function isPinned(string $block): string {
		$payload = $this->getPayload();
		if ('post' === $block)
			$postClass = $payload['is_pinned'] ? 'vk-post pinned' : 'vk-post';
		elseif ('pin' === $block)
			$postClass = $payload['is_pinned'] ? 'vk-post__pin' : 'vk-post__unpin';
		return $postClass;
	}

	/**
	 * @return mixed
	 */
	public function getPostAttachments() {
		$payload = $this->getPayload();
		return $payload['post_attachments'];
	}

	/**
	 * @return mixed
	 */
	public function getPostCopyAttachments() {
		$payload = $this->getPayload();
		return $payload['copy_history_data'][0]['attachments'];
	}

	/**
	 * @return mixed
	 */
	public function getAttachments() {
		if ($this->hasCopyHistory())
			$attachments = $this->getPostCopyAttachments();
		else
			$attachments = $this->getPostAttachments();

		return $attachments;
	}

	/**
	 * @return bool
	 */
	public function hasImages() {
		$hasImages_ = false;
		$attachments = $this->getAttachments();

		foreach ($attachments as $attachment) {
			if (array_key_exists('photo', $attachment))
				$hasImages_ = true;
		}

		return $hasImages_;
	}

	/**
	 * @return array
	 */
	public function getImages(): array {
		$attachments = $this->getAttachments();
		$images = [];
		foreach ($attachments as $attachment) {
			if (array_key_exists('photo', $attachment)) {
				foreach ($attachment['photo']['sizes'] as $size) {
					if (in_array('y', $size)) {
						array_push($images, ['type' => 'link', 'url' => $size['url']]);
					}
				}
			}
		}

		return $images;
	}

	/**
	 * @param string $id
	 * @return bool
	 */
	public function isBlockedVideo(string $id): bool {
		$result = true;
		try {
			$video = Youtube::getVideoInfo($id);
			if ('public' !== $video->{'status'}->{'privacyStatus'}) {
				$result = true;
			} elseif (array_key_exists('regionRestriction', $video->{'contentDetails'})) {
				if (in_array('RU', $video->{'contentDetails'}->{'regionRestriction'}->{'blocked'})) {
					$result = true;
				}
			} else
				$result = false;
		} catch (\Exception $exception) {
			$result = true;
			Log::critical('method isBlockedVideo failed', ['message' => $exception->getMessage(), 'line' => $exception->getLine(), 'code' => $exception->getCode()]);
		}

		return $result;
	}

	/**
	 * @return bool
	 */
	public function hasVideos() {
		$hasVideos_ = false;
		$attachments = $this->getAttachments();

		foreach ($attachments as $attachment) {
			if (array_key_exists('video', $attachment)) {
				$hasVideos_ = true;
			} elseif (array_key_exists('link', $attachment)) {
				if (preg_match('/youtube.com|youtu.be/', $attachment['link']['url'])) {
					$hasVideos_ = true;
				}
			}
		}

		return $hasVideos_;
	}

	/**
	 * @return array
	 */
	public function getVideos(): array {
		$attachments = $this->getAttachments();
		$videos = [];

		foreach ($attachments as $attachment) {
			if (array_key_exists('video', $attachment)) {
				array_push($videos, ['type' => 'video_vk', 'photo' => $attachment['video']['photo_800'], 'url' => $this->getVideoLink($attachment['video']['owner_id'], $attachment['video']['id'])]);
			} elseif (array_key_exists('link', $attachment)) {
				if (preg_match('/youtube.com|youtu.be/', $attachment['link']['url'])) {
					foreach ($attachment['link']['photo']['sizes'] as $size) {
						if (in_array('l', $size)) {
							array_push($videos, ['type' => 'video_youtube', 'is_blocked' => $attachment['link']['is_blocked'], 'url' => $attachment['link']['url'], 'photo' => $size['url']]);
						}
					}
				}
			}
		}

		return $videos;
	}

	/**
	 * @return array
	 */
	public function getFiles(): array {
		$files = array_merge($this->getImages(), $this->getVideos());

		return $files;
	}

	/**
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopePinned(Builder $query): Builder {
		return $query->where('payload', 'like', '%"is_pinned":1%');
	}

	/**
	 * @param Builder $query
	 * @param int     $postId
	 * @return Builder
	 */
	public function scopeExist(Builder $query, int $postId): Builder {
		return $query->where('post_id', '=', $postId);
	}

    public function scopePined(Builder $query): Builder
    {
        return $query->orderByDesc('is_pined')->orderByDesc('id');
    }

	/**
	 * @return array
	 */
	public static function published(): array {
		$result = ['pinned' => [], 'not_pinned' => []];
		$postPinned = self::where('payload', 'like', '%"is_pinned":1%')->get();

        $result['pinned'][] = $postPinned[0];

		if (0 !== count($postPinned)) {
			/**
			 * @var VkPost $postPinned
			 */
			$idPinned = $postPinned[0]->getPostId();
			$res = self::orderBy('id', 'desc')
					->where([
							['post_id', '!=', $idPinned],
							['is_deleted', '!=', 1]
					])->take(2)
					->get();
			foreach ($res as $item) {
				array_push($result['not_pinned'], $item);
			}
		} else {
			$res = self::orderBy('id', 'desc')->take(3)->get();
			foreach ($res as $item) {
                $result['not_pinned'][] = $item;
			}
		}

		return $result;
	}

	/**
	 * @return bool
	 */
	public function updateFromVk(): bool {
		$result = false;
		try {
			$client = $this->getClient();
			$request = new Request('wall.getById', ['posts' => $this->getVkGroupId() . '_' . $this->getPostId()]);
			$response = $client->send($request);

			if (0 !== count($response['response'])) {
				$post = $response['response'][0];

				if (array_key_exists('attachments', $post)) {
					$indexAttachments = 0;
					foreach ($post['attachments'] as $attachment) {
						if ('link' === $attachment['type']) {
							$url = $attachment['link']['url'];
							$parseLink = parse_url($url);
							if ('youtu.be' === $parseLink['host']) {
								$id = substr($parseLink['path'], 1);
								$isBlock = $this->isBlockedVideo($id);
								$post['attachments'][$indexAttachments]['link']['is_blocked'] = $isBlock;

								if ($isBlock) {
									$link = '/' . preg_replace('/\//', '\/', $url) . '/';
									$post['text'] = preg_replace($link, '', $post['text']);
								}
							}
						}
						$indexAttachments++;
					}

					$this->setPayload('post_attachments', $post['attachments']);
				}

				if ($this->getText() !== $post['text'])
					$this->setText($post['text']);
			} else {
				$this->setDeleted();
			}

			$result = true;
		} catch (VkException $exception) {
			Log::critical('method updateFromVk failed', ['message' => $exception->getMessage(), 'line' => $exception->getLine(), 'code' => $exception->getCode()]);
		}

		return $result;
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
}
