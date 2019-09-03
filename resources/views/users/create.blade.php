<?php

?>

@extends('adminPanel.admin')

@section('content')
	@include('adminPanel.modules._flashMessages')

	<div class='row no-gutters mb-4'>
		<div class='col-lg'>
			<h2>Создание пользователя</h2>
		</div>
		@include('adminPanel.modules._backButton', [
			'route' => route('users.index')
		])
	</div>

	{{ Form::open(['route' => ['users.store'], 'method' => 'POST']) }}

	<div class='users'>
		<div class='col-lg-12 px-0'>
			<div class='user-create p-0 mb-lg-2 mb-0'>
				<div class='form-row'>
					<div class='form-group col-lg-4'>
						<label for="f_name">Имя</label>
						<input type='text' class='form-control' id='f_name' name='f_name' placeholder='Имя' required>
					</div>
					<div class='form-group col-lg-4'>
						<label for="phone">Телефон</label>
						<input type='text' class='form-control' id='phone' name='phone' placeholder='Телефон' required>
					</div>
					<div class='form-group col-lg-4'>
						<label for="email">Email</label>
						<input type='email' class='form-control' id='email' name='email' placeholder='Email'>
					</div>
				</div>
				<div class='form-row'>
					<div class='form-group col-lg-6'>
						<label for="password">Новый пароль</label>
						<input type='password' class='form-control' id='password' name='password' placeholder='Пароль' required>
					</div>
					<div class='form-group col-lg-6'>
						<label for="password_confirmation">Подтверждение пароля</label>
						<input type='password' class='form-control' id='password_confirmation' name='password_confirmation' placeholder='Подтверждение пароля' required>
					</div>
				</div>
				<div class='row mt-lg-4 mt-0'>
					<div class='col-lg text-center'>
						<div class='form-group'>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="is_smsable" name='is_smsable'>
								<label class="form-check-label" for="is_smsable">
									Отправлять SMS
								</label>
							</div>
						</div>
					</div>
					<div class='col-lg text-center'>
						<div class='form-group'>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="is_emailable" name='is_emailable'>
								<label class="form-check-label" for="is_emailable">
									Отправлять Email
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class='offset-lg-5 col-lg-2 p-0 text-center' title='Создать'>
		<button class="btn btn-outline-primary btn-block" type='submit'>
			Создать
		</button>
	</div>

	{{ Form::close() }}
@stop
