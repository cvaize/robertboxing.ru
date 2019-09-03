<?php
/**
 * @var \App\Models\Apis\SmsApi $balance
 */
?>

<nav class="js-navbar bg-white">
	<div class="container-fluid container-fluid--max navbar navbar-expand-lg">
		<button class="navbar-toggler order-2" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
			<svg width="30" height="30" viewBox="0 0 49 44" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M0 18.9233L48.8891 2.9819L49 0.5L2.25585 12.1767L0 18.9233Z" fill="#FF7300"/>
				<path d="M0 31.2302L48.8891 15.2517L49 12.7698L2.25585 24.4836L0 31.2302Z" fill="#FF7300"/>
				<path d="M0 43.5L48.8891 27.5586L49 25.0767L2.25585 36.7534L0 43.5Z" fill="#FF7300"/>
			</svg>
		</button>

		<div class="collapse navbar-collapse order-3 order-lg-2" id="navbarToggler">
			@auth
				<ul class="navbar-nav text-right ml-auto mt-2 mt-lg-0">
					<li class="nav-item">
						<a class="nav-link js-link-anchor" href="{{ route('welcome') }}">На главную</a>
					</li>
				</ul>
			@endauth
			<ul class="navbar-nav text-right ml-auto mt-2 mt-lg-0">
				@guest
					<li class="nav-item">
						<a class="nav-link js-link-anchor" href="{{ route('welcome') }}">На главную</a>
					</li>
				@else
					@if(null !== $balance)
						@if($balance->getBalance() === 'Error')
							<span class="nav-text">
								<a class="nav-text js-link-anchor" href="#">
									<span class='text-danger'>— смс</span>
								</a>
							</span>
						@else
							<span class="nav-text">
								{{ $balance->getAvailableSmsQuantity() }} смс
							</span>
						@endif
					@else
						<span class="nav-text">
							<a class="nav-text js-link-anchor" href="#">
								<span class='text-danger'>— смс</span>
							</a>
						</span>
					@endif
					<li class="nav-item">
						<a class="nav-link{{ (URL::current() === route('requests.index') || URL::current() === route('admin')) ? '--active' : '' }} js-link-anchor" href="{{ route('requests.index') }}">Заявки</a>
					</li>
					<li class="nav-item">
						<a class="nav-link{{ URL::current() === route('feedback.index') ? '--active' : '' }} js-link-anchor" href="{{ route('feedback.index') }}">Отзывы</a>
					</li>
					<li class="nav-item">
						<a class="nav-link{{ URL::current() === route('users.index') ? '--active' : '' }} js-link-anchor" href="{{ route('users.index') }}">Пользователи</a>
					</li>
					<li class="nav-item mt-lg-0 mt-md-0 mt-sm-0 mt-3">
						<a class="nav-link nav-link--orange font-weight-bold" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
							Выход
						</a>

						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
						</form>
					</li>
				@endguest
			</ul>
		</div>
		@guest
		@else
			<div class="order-1 order-lg-3">
				<div class="nav-item">

				</div>
			</div>
		@endguest
	</div>
</nav>