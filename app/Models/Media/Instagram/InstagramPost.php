<?php

namespace App\Models\Media\Instagram;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use InstagramAPI\Exception\InstagramException;
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
	use SoftDeletes;

	/**
	 * @var array
	 */
	protected $fillable = ['post_id', 'link', 'payload'];

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
	 * @return int
	 */
	public function getId(): int {
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
	 * @return array
	 */
	public function scopePosts(Builder $query): Builder {
		return $query->orderByDesc('id');
	}

	/**
	 * @return bool
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function updateFromInstagram(): bool {
		$result = false;

		try {
			$postId = $this->getPostId();
			$getPostInstagram = $this->getPostInstagram($postId);
			$status = $getPostInstagram['status'];
			$payload = $this->getPayload();

			if ('OK' === $status) {
				$post = $getPostInstagram['item'];
				if (null !== $post['caption']) {
					if ($payload['caption'] !== $post['caption']) {
						$this->setPayload('caption', $post['caption']);
					}
				} else {
					if (strlen($payload['caption']) !== 0) {
						$this->setPayload('caption', '');
					}
				}
			} else {
				$this->setDeleted();
			}

			$this->save();

			$result = true;
		} catch (\Exception $exception) {
			Log::critical('method updateFromInstagram failed', ['message' => $exception->getMessage(), 'line' => $exception->getLine(), 'code' => $exception->getCode()]);
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
	 * @param string $postId
	 * @return array
	 */
	private function getPostInstagram(string $postId): array {
		$client = $this->getClient();
		$result = ['status' => 'FAILED'];
		if (null !== $client) {
			try {
				$instagramPost = $client->media->getInfo($postId)->asArray();
				$result['status'] = 'OK';

				if (null !== $instagramPost['items'][0]['caption']) {
					$result['item']['caption'] = $instagramPost['items'][0]['caption']['text'];
				} else {
					$result['item']['caption']['caption'] = '';
				}
			} catch (InstagramException $exception) {
				Log::critical('method getPost failed', ['message' => $exception->getMessage(), 'line' => $exception->getLine(), 'code' => $exception->getCode()]);
			}
		}

		return $result;
	}

	/**
	 * @return mixed|null
	 */
	public function getToken() {
		return env('INSTAGRAM_API') ?? null;
	}

	/**
	 * @return array
	 */
	public function getMedia(): array {
		$result = [];
		$payload = $this->getPayload();
		$result['media'] = $payload['media'];
		$result['url'] = $this->getLink();
		$result['caption'] = $payload['caption'];

		return $result;
	}
}
