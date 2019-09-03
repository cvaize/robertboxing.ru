<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Apis\SmsApi;
use App\Models\Users\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller {
	/**
	 * @var
	 */
	protected $users;
	/**
	 * @var
	 */
	protected $smsApi;

	/**
	 * UserController constructor.
	 * @param User $users
	 */
	public function __construct(User $users, SmsApi $smsApi) {
		$this->users = $users;
		$this->smsApi = $smsApi;
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function index(Request $request) {
		//dd($request->header('user-agent'));
		//dd($request->ip());

		$frd = $request->only(['search', 'perPage']);
		$users = $this->users->filter($frd)->paginate($frd['perPage'] ?? $this->users->getPerPage());
		$balance = $this->smsApi ?? null;

		return view('users.index', compact('frd', 'users', 'balance'));
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function create() {
		$balance = $this->smsApi ?? null;

		return view('users.create', compact('balance'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$this->validate($request, [
			'f_name' => 'required',
			'phone' => 'required',
			'password' => 'required|confirmed|min:6',
		]);
		$frd = $request->only(['f_name', 'phone', 'email', 'is_smsable', 'is_emailable', 'password']);

		$message = [
			'flash_message' => [
				'type' => 'danger',
				'text' => 'При создании пользователя произошла ошибка',
			]
		];

		$frd['password'] = bcrypt($frd['password']);

		if (isset($frd['is_smsable']))
			$frd['is_smsable'] = true;
		else
			$frd['is_smsable'] = false;

		if (isset($frd['is_emailable']))
			$frd['is_emailable'] = true;
		else
			$frd['is_emailable'] = false;

		try {
			$this->users->create($frd);
			$message = [
				'flash_message' => [
					'type' => 'success',
					'text' => 'Пользователь успешно создан',
				]
			];
		} catch (\Exception $exception) {
			Log::critical('method userController@store failed', ['message' => $exception->getMessage(), 'line' => $exception->getLine(), 'code' => $exception->getCode()]);
		}

		return redirect()->back()->with('flash_message', $message['flash_message']);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Users\User $user
	 * @return \Illuminate\Http\Response
	 */
	public function show(User $user) {
		//
	}

	/**
	 * @param User $user
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function edit(User $user) {
		$balance = $this->smsApi ?? null;

		return view('users.edit', compact('user', 'balance'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \App\Models\Users\User   $user
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, User $user) {
		$this->validate($request, [
			'f_name' => 'required',
			'phone' => 'required',
			'password' => 'confirmed|min:6|nullable',
		]);
		$frd = $request->only(['f_name', 'phone', 'email', 'is_smsable', 'is_emailable', 'password']);

		$message = [
			'flash_message' => [
				'type' => 'danger',
				'text' => 'При обновлении пользователя произошла ошибка',
			]
		];

		if (null === $frd['password']) {
			unset($frd['password']);
		} else {
			$frd['password'] = bcrypt($frd['password']);
		}

		if (isset($frd['is_smsable']))
			$frd['is_smsable'] = true;
		else
			$frd['is_smsable'] = false;

		if (isset($frd['is_emailable']))
			$frd['is_emailable'] = true;
		else
			$frd['is_emailable'] = false;

		try {
			$user->update($frd);
			$message = [
				'flash_message' => [
					'type' => 'success',
					'text' => 'Пользователь успешно обновлен',
				]
			];
		} catch (\Exception $exception) {
			Log::critical('method userController@update failed', ['message' => $exception->getMessage(), 'line' => $exception->getLine(), 'code' => $exception->getCode()]);
		}

		return redirect()->back()->with('flash_message', $message['flash_message']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Users\User $user
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(User $user) {
		//
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function admin(Request $request) {
		$balance = $this->smsApi;

		return view('adminPanel.admin', compact('balance'));
	}
}
