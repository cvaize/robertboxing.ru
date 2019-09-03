<?php
/**
 * @var \App\Models\Users\User $user
 */
?>
@extends('adminPanel.admin')

@section('content')
	<div class='offset-lg-2 col-lg-8 mb-lg-3 mb-2'>
		<div class='row'>
			<div class='col-lg text-center'>
				<h1>Пользователи</h1>
			</div>
		</div>
	</div>
	<div class='row mb-3 justify-content-between'>
		<div class='col-lg-8 mb-lg-0 mb-2 col-12'>
			@include('adminPanel.modules._searchPanel', [
				'route' => 'users.index',
				'selectFeedback' => false,
				'placeholder' => 'Имя, телефон, email'
			])
		</div>
		<div class='col-lg-auto col-12'>
			<a href='{{ route('users.create') }}' class='btn btn-block btn-outline-success'>Добавить пользователя в
				систему</a>
		</div>
	</div>
	<div class='users'>
		@forelse($users as $user)
			<div class='col-lg-12'>
				<div class='row user p-3 mb-4 border'>
					<div class='col-lg col-md-4 col-sm-4 col-12 col-sm user-name text-center' style='margin: auto 0;'>{{ $user->getFirstName() }}</div>
					<div class='col-lg col-md-4 col-sm-4 col-12 col-sm user-phone text-center' style='margin: auto 0;'>{{ $user->getPhone() }}</div>
					<div class='col-lg col-md-4 col-sm-4 col-12 col-sm user-email text-center' style='margin: auto 0;'>{{ $user->getEmail() ?? 'Отсутсвует' }}</div>
					@if($user->isSmsable())
						<div class='col-lg-1 col-md-4 col-sm-4 col mt-lg-0 mt-2 user-smsable--active text-center' title='Для пользователя отправляется СМС'>
							<i class="fas fa-lg fa-comments"></i>
						</div>
					@else
						<div class='col-lg-1 col-md-4 col-sm-4 col mt-lg-0 mt-2 user-smsable text-center' title='Для пользователя не отправляется СМС'>
							<i class="fas fa-lg fa-comments"></i>
						</div>
					@endif
					@if($user->isEmailable())
						<div class='col-lg-1 col-md-4 col-sm-4 col mt-lg-0 mt-2 user-emailable--active text-center' title='Для пользователя отправляется Email'>
							<i class="fas fa-lg fa-at"></i>
						</div>
					@else
						<div class='col-lg-1 col-md-4 col-sm-4 col mt-lg-0 mt-2 user-emailable text-center' title='Для пользователя не отправляется Email'>
							<i class="fas fa-lg fa-at"></i>
						</div>
					@endif
					<div class='col-lg-1 col-md-4 col-sm-4 col mt-lg-0 mt-2 user-edit text-center' title='Редактировать'>
						<a class='btn btn-outline-success btn' href='{{ route('users.edit', $user) }}'>
							<i class="fas fa-pencil-alt"></i>
						</a>
					</div>
				</div>
			</div>
		@empty
			<div class='col-lg-12'>
				<div class='row user-request p-3 mb-4 border'>
					<div class='col user-request__empty text-center'>Пользователи отсутствуют</div>
				</div>
			</div>
		@endforelse
		<div class="d-flex justify-content-center">
			{!! $users->appends($frd ?? [])->render('pagination::bootstrap-4') !!}
		</div>
	</div>
@stop