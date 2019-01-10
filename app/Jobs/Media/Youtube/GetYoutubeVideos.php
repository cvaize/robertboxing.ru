<?php

namespace App\Jobs\Media\Youtube;

use Alaouy\Youtube\Facades\Youtube;
use App\Models\Media\Youtube\YoutubeVideo;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GetYoutubeVideos implements ShouldQueue {
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
	private $videos;

	/**
	 * @var int
	 */
	protected $countVideos = 5;
	/**
	 * @var string
	 */
	private $channelId = 'UCyuMv8sIthuCu-ZBdMJhQag';

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
	 * @param YoutubeVideo $youtubeVideos
	 * @throws \Exception
	 */
	public function handle(YoutubeVideo $youtubeVideos) {
		$this->videos = $youtubeVideos;
		$getVideos = array_reverse($this->getVideos(), true);
		$logMessage = '';
		$logMessage .= Carbon::now()->toDayDateTimeString() . ' Get youtube video: ';


		foreach ($getVideos as $video) {
			$payload = [];
			$videoId = $video['id']['videoId'];

			if (0 !== count($this->videos::where('video_id', $videoId)->get()))
				continue;

			$logMessage .= $videoId . ' ';
			$payload['channelTitle'] = $video['snippet']['channelTitle'];
			$payload['thumbnail'] = $video['snippet']['thumbnails']['high']['url'];
			$payload['description'] = $this->getFullDescription($videoId);
			$payload['published_at'] = strtotime($video['snippet']['publishedAt']);
			$logMessage .= $videoId . ' ';
			$frd = [
					'video_id' => $videoId,
					'title' => $video['snippet']['title'],
					'channel_id' => $video['snippet']['channelId'],
					'payload' => $payload
			];

			$this->videos->create($frd);
		}

		dump($logMessage);
	}

	/**
	 * @return string
	 */
	public function getChannelId(): string {
		return $this->channelId;
	}

	/**
	 * @return int
	 */
	public function getCountVideos(): int {
		return $this->countVideos;
	}

	/**
	 * @return array|null
	 * @throws \Exception
	 */
	public function getVideos(): ?array {
		$channelId = $this->getChannelId();
		$countVideos = $this->getCountVideos();
		$videos = Youtube::listChannelVideos($channelId, $countVideos, 'date');
		$videos = json_decode(json_encode($videos), true);

		return $videos;
	}

	/**
	 * @param string $id
	 * @return string
	 * @throws \Exception
	 */
	public function getFullDescription(string $id): string {
		$videoInfo = json_decode(json_encode(Youtube::getVideoInfo($id)), true);
		$description = $videoInfo['snippet']['description'];

		return $description;
	}
}
