<?php

namespace App\Http\Controllers\Requests;

use App\Http\Controllers\Controller;
use App\Models\Apis\SmsApi;
use App\Models\Apis\TrainingApi;
use App\Models\Notifications\Email;
use App\Models\Notifications\Sms;
use App\Models\Requests\Feedback;
use App\Models\Requests\RequestSite;
use App\Models\Users\User;
use App\Notifications\RequestLeft;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class FeedbackController extends Controller {

	/**
	 * @var
	 */
	protected $feedback;

	/**
	 * @var SmsApi
	 */
	protected $smsApi;

	/**
	 * RequestSiteController constructor.
	 * @param RequestSite $requestSites
	 */
	public function __construct(Feedback $feedback, SmsApi $smsApi) {
		$this->feedback = $feedback;
		$this->smsApi = $smsApi;
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function index(Request $request) {
		$frd = $request->only([
			'search',
			'perPage',
			'status_id'
		]);
		$feedback = $this->feedback->filter($frd)
		                           ->orderByDesc('id')
		                           ->paginate($frd['perPage'] ?? $this->feedback->getPerPage());
		$balance = $this->smsApi;

		return view('feedback.index', compact('frd', 'feedback', 'balance'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function store(Request $request) {
		$messagesCaptcha = [
			'g-recaptcha-response.required' => 'Обязательно пройдите reCAPTCHA',
			'g-recaptcha-response.captcha'  => 'reCAPTCHA не пройдена',
		];
		$messages = [
			'phone.min'              => 'Минимум 8 симоволов',
			'phone.required'         => 'Обязательно укажите телефон',
			'name.min'               => 'Минимум 2 симовола',
			'name.required'          => 'Как к вам обращаться?',
			'feedback-text.min'      => 'Минимум 10 символов',
			'feedback-text.required' => 'Ваше мнение'
		];
		$inputsCaptcha = ['g-recaptcha-response'];
		$inputs = [
			'name',
			'phone',
			'feedback-text'
		];
		$rulesCaptcha = ['g-recaptcha-response' => 'required|captcha'];
		$rules = [
			'name'          => 'required|min:2',
			'phone'         => 'required|min:8',
			'feedback-text' => 'required|min:10',
		];

		if (Feedback::isCaptchaReviews()) {
			$messages = array_merge($messages, $messagesCaptcha);
			$inputs = array_merge($inputs, $inputsCaptcha);
			$rules = array_merge($rules, $rulesCaptcha);
		}
		\Validator::make($request->only($inputs), $rules, $messages)->validate();

		$frd = $request->only([
			'name',
			'phone',
			'feedback-text'
		]);
		$res = [
			'type' => 'success',
		];
		$userIp = $request->ip();
		$userAgent = $request->header('user-agent');

		/** @var User $user */
		$user = User::firstOrCreate([
			'phone' => $frd['phone'],
		], [
			'f_name'   => $frd['name'],
			'password' => Hash::make(str_random(8))
		]);

		$feedback = new Feedback([
			'text'       => $frd['feedback-text'],
			'status_id'  => 1,
			'user_ip'    => $userIp,
			'user_agent' => $userAgent
		]);

		$user->feedback()->save($feedback);

		return response()->json($res);
	}

	/**
	 * @param Feedback $feedback
	 */
	public function show(Feedback $feedback) {

	}

	/**
	 * @param Feedback $feedback
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit(Feedback $feedback) {
		$balance = $this->smsApi;

		return view('feedback.edit', compact('feedback', 'balance'));
	}

	/**
	 * @param Request  $request
	 * @param Feedback $feedback
	 */
	public function update(Request $request, Feedback $feedback) {
		$this->validate($request, [
			'status_id' => 'required',
		]);
		$frd = $request->only(['status_id']);
		$message = [
			'flash_message' => [
				'type' => 'danger',
				'text' => 'При обновлении пользователя произошла ошибка',
			]
		];

		try {
			$feedback->update($frd);
			$message = [
				'flash_message' => [
					'type' => 'success',
					'text' => 'Отзыв успешно сохранен',
				]
			];
		} catch (\Exception $exception) {
			Log::critical('method FeedbackController@update failed', [
				'message' => $exception->getMessage(),
				'line'    => $exception->getLine(),
				'code'    => $exception->getCode()
			]);
		}

		return redirect()->back()->with('flash_message', $message['flash_message']);
	}

	/**
	 * @param Feedback $feedback
	 */
	public function destroy(Feedback $feedback) {
		//
	}
}
