<?php

namespace App\Models\Media\Vk;

use Alaouy\Youtube\Facades\Youtube;
use ATehnix\VkClient\Client;
use ATehnix\VkClient\Exceptions\VkException;
use ATehnix\VkClient\Requests\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
	use SoftDeletes;

	/**
	 * @var array
	 */
	protected $fillable = ['post_id', 'post_text', 'is_pinned', 'payload'];

	/**
	 * @var array
	 */
	protected $casts = ['payload' => 'array'];

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
	 * @param bool $pinned
	 */
	public function setPinned(bool $pinned) {
		$this->{'is_pinned'} = $pinned;
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
		return $payload['copy_history_text'];
	}

	/**
	 * @return string
	 */
	public function isPinned(string $block): string {
		if ('post' === $block)
			$postClass = $this->{'is_pinned'} ? 'vk-post pinned' : 'vk-post';
		elseif ('pin' === $block)
			$postClass = $this->{'is_pinned'} ? 'vk-post__pin' : 'vk-post__unpin';
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
	 * @return array
	 */
	public function getMedia(): array {
		$result = [];
		$caption = '';

		if ($this->hasText())
			$caption .= $this->getText() . "\n\n";

		if ($this->hasCopyHistory())
			$caption .= $this->getCopyHistoryText() . "\n\n";
		$result['media'] = $this->getPostAttachments();
		$result['url'] = $this->getLink();
		$result['caption'] = trim($caption);

		return $result;
	}

	/**
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopePinned(Builder $query): Builder {
		return $query->where('is_pinned', '=', '1');
	}

	/**
	 * @param Builder $query
	 * @param int     $postId
	 * @return Builder
	 */
	public function scopeExist(Builder $query, int $postId): Builder {
		return $query->where('post_id', '=', $postId);
	}

	/**
	 * @return array
	 */
	public function scopePosts(Builder $query): Builder {
		return $query->orderByDesc('is_pinned')
				->orderByDesc('id');
	}

	/**
	 * @return bool
	 * @throws \Exception
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
								//$isBlock = $this->isBlockedVideo($id);
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
				$this->delete();
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
