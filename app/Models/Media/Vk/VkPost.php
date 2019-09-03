<?php

namespace App\Models\Media\Vk;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use VK\Client\VKApiClient;
use VK\Exceptions\VKApiException;

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
 * @property int|null $is_pinned
 * @property string|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Media\Vk\VkPost onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Vk\VkPost posts()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Vk\VkPost whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Vk\VkPost whereIsPinned($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Media\Vk\VkPost withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Media\Vk\VkPost withoutTrashed()
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
			$response = $client->wall()->getById($this->getToken(), [
					'posts' => $this->getVkGroupId() . '_' . $this->getPostId()
			]);

			if (0 !== count($response)) {
				$post = $response[0];
				$timePassed = Carbon::createFromTimestamp($post['date'])->diffInHours();

				if ($timePassed < 24) {
					if (array_key_exists('attachments', $post)) {
						$postAttachments = [];
						foreach ($post['attachments'] as $attachment) {
							if ('photo' === $attachment['type']) {
								$maxWidth = 0;
								$indexMaxSize = null;

								foreach ($attachment['photo']['sizes'] as $key => $item) {
									if ($item['width'] > $maxWidth)
										$maxWidth = $item['width'];
									$indexMaxSize = $key;
								}

								$image = [
										'url' => $attachment['photo']['sizes'][$indexMaxSize]['url'],
										'isImage' => true,
										'isVideo' => false
								];

								$postAttachments[] = $image;
							} elseif ('video' === $attachment['type']) {
								$videoOwner = $attachment['video']['owner_id'];
								$videoId = $attachment['video']['id'];
								$videoAccessKey = $attachment['video']['access_key'];
								$videoInfo = $this->getVideoInfo($videoOwner, $videoId, $videoAccessKey);
								$video = [
										'first_frame' => $videoInfo['items'][0]['photo_800'],
										'url' => $videoInfo['items'][0]['player'],
										'isImage' => false,
										'isVideo' => true
								];

								$postAttachments[] = $video;
							}
						}

						$this->setPayload('post_attachments', $postAttachments);
					}

					if ('' !== $post['text']) {
						$post['text'] = $this->replaceLinks($post['text']);
						$post['text'] = $this->replaceVkLinks($post['text']);
					}

					if ($this->getText() !== $post['text'])
						$this->setText($post['text']);

				}
			} else {
				$this->delete();
			}

			$this->save();
			$result = true;
		} catch (VKApiException $exception) {
			Log::critical('method updateFromVk failed', ['message' => $exception->getMessage(), 'line' => $exception->getLine(), 'code' => $exception->getCode()]);
		}

		return $result;
	}

	/**
	 * @param string $text
	 *
	 * @return string
	 */
	private function replaceVkLinks(string $text): string {
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
	private function replaceLinks(string $text): string {
		$countLinks = preg_match_all("/(https|http):\/\/[^\s()]*/ui", $text, $links, PREG_PATTERN_ORDER);
		foreach ($links[0] as $link) {
			$newLink = '<a class="vk-post-urllink" target="_blank" href="' . $link . '">' . $link . '</a>';
			$link = '/' . preg_replace('/\//', '\/', $link) . '/';
			$text = preg_replace($link, $newLink, $text);
		}

		return $text;
	}

	/**
	 * @return VKApiClient
	 */
	private function getClient(): VKApiClient {
		if (null === $this->client) {
			$this->client = new VKApiClient();
		}

		return $this->client;
	}

	/**
	 * @param int    $owner
	 * @param int    $id
	 * @param string $accessKey
	 * @return mixed|null
	 * @throws \VK\Exceptions\VKClientException
	 */
	private function getVideoInfo(int $owner, int $id, string $accessKey) {
		try {
			$client = $this->getClient();
			$video = $owner . '_' . $id . '_' . $accessKey;
			$response = $client->video()->get($this->getVideoToken(), [
					'videos' => $video
			]);
		} catch (VKApiException $exception) {
			Log::critical('method getVideoInfo failed', ['message' => $exception->getMessage(), 'line' => $exception->getLine(), 'code' => $exception->getCode()]);
		}

		return $response ?? null;
	}

	/**
	 * @return null|string
	 */
	private function getToken(): ?string  {
		return env('VK_SERVICE_API') ?? null;
	}

	/**
	 * @return null|string
	 */
	private function getVideoToken(): ?string {
		return env('VK_VIDEO_ACCESS_TOKEN') ?? null;
	}
}
