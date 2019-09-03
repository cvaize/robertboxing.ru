<?php
/**
 * @var \App\Models\Requests\Feedback $feedback
 */
?>
@extends('adminPanel.admin')

@section('content')
	@include('adminPanel.modules._flashMessages')

	<div class='row mb-2'>
		@include('adminPanel.modules._backButton', [
			'route' => route('feedback.index')
		])
	</div>

	<div class='row no-gutters mb-5 px-0'>
		<div class='col-lg text-center'>
			<h2>Редактирование отзыва</h2>
		</div>
	</div>

	<div class='user-requests'>

		{{ Form::model($feedback, ['route' => ['feedback.update', $feedback], 'method' => 'PATCH']) }}

		<div class='offset-lg-2 col-lg-8 px-0'>
			<div class='row mb-2'>
				<div class='col-lg text-center'>
					<h1><i>{{ $feedback->user()->get()[0]->getFirstName() }}</i></h1>
				</div>
			</div>
			<div class='row user__feedback mb-5'>
				<div class='col-lg user__feedback-text text-center'>{{ $feedback->getText() }}</div>
			</div>
			<div class='row user__feedback-approve'>
				<div class='col-lg user__feedback-approve--yes text-center'>
					<div class='form-group'>
						<div class="form-check">
							<input class="form-check-input" type="radio" id="status-approve" name='status_id' value='2' {{ ($feedback->getStatus() === 2) ? 'checked' : '' }}>
							<label class="form-check-label" for="status-approve">
								Одобрено
							</label>
						</div>
					</div>
				</div>
				<div class='col-lg mb-3 user__feedback-approve--no text-center'>
					<div class='form-group'>
						<div class="form-check">
							<input class="form-check-input" type="radio" id="status-not-approve" name='status_id' value='3' {{ ($feedback->getStatus() === 3) ? 'checked' : '' }}>
							<label class="form-check-label" for="status-not-approve">
								Не одобрено
							</label>
						</div>
					</div>
				</div>
			</div>
			<div class='row'>
				<div class='offset-lg-5 col-lg-2'>
					<button class='btn btn-block btn-outline-primary' type='submit'>Сохранить</button>
				</div>
			</div>
		</div>

		{{ Form::close() }}

	</div>
@stop