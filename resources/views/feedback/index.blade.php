<?php
/**
 * @var \App\Models\Requests\Feedback $feedbackItem
 */
?>
@extends('adminPanel.admin')

@section('content')
	<div class='offset-lg-2 col-lg-8 mb-lg-4 mb-2 px-0'>
		<div class='row'>
			<div class='col-lg text-center'>
				<h1>Отзывы</h1>
			</div>
		</div>
	</div>
	<div class='offset-lg-2 col-lg-8 mb-lg-3 mb-4  px-0'>
		@include('adminPanel.modules._searchPanel', [
			'route' => 'feedback.index',
			'selectFeedback' => true,
			'placeholder' => 'id, Текст, Имя'
		])
	</div>

	<div class='user-requests'>
		@forelse($feedback as $feedbackItem)
			<div class='offset-lg-2 col-lg-8'>
				<div class='row user__feedback p-3 mb-4 border'>
					<div class='col-lg-1 user__feedback-id text-center'>{{ $feedbackItem->getKey() }}</div>
					<div class='col-lg-2 user__feedback-name text-center'>{{ $feedbackItem->user()->get()[0]->getFirstName() }}</div>
					<div class='col-lg col-12 user__feedback-text text-center'>{{ $feedbackItem->getShortText() }}</div>
					<div class='col-lg-1 col-6 mt-lg-0 mt-2 {{ $feedbackItem->statusClass() }} text-center' title='Sms: доставлено'>
						<i class="fas fa-lg fa-clipboard-check"></i>
					</div>
					<div class='col-lg-1 col-6 mt-lg-0 mt-2 user__edit text-center' title='Редактировать'>
						<a class='btn btn-outline-success' href='{{ route('feedback.edit', $feedbackItem) }}'>
							<i class="fas fa-pencil-alt"></i>
						</a>
					</div>
					@include('adminPanel.modules._userInfo', [
						'obj' => $feedbackItem
					])
				</div>
			</div>
		@empty
			<div class='offset-lg-2 col-lg-8'>
				<div class='row user-request p-3 mb-4 border'>
					<div class='col user-request__empty text-center'>Отзывы отсутствуют</div>
				</div>
			</div>
		@endforelse
			<div class="d-flex justify-content-center">
				{!! $feedback->appends($frd ?? [])->render('pagination::bootstrap-4') !!}
			</div>
	</div>
@stop