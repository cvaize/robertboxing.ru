<?php

namespace App\Http\Controllers\Auth;

use App\Models\Users\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller {
	/*
	|--------------------------------------------------------------------------
	| Register Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users as well as their
	| validation and creation. By default this controller uses a trait to
	| provide this functionality without requiring any additional code.
	|
	*/

	use RegistersUsers;

	/**
	 * Where to redirect users after registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/admin/requests';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware('guest');
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data) {
		return Validator::make($data, [
				'l_name' => 'required|string|max:255',
				'f_name' => 'required|string|max:255',
				'm_name' => 'string|max:255|nullable',
				'phone' => 'string|max:255|nullable',
				'telegram' => 'string|max:255|nullable',
				'email' => 'required|string|email|max:255|unique:users',
				'password' => 'required|string|min:6|confirmed',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array $data
	 * @return \App\Models\Users\User
	 */
/*	protected function create(array $data) {
		return User::create([
				'l_name' => $data['l_name'],
				'f_name' => $data['f_name'],
				'm_name' => $data['m_name'],
				'email' => $data['email'],
				'phone' => $data['phone'],
				'telegram' => $data['telegram'],
				'password' => bcrypt($data['password']),
		]);
	}*/
}
