<?php

namespace App\Models\Media\Youtube;

use Alaouy\Youtube\Facades\Youtube;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\Media\Youtube\YoutubeVideo
 *
 * @property int $id
 * @property string $video_id
 * @property string $title
 * @property string $channel_id
 * @property \Illuminate\Support\Carbon $published_at
 * @property int|null $is_deleted
 * @property array|null $payload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Youtube\YoutubeVideo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Youtube\YoutubeVideo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Youtube\YoutubeVideo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Youtube\YoutubeVideo whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Youtube\YoutubeVideo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Youtube\YoutubeVideo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Youtube\YoutubeVideo whereIsDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Youtube\YoutubeVideo wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Youtube\YoutubeVideo wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Youtube\YoutubeVideo whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Youtube\YoutubeVideo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Youtube\YoutubeVideo whereVideoId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\Youtube\YoutubeVideo published()
 */
class YoutubeVideo extends Model {
	/**
	 * @var array
	 */
	protected $fillable = ['video_id', 'title', 'channel_id', 'published_at', 'is_deleted', 'payload'];

	/**
	 * @var array
	 */
	protected $casts = ['payload' => 'array'];

	/**
	 * @var array
	 */
	protected $dates = ['published_at'];

	/**
	 * @var string
	 */
	protected $channelId = 'UCFEFGJpu33RA_PDJXcZXx8w';

	/**
	 * @var int
	 */
	protected $countVideos = 3;

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->{'id'};
	}

	/**
	 * @return string
	 */
	public function getVideoId(): string {
		return $this->{'video_id'};
	}

	/**
	 * @return string
	 */
	public function getTitle(): string {
		return $this->{'tite'};
	}

	/**
	 * @return string
	 */
	public function getChannelId(): string {
		return $this->{'channel_id'};
	}

	/**
	 * @return string
	 */
	public function getPublishedAt(): string {
		/**
		 * @var Carbon $date
		 */
		$date = $this->{'published_at'};
		return $date->toDayDateTimeString();
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
	public function setDelete() {
		$this->{'is_deleted'} = 1;
	}

	/**
	 * @return array|null
	 */
	public function getPayload(): ?array {
		return $this->{'payload'};
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
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopePublished(Builder $query): Builder {
		return $query->orderBy('id', 'desc')
				->where('is_deleted', '!=', '1')
				->take(3);
	}

	public function updateFromYoutube(): bool {
		$result = false;

		try {
			$videoId = $this->getVideoId();
			$video = Youtube::getVideoInfo($videoId);

			dump($video);

			$result = true;
		} catch (\Exception $exception) {
			Log::critical('method updateFromYoutube failed', ['message' => $exception->getMessage(), 'line' => $exception->getLine(), 'code' => $exception->getCode()]);
		}

		return $result;
	}
}
