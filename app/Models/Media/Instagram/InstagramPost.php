<?php

namespace App\Models\Media\Instagram;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Vinkla\Instagram\Instagram;

/**
 * App\Models\Media\Instagram\InstagramPost
 *
 * @property int                             $id
 * @property int                             $post_id
 * @property string                          $link
 * @property \Illuminate\Support\Carbon      $posted_at
 * @property int|null                        $is_deleted
 * @property array|null                      $payload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Instagram\InstagramPost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Instagram\InstagramPost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Instagram\InstagramPost query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Instagram\InstagramPost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Instagram\InstagramPost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Instagram\InstagramPost whereIsDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Instagram\InstagramPost whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Instagram\InstagramPost wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Instagram\InstagramPost wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Instagram\InstagramPost wherePostedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Instagram\InstagramPost whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Instagram\InstagramPost published()
 */
class InstagramPost extends Model {
	/**
	 * @var array
	 */
	protected $fillable = ['post_id', 'link', 'posted_at', 'is_deleted', 'payload'];

	/**
	 * @var array
	 */
	protected $dates = ['posted_at'];

	/**
	 * @var array
	 */
	protected $casts = ['payload' => 'array'];

	/**
	 * @var
	 */
	protected $client;

	/**
	 * @var
	 */
	protected $clientGuzzle;

	/**
	 * @return int
	 */
	public function getKey(): int {
		return $this->{'id'};
	}

	/**
	 * @return string
	 */
	public function getPostId(): string {
		return $this->{'post_id'};
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
		return $this->{'link'};
	}

	/**
	 * @return bool
	 */
	public function hasVideos(): bool {
		$payload = $this->getPayload();
		$result = false;
		if (0 !== count($payload['media']['videos'])) {
			$result = true;
		}

		return $result;
	}

	/**
	 * @return array
	 */
	public function getVideos(): array {
		return $this->getPayload()['media']['videos'];
	}

	/**
	 * @return bool
	 */
	public function hasImages(): bool {
		$payload = $this->getPayload();
		$result = false;
		if (0 !== count($payload['media']['images'])) {
			$result = true;
		}

		return $result;
	}

	/**
	 * @return array
	 */
	public function getImages(): array {
		return $this->getPayload()['media']['images'];
	}

	/**
	 * @return array
	 */
	public function getFiles(): array {
		$files = array_merge($this->getImages(), $this->getVideos());

		return $files;
	}

	/**
	 * @return bool
	 */
	public function hasTags(): bool {
		$payload = $this->getPayload();
		$result = false;
		if (0 !== count($payload['tags'])) {
			$result = true;
		}

		return $result;
	}

	/**
	 * @return array
	 */
	public function getTags(): array {
		return $this->getPayload()['tags'] ?? null;
	}

	/**
	 * @return string
	 */
	public function getCaption(): string {
		return $this->getPayload()['caption'] ?? null;
	}

	/**
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopePublished(Builder $query): Builder {
		return $query->orderBy('id', 'desc')
				->where('is_deleted', '!=', '1');
	}

	/**
	 * @return bool
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function updateFromInstagram(): bool {
		$result = false;

		try {
			$postId = $this->getPostId();
			$link = $this->getLink();
			$getPostInstagram = $this->getPostInstagram($postId);
			$code = $getPostInstagram['meta']['code'];
			$payload = $this->getPayload();

			if (400 === $code) {
				$this->setDeleted();
			} elseif (200 === $code) {
				$post = $getPostInstagram['data'];
				if (null !== $post['caption']) {
					$caption_text = $this->getCaptionText($post['caption']['text']);

					if ($payload['caption'] !== $caption_text) {
						$this->setPayload('caption', $caption_text);
					}
				} else {
					if (strlen($payload['caption']) !== 0) {
						$this->setPayload('caption', '');
					}
				}

				if (0 !== count($post['tags'])) {
					$hashtags = $this->getHashtags($post['tags']);

					if ($payload['tags'] !== $hashtags) {
						$this->setPayload('tags', $hashtags);
					}
				} else {
					if (strlen($payload['tags']) !== 0) {
						$this->setPayload('tags', '');
					}
				}
			} else {
				dump('something is wrong, statusCode is ' . $code);
			}

			$this->save();

			$result = true;
		} catch (\Exception $exception) {
			Log::critical('method updateFromInstagram failed', ['message' => $exception->getMessage(), 'line' => $exception->getLine(), 'code' => $exception->getCode()]);
		}

		return $result;
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

	/**
	 * @return Client
	 */
	private function getClientGuzzle(): Client {
		if (null === $this->clientGuzzle) {
			$this->clientGuzzle = new Client([
					'timeout' => 5,
					'http_errors' => false
			]);
		}

		return $this->clientGuzzle;
	}

	/**
	 * @param string $postId
	 * @return array
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	private function getPostInstagram(string $postId): array {
		$guzzleClient = $this->getClientGuzzle();
		$token = $this->getToken();

		$response = $guzzleClient->request('GET', 'https://api.instagram.com/v1/media/' . $postId . '/?access_token=' . $token)->getBody()->getContents();
		$response = json_decode($response, true);

		return $response;
	}

	/**
	 * @return mixed|null
	 */
	public function getToken() {
		return env('INSTAGRAM_API') ?? null;
	}
}
