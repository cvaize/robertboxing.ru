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

class RequestSiteController extends Controller {

	/**
	 * @var
	 */
	protected $requestsSites;

	/**
	 * @var
	 */
	protected $users;

	/**
	 * @var
	 */
	protected $smsApi;

	/**
	 * @var
	 */
	protected $trainingApi;

	/**
	 * @var
	 */
	protected $feedback;

	/**
	 * RequestSiteController constructor.
	 * @param RequestSite $requestSites
	 */
	public function __construct(RequestSite $requestSites, User $users, SmsApi $smsApi, TrainingApi $trainingApi, Feedback $feedback) {
		$this->requestsSites = $requestSites;
		$this->users = $users;
		$this->smsApi = $smsApi;
		$this->trainingApi = $trainingApi;
		$this->feedback = $feedback;
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function index(Request $request) {
		$frd = $request->only(['perPage', 'search']);
		$requestsSites = $this->requestsSites->filter($frd)->orderByDesc('id')->paginate($frd['perPage'] ?? $this->requestsSites->getPerPage());
		$balance = $this->smsApi ?? null;

		return view('requests.index', compact('frd', 'requestsSites', 'balance'));
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
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function store(Request $request) {
		$messages = [
			'g-recaptcha-response.required' => 'Обязательно пройдите reCAPTCHA',
			'g-recaptcha-response.captcha' => 'reCAPTCHA не пройдена',
			'phone.min' => 'Минимум 8 симоволов',
			'phone.required' => 'Обязательно укажите телефон',
			'name.min' => 'Минимум 2 симовола',
			'name.required' => 'Как к вам обращаться?',
		];
		\Validator::make($request->only(['name', 'phone', 'g-recaptcha-response']), [
			'name' => 'required|min:2',
			'phone' => 'required|min:8',
			'g-recaptcha-response' => 'required|captcha',

		], $messages)->validate();

		$frd = $request->only(['name', 'phone']);
		$res = [
			'type' => 'success',
		];
		$userIp = $request->ip();
		$userAgent = $request->header('user-agent');

		/** @var User $user */
		$user = User::firstOrCreate([
			'phone' => $frd['phone'],
		], [
			'f_name' => $frd['name'],
			'password' => Hash::make(str_random(8))
		]);

		$requestSite = new RequestSite([
			'name' => $frd['name'],
			'phone' => $frd['phone'],
			'user_ip' => $userIp,
			'user_agent' => $userAgent
		]);
		$sms = new Sms(['text' => $frd['phone'] . ' ' . $frd['name']]);
		$email = new Email(['text' => $frd['phone'] . ' ' . $frd['name']]);

		$user->requests()->save($requestSite);
		$sms->save();
		$email->save();
		$requestSite->sms()->associate($sms)->save();
		$requestSite->email()->associate($email)->save();
		$smsAccess = $this->smsApi->isAccessible();

		if ($smsAccess['OK']) {
			Notification::send($this->users->notifiable()->get(), new RequestLeft($requestSite));
		} else {
			$res = [
				'type' => 'error',
			];
		}

		return response()->json($res);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Requests\RequestSite $requestSite
	 * @return \Illuminate\Http\Response
	 */
	public function show(RequestSite $request) {

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\Requests\RequestSite $requestSite
	 * @return \Illuminate\Http\Response
	 */
	public function edit(RequestSite $requestSite) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request         $request
	 * @param  \App\Models\Requests\RequestSite $requestSite
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, RequestSite $requestSite) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Requests\RequestSite $requestSite
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(RequestSite $requestSite) {
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Requests\RequestSite $requestSite
	 * @return \Illuminate\Http\Response
	 */
	public function time(RequestSite $requestSite) {
		$nextTraining = $this->trainingApi->nextTrainingTimeLeft();
		dd($nextTraining);
	}

	public function testing(Request $request) {
		$balance = $this->smsApi ?? null;

		return view('requests.testing', compact('balance'));
	}
}
