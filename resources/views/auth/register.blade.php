@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">Register</div>

					<div class="panel-body">
						<form class="form-horizontal" method="POST" action="{{ route('register') }}">
							{{ csrf_field() }}

							<div class="form-group{{ $errors->has('l_name') ? ' has-error' : '' }}">
								<label for="l_name" class="col-md-4 control-label">Last name</label>

								<div class="col-md-6">
									<input id="l_name" type="text" class="form-control" name="l_name" value="{{ old('l_name') }}" required autofocus>

									@if ($errors->has('l_name'))
										<span class="help-block">
                        <strong>{{ $errors->first('l_name') }}</strong>
                    </span>
									@endif
								</div>
							</div>

							<div class="form-group{{ $errors->has('f_name') ? ' has-error' : '' }}">
								<label for="f_name" class="col-md-4 control-label">First name</label>

								<div class="col-md-6">
									<input id="f_name" type="text" class="form-control" name="f_name" value="{{ old('f_name') }}" required autofocus>

									@if ($errors->has('f_name'))
										<span class="help-block">
                        <strong>{{ $errors->first('f_name') }}</strong>
                    </span>
									@endif
								</div>
							</div>

							<div class="form-group{{ $errors->has('m_name') ? ' has-error' : '' }}">
								<label for="m_name" class="col-md-4 control-label">Middle name</label>

								<div class="col-md-6">
									<input id="m_name" type="text" class="form-control" name="m_name" value="{{ old('m_name') }}" required autofocus>

									@if ($errors->has('m_name'))
										<span class="help-block">
                        <strong>{{ $errors->first('m_name') }}</strong>
                    </span>
									@endif
								</div>
							</div>

							<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
								<label for="phone" class="col-md-4 control-label">Phone</label>

								<div class="col-md-6">
									<input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required autofocus>

									@if ($errors->has('phone'))
										<span class="help-block">
                        <strong>{{ $errors->first('phone') }}</strong>
                    </span>
									@endif
								</div>
							</div>

							<div class="form-group{{ $errors->has('telegram') ? ' has-error' : '' }}">
								<label for="telegram" class="col-md-4 control-label">Telegram</label>

								<div class="col-md-6">
									<input id="telegram" type="text" class="form-control" name="telegram" value="{{ old('telegram') }}" required autofocus>

									@if ($errors->has('telegram'))
										<span class="help-block">
                        <strong>{{ $errors->first('telegram') }}</strong>
                    </span>
									@endif
								</div>
							</div>

							<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
								<label for="email" class="col-md-4 control-label">E-Mail</label>

								<div class="col-md-6">
									<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

									@if ($errors->has('email'))
										<span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
									@endif
								</div>
							</div>

							<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
								<label for="password" class="col-md-4 control-label">Password</label>

								<div class="col-md-6">
									<input id="password" type="password" class="form-control" name="password" required>

									@if ($errors->has('password'))
										<span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

								<div class="col-md-6">
									<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-6 col-md-offset-4">
									<button type="submit" class="btn btn-primary">
										Register
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
