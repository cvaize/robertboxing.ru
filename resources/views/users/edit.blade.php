<?php
/**
 * @var \App\Models\Users\User $user
 */
?>
@extends('adminPanel.admin')

@section('content')
	@include('adminPanel.modules._flashMessages')

	<div class='row mb-2'>
		@include('adminPanel.modules._backButton', [
			'route' => route('users.index')
		])
	</div>

	<div class='row no-gutters mb-4'>
		<div class='col-lg text-center'>
			<h2>Редактирование пользователя</h2>
		</div>
	</div>

	{{ Form::model($user, ['route' => ['users.update', $user], 'method' => 'PATCH']) }}

	<div class='users'>
		<div class='col-lg-12 px-0'>
			<div class='user-edit p-0 mb-lg-2 mb-0'>
				<div class='form-row'>
					<div class='form-group col-lg-4'>
						<label for="f_name">Имя</label>
						<input type='text' class='form-control' id='f_name' name='f_name' placeholder='Имя' value='{{ $user->getFirstName() }}'>
					</div>
					<div class='form-group col-lg-4'>
						<label for="phone">Телефон</label>
						<input type='text' class='form-control' id='phone' name='phone' placeholder='Телефон' value='{{ $user->getPhone() }}'>
					</div>
					<div class='form-group col-lg-4'>
						<label for="email">Email</label>
						<input type='email' class='form-control' id='email' name='email' placeholder='Email' value='{{ $user->getEmail() ?? 'Отсутсвует' }}'>
					</div>
				</div>
				<div class='form-row'>
					<div class='form-group col-lg-6'>
						<label for="password">Новый пароль</label>
						<input type='password' class='form-control' id='password' name='password' placeholder='Пароль'>
					</div>
					<div class='form-group col-lg-6'>
						<label for="password_confirmation">Подтверждение пароля</label>
						<input type='password' class='form-control' id='password_confirmation' name='password_confirmation' placeholder='Подтверждение пароля'>
					</div>
				</div>
				<div class='row mt-lg-4 mt-0'>
					<div class='col-lg text-center'>
						<div class='form-group'>
							<div class="form-check">
								@if($user->isSmsable())
									<input class="form-check-input" type="checkbox" id="is_smsable" name='is_smsable' checked>
								@else
									<input class="form-check-input" type="checkbox" id="is_smsable" name='is_smsable'>
								@endif
								<label class="form-check-label" for="is_smsable">
									Отправлять SMS
								</label>
							</div>
						</div>
					</div>
					<div class='col-lg text-center'>
						<div class='form-group'>
							<div class="form-check">
								@if($user->isEmailable())
									<input class="form-check-input" type="checkbox" id="is_emailable" name='is_emailable' checked>
								@else
									<input class="form-check-input" type="checkbox" id="is_emailable" name='is_emailable'>
								@endif
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
	<div class='offset-lg-5 col-lg-2 p-0 text-center' title='Сохранить'>
		<button class="btn btn-outline-primary btn-block" type='submit'>
			Сохранить
		</button>
	</div>

	{{ Form::close() }}
@stop