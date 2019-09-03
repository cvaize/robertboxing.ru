<?php

namespace App\Http\Controllers;

use App\Models\Apis\TrainingApi;
use App\Models\Media\Instagram\InstagramPost;
use App\Models\Media\Vk\VkPost;
use App\Models\Media\Youtube\YoutubeVideo;
use App\Models\Requests\Feedback;
use App\Models\Requests\RequestSite;
use Illuminate\Http\Request;

class WelcomeController extends Controller {
	protected $perPageVk = 3;
	protected $perPageInstagram = 3;
	protected $perPageYoutube = 3;

	/**
	 * @var
	 */
	protected $trainingApi;

	/**
	 * @var
	 */
	protected $feedback;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(TrainingApi $trainingApi, Feedback $feedback) {
		$this->trainingApi = $trainingApi;
		$this->feedback = $feedback;
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {
		$perPageVk = $this->perPageVk;
		$perPageInstagram = $this->perPageInstagram;
		$perPageYoutube = $this->perPageYoutube;

		$vk = VkPost::posts()->paginate($perPageVk);
		$instagram = InstagramPost::posts()->paginate($perPageInstagram);
		$youtube = YoutubeVideo::posts()->paginate($perPageYoutube);

		$nextTraining = $this->trainingApi->nextTraining() ?? 'unknown';
		$nextTrainingTimeLeft = $this->trainingApi->nextTrainingTimeLeft();
		$training = $this->trainingApi->filter([])->orderBy('ordering', 'ASC')->get();
		$feedback = $this->feedback->randomFeedback()->get();

		$vk = $vk->map(function ($item) {
			return $item->getMedia();
		});
		$instagram = $instagram->map(function ($item) {
			return $item->getMedia();
		});
		$youtube = $youtube->map(function ($item) {
			return $item->getMedia();
		});
		$vk = json_encode($vk);
		$instagram = json_encode($instagram);
		$youtube = json_encode($youtube);

		if ($request->ajax()) {
			$response = response()->json(compact('vk', 'instagram', 'youtube', 'perPageVk', 'perPageInstagram', 'perPageYoutube', 'nextTraining', 'nextTrainingTimeLeft', 'training', 'feedback'));
		} else {
			$response = view('welcome', compact('vk', 'instagram', 'youtube', 'perPageVk', 'perPageInstagram', 'perPageYoutube', 'nextTraining', 'nextTrainingTimeLeft', 'training', 'feedback'));
		}

		return $response;
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function posts(Request $request) {
		$frd = $request->only(['social']);
		$perPageVk = $this->perPageVk;
		$perPageInstagram = $this->perPageInstagram;
		$perPageYoutube = $this->perPageYoutube;

		$res = [];

		if (isset($frd['social'])) {
			if ($frd['social'] === 'vk') {
				$posts = VkPost::posts()->paginate($perPageVk);
			}
			if ($frd['social'] === 'instagram') {
				$posts = InstagramPost::posts()->paginate($perPageInstagram);
			}
			if ($frd['social'] === 'youtube') {
				$posts = YoutubeVideo::posts()->paginate($perPageYoutube);
			}


			if (isset($posts)) {
				$res = $posts->map(function ($item) {
					return $item->getMedia();
				});
			}
		}
		return response()->json($res);
	}

}
