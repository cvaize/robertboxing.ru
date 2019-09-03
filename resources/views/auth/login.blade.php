@extends('adminPanel.admin')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8 offset-md-2">
				<div class="panel panel-default">
					<div class="panel-heading nav-link text-center">Авторизация</div>

					<div class="panel-body">
						<form class="form-horizontal" method="POST" action="{{ route('login') }}">
							{{ csrf_field() }}

							<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
								<label for="phone" class="col-md-4 offset-md-3 control-label">Телефон</label>

								<div class="col-md-6 offset-md-3">
									<input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required autofocus>

									@if ($errors->has('phone'))
										<span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
									@endif
								</div>
							</div>

							<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
								<label for="password" class="col-md-4 offset-md-3 control-label">Пароль</label>

								<div class="col-md-6 offset-md-3">
									<input id="password" type="password" class="form-control" name="password" required>

									@if ($errors->has('password'))
										<span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-6 offset-md-3">
									<div class="checkbox">
										<label>
											<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Запомнить меня
										</label>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-8 offset-md-3">
									<button type="submit" class="btn btn-primary">
										Войти
									</button>

									<a class="btn btn-link" href="{{ route('password.request') }}">
										Забыли пароль?
									</a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
