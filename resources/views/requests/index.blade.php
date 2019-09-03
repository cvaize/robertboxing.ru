<?php
/**
 * @var \App\Models\Requests\RequestSite $request
 */
?>
@extends('adminPanel.admin')

@section('content')
	<div class='offset-lg-2 col-lg-8 mb-lg-4 mb-2 px-0'>
		<div class='row'>
			<div class='col-lg text-center'>
				<h1>Заявки</h1>
			</div>
		</div>
	</div>
	<div class='offset-lg-2 col-lg-8 mb-lg-3 mb-4 px-0'>
		@include('adminPanel.modules._searchPanel', [
			'route' => 'requests.index',
			'selectFeedback' => false,
			'placeholder' => 'id, Имя, телефон'
		])
	</div>

	<div class='user-requests'>
		@forelse($requestsSites as $request)
			<div class='offset-lg-2 col-lg-8'>
				<div class='row user__request p-3 mb-4 border'>
					<div class='col-lg-1 user__request-id text-center'>{{ $request->getKey() }}</div>
					<div class='col-lg mt-lg-0 mt-2 user__request-name text-center'>{{ $request->getName() }}</div>
					<div class='col-lg user__request-phone text-center'>{{ $request->getPhone() }}</div>
					<div class='col-lg user__request-phone text-center'>{{ $request->getDate() }}</div>
					@if($request->isSmsed())
						<div class='col-lg-1 col-6 mt-lg-0 mt-2 user__request-sms--active text-center' title='Sms: доставлено'>
							<i class="fas fa-lg fa-comments"></i>
						</div>
					@else
						<div class='col-lg-1 col-6 mt-lg-0 mt-2 user__request-sms text-center' title='Sms: не доставлено'>
							<i class="fas fa-lg fa-comments"></i>
						</div>
					@endif
					@if($request->isEmailed())
						<div class='col-lg-1 col-6 mt-lg-0 mt-2 user__request-email--active text-center' title='Email: доставлено'>
							<i class="fas fa-lg fa-at"></i>
						</div>
					@else
						<div class='col-lg-1 col-6 mt-lg-0 mt-2 user__request-email text-center' title='Email: не доставлено'>
							<i class="fas fa-lg fa-at"></i>
						</div>
					@endif
					{{--<div class='col-1 user__edit text-center' title='Редактировать'>
						<a class='btn btn-outline-success btn' href='{{ route('requests.show', $request) }}'>
							<i class="fas fa-pencil-alt"></i>
						</a>
					</div>--}}
					@include('adminPanel.modules._userInfo', [
						'obj' => $request
					])
				</div>
			</div>
		@empty
			<div class='offset-lg-2 col-lg-8'>
				<div class='row user-request p-3 mb-4 border'>
					<div class='col user-request__empty text-center'>Заявки пользователей отсутствуют</div>
				</div>
			</div>
		@endforelse
		<div class="d-flex justify-content-center">
			{!! $requestsSites->appends($frd ?? [])->render('pagination::bootstrap-4') !!}
		</div>
	</div>
@stop