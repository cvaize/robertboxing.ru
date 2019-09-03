<?php
/**
 * @var \App\Models\Apis\TrainingApi  $trainingItem
 * @var \App\Models\Requests\Feedback $feedbackItem
 */
$slider = [
	[
		'img'  => '/img/welcome/slider/new1.jpg',
		'text' => 'Бокс',
		'link' => '#',
	],
	[
		'img'  => '/img/welcome/slider/new2.jpg',
		'text' => 'Силовые',
		'link' => '#',
	],
	[
		'img'  => '/img/welcome/slider/new3.jpg',
		'text' => 'Коррекция фигуры',
		'link' => '#',
	],
];
$version = 1;
?>
@extends('layouts.app')

@section('content')
	<div id="services" class="container-fluid">
		<div class="row no-gutters">
			<div class="col-md-4 d-flex align-items-center justify-content-center mb-5">
				<div class="main__big-logo">
					<a href='{{ route('welcome') }}' rel='nofollow'>
						<img class="w-100 lazy" data-src="/img/welcome/big-logo.svg" alt="РОБЕРТ РУСТАМЯН, тренер по боксу">
					</a>
				</div>
			</div>
			<div class="col-md-8 mb-5">
				<div class="js-scroll-bar scroll-bar__wrapper scrollbar-chrome main__scroll-bar-container">
					<div class="scroll-bar">
						@foreach($slider as $item)
							<div class="scroll-bar__item d-flex align-items-center justify-content-centerl lazy" data-bg='url({{$item['img']}}?v={{ $version }})' style="background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3));">
								<div class="text-uppercase text-skew-effect w-100 h-100 d-block" data-href="{{$item['link']}}">
									<svg viewBox="0 0 300 300">
										<text width="300" transform="skewX(-17)">
											<textPath xlink:href="#curve" startOffset="54%">
												{{$item['text']}}
											</textPath>
										</text>
									</svg>
								</div>
							</div>
						@endforeach
						<div class="scroll-bar__item d-flex align-items-center justify-content-center">
							<div class="text-uppercase text-skew-effect w-100 h-100 d-block" data-href="#">
								<svg viewBox="0 0 300 300">
									<text width="300" transform="skewX(-17)">
										<textPath xlink:href="#curve" startOffset="54%">
											<a id='training-link'>Подобрать тренировку</a>
										</textPath>
									</text>
								</svg>
								<div class="scroll-bar__item__subtext">
									Расскажите о цели,<br>
									и я подберу программу
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="d-none">
		<svg viewBox="0 0 300 300">
			<path id="curve" stroke-width="0" stroke="transparent" d="M -250,255 L 550,5"/>
		</svg>
		<svg viewBox="0 0 600 600">
			<path id="curve2" stroke-width="0" stroke="transparent" d="M -550,255 L 850,5"/>
		</svg>
		<svg>
			<filter xmlns="http://www.w3.org/2000/svg" id="dropshadow" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
				<feComponentTransfer in="SourceAlpha">
					<feFuncR type="discrete" tableValues="1"/>
					<feFuncG type="discrete" tableValues="0.47"/>
					<feFuncB type="discrete" tableValues="0"/>
				</feComponentTransfer>
				<feGaussianBlur stdDeviation="10"/>
				<feOffset dx="0" dy="10" result="shadow"/>
				<feComposite in="SourceGraphic" in2="shadow" operator="over"/>
			</filter>
			<filter xmlns="http://www.w3.org/2000/svg" id="dropshadow-active" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
				<feComponentTransfer in="SourceAlpha">
					<feFuncR type="discrete" tableValues="1"/>
					<feFuncG type="discrete" tableValues="0.47"/>
					<feFuncB type="discrete" tableValues="0"/>
				</feComponentTransfer>
				<feGaussianBlur stdDeviation="5"/>
				<feOffset dx="0" dy="0" result="shadow"/>
				<feComposite in="SourceGraphic" in2="shadow" operator="over"/>
			</filter>
		</svg>
	</div>
	<div class="container-fluid--max">
		<div class="row no-gutters">
			<div class="d-none d-lg-block col-lg-4"></div>
			<div class="col-12 col-lg-8 mb-4">
				<div id='next-training' class="main__box-training d-flex flex-column align-items-center justify-content-center">
					<p class="text-center subtext">
						{{ $nextTraining }}
						{{--<br><b>{{ $nextTrainingTimeLeft }}</b>--}}
						<br>через
						<span id='next-training__time' data-time='{{ $nextTrainingTimeLeft }}' style='font-weight: 700'>00:00:00</span>
					</p>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid--max">
		<div class="row no-gutters">
			<div class="d-none d-lg-block col-lg-4"></div>
			<div class="col-12 col-lg-8">
				<div id='box-request' class="main__box-bg-action d-flex flex-column align-items-center justify-content-center py-5">
					<div class="collapse js-form-requests__collapse--message-success text-center">
						<h4 class="my-5 text-success">
							Заявка успешно отправлена, <br> мы свяжемся с вами в рабочее время!
						</h4>
					</div>
					<div class="collapse js-form-requests__collapse--message-error text-center">
						<h4 class="my-5 text-danger">
							Отправить заявку невозможно, <br> попробуйте позже.
						</h4>
					</div>
					<div class="collapse show js-form-requests__collapse--form">
						<form class="js-form-requests" method="post" action="{{ action('Requests\RequestSiteController@store') }}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="form-group px-sm-3 px-lg-5">
								<input id='name' name="name" data-name="name" type="text"
								       maxlength="255" class="form-control form-control--main js-form-requests__input" placeholder="Имя">
								<div class="invalid-feedback">Как к вам обращаться?</div>
							</div>
							<div class="form-group px-sm-3 px-lg-5 pb-4">
								<input name="phone" data-name="phone" type="tel"
								       maxlength="255" class="form-control form-control--main js-form-requests__input js-phone-mask" placeholder="Телефон">
								<div class="invalid-feedback">Обязательно укажите телефон</div>
							</div>
							<div class="collapse js-form-requests__collapse">
								<div class="d-flex justify-content-center">
									{!! Captcha::display(['add-js' => false]) !!}
								</div>
								<div class="form-group px-sm-3 px-lg-5">
									<input type="hidden" data-name="g-recaptcha-response" class="form-control">
									<div class="invalid-feedback">Обязательно пройдите капчу</div>
								</div>
							</div>
							<div class="mt-3 btn-warning--orange__filter">
								<button class="btn btn-warning btn-warning--orange" type="submit">
									ЗАПИСАТЬСЯ НА ТРЕНИРОВКУ
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			{{--<div class="col-12">
				<p class="text-center subtext mt-5 mb-0">
					Приходите, посмотрите: <br>
					Фитнес-клуб
					<span style='color: #FF7300'>«</span><a class='halk-link' href='http://hulk.fit/' target='_blank' rel="nofollow">ХАЛК</a><span style='color: #FF7300'>»</span>,
					ТЦ Квадрат, ул. Пархоменко, 41 <br>
					Работаем с 8:00 до 23:00 ежедневно
				</p>
			</div>--}}
			@if(null !== $training)
				<div id='schedule-training' class="offset-lg-3 col-lg-6 offset-md-2 col-md-8 mt-5 main__schedule-training">
					<p class='text-center main__schedule-training__title'>Расписание</p>
					@foreach($training as $trainingItem)
						@if(null !== $trainingItem->getName())
							<div class="row text-left subtext pl-md-0 pl-lg-2 pl-0 ">
								<div class='col-lg-4 pr-lg-1 col-md-4 pr-md-1 col-sm-4 pr-sm-1 col-4 pr-1'>
									<div class='ml-auto no-gutters row main__schedule-training__title-day justify-content-between'>
										<span>{{ $trainingItem->getDay() }}</span>
										<span>{{ $trainingItem->getStartAt() }}</span>
									</div>
								</div>
								—
								<div class='col-lg pl-lg-1 pr-lg-3 text-lg-left col-md col-7 pl-md-1 pl-sm-1 pl-1 pr-0'>{{ $trainingItem->getName() }}</div>
							</div>
						@else
							{{--<div class="row text-left subtext pl-md-0 pl-lg-2 pl-0 ">--}}
								{{--<div class='col-lg-4 pr-lg-1 col-md-4 pr-md-1 col-sm-4 pr-sm-1 col-4 pr-1'>--}}
									{{--<div class='ml-auto no-gutters row main__schedule-training__title-day justify-content-start'>--}}
										{{--—--}}
									{{--</div>--}}
								{{--</div>--}}
							{{--</div>--}}
						@endif
					@endforeach
				</div>
			@endif
			<div id="about" class="col-12">
				<div class="d-flex align-items-center justify-content-center">
					<div class="text-rotate-skew text-rotate-skew__h1 text-uppercase text-center color-orange text-skew-effect w-100 h-100 font-italic">
						Заряжаю на тренировку
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid main__bg-tree mt-md-5">
		<div class="main__bg-tree__img lazy" data-bg="url(/img/welcome/main.jpg?v={{ $version }})"></div>
		<div class="container">
			<div class="row no-gutters">
				<div class="col-lg-6">
					<div class="text-white main__bg-tree__text">
						<p>
							Тренирую боксу и&nbsp;сам участвую в&nbsp;соревнованиях. Это преимущество в&nbsp;обучении:
							рассказываю, как действуют соперники в&nbsp;бою, и&nbsp;испытываю знания на&nbsp;практике.
						</p>
						<p>
							Боксу я&nbsp;учу творчески, ведь для победы в&nbsp;бою нужна фантазия: неожиданные ходы,
							меняющаяся стратегия боя, понимание психологии противника.
						</p>
						<p>
							Мы&nbsp;тренируемся так, чтобы у&nbsp;вас не&nbsp;возникало отвращения к&nbsp;физическому
							напряжению. Тренировки проходят весело и&nbsp;в&nbsp;поту! Каждую неделю придумываю новое
							исполнение одной и&nbsp;той&nbsp;же техники. Боксерский удар отрабатываем на&nbsp;мячиках,
							цепях, людях, грушах. Интерес и&nbsp;мышцы всегда в&nbsp;тонусе! Часть упражнений я&nbsp;показываю
							на&nbsp;себе, для наглядности.
						</p>
					</div>
					<div class="text-white main__bg-tree__text">
						<p>
							<a class='robert-link' href="https://vk.com/id311663076" target='_blank' rel="nofollow">Роберт
								Рустамян</a>
							<a class="ml-3 link-hover-orange" href="https://vk.com/robertboxing" target='_blank' rel="nofollow">
								<svg id='vk-logo' aria-labelledby='vk-logo__title' width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
									<title id="vk-logo__title">Группа в ВК</title>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M27.4948 0.322998H14.7297C3.07972 0.322998 0.321289 3.08143 0.321289 14.7314V27.4965C0.321289 39.1464 3.07972 41.9049 14.7297 41.9049H27.4948C39.1447 41.9049 41.9032 39.1464 41.9032 27.4965V14.7314C41.9032 3.08143 39.1154 0.322998 27.4948 0.322998ZM33.892 29.9908H30.8694C29.725 29.9908 29.3728 29.0811 27.3187 26.9976C25.5287 25.2663 24.7363 25.0315 24.2962 25.0315C23.6799 25.0315 23.5038 25.2076 23.5038 26.0586V28.7877C23.5038 29.5213 23.2691 29.9615 21.3323 29.9615C18.1337 29.9615 14.583 28.0247 12.0886 24.4153C8.33248 19.1332 7.3054 15.1716 7.3054 14.3499C7.3054 13.9097 7.48147 13.4989 8.33248 13.4989H11.355C12.118 13.4989 12.4114 13.851 12.7049 14.6727C14.2015 18.9864 16.6958 22.7719 17.7229 22.7719C18.1044 22.7719 18.2804 22.5959 18.2804 21.6275V17.167C18.1631 15.1129 17.0773 14.9368 17.0773 14.2032C17.0773 13.851 17.3707 13.4989 17.8403 13.4989H22.5942C23.2397 13.4989 23.4745 13.851 23.4745 14.614V20.6297C23.4745 21.2753 23.768 21.5101 23.944 21.5101C24.3255 21.5101 24.6483 21.2753 25.3526 20.5711C27.5241 18.1354 29.0794 14.3793 29.0794 14.3793C29.2848 13.9391 29.637 13.5283 30.3999 13.5283H33.4225C34.3322 13.5283 34.5376 13.9978 34.3322 14.6434C33.9507 16.4041 30.2532 21.6275 30.2532 21.6275C29.9304 22.1557 29.813 22.3904 30.2532 22.9773C30.576 23.4175 31.6324 24.3272 32.3367 25.1489C33.6279 26.6161 34.6256 27.8486 34.8897 28.6996C35.1832 29.5506 34.743 29.9908 33.892 29.9908Z" fill="white"/>
								</svg>
							</a>
							<a class="ml-2 link-hover-orange" href="https://www.instagram.com/rustamyan_team/?igshid=1c6yleai66jye" target='_blank' rel="nofollow">
								<svg id='instagram-logo' aria-labelledby='instagram-logo__title' width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
									<title id="instagram-logo__title">Инстаграм</title>
									{{--<path fill="white" d="M29.5,0h-17C5.6,0,0,5.6,0,12.5v17C0,36.4,5.6,42,12.5,42h17C36.4,42,42,36.4,42,29.5v-17--}}
									{{--C42,5.6,36.4,0,29.5,0z M21,31.9c-6,0-10.9-4.9-10.9-10.9S15,10.1,21,10.1S31.9,15,31.9,21S27,31.9,21,31.9z M31.9,12.8--}}
									{{--c-1.4,0-2.6-1.2-2.6-2.6s1.2-2.6,2.6-2.6s2.6,1.2,2.6,2.6S33.3,12.8,31.9,12.8z"/>--}}
									<g>
										<ellipse transform="matrix(0.9732 -0.2298 0.2298 0.9732 -4.2687 5.3937)" fill="#FFFFFF" cx="21" cy="21" rx="7" ry="7"/>
										<path fill="#FFFFFF" d="M41.9,12.3c-0.1-2.2-0.5-3.8-1-5.1c-0.5-1.4-1.3-2.6-2.4-3.7c-1.2-1.2-2.3-1.9-3.7-2.4
		c-1.3-0.5-2.9-0.9-5.1-1C27.4,0,26.7,0,21,0s-6.4,0-8.7,0.1c-2.2,0.1-3.8,0.5-5.1,1C5.9,1.6,4.7,2.4,3.5,3.5S1.6,5.9,1.1,7.2
		c-0.5,1.3-0.9,2.9-1,5.1C0,14.6,0,15.3,0,21s0,6.4,0.1,8.7c0.1,2.2,0.5,3.8,1,5.1c0.5,1.4,1.3,2.6,2.4,3.7c1.2,1.2,2.3,1.9,3.7,2.4
		c1.3,0.5,2.9,0.9,5.1,1c2.2,0.1,3,0.1,8.7,0.1s6.4,0,8.7-0.1c2.2-0.1,3.8-0.5,5.1-1c1.4-0.5,2.6-1.3,3.7-2.4
		c1.2-1.2,1.9-2.3,2.4-3.7c0.5-1.3,0.9-2.9,1-5.1c0.1-2.2,0.1-3,0.1-8.7S42,14.6,41.9,12.3z M21,31.8c-6,0-10.8-4.8-10.8-10.8
		S15.1,10.2,21,10.2S31.8,15.1,31.8,21S27,31.8,21,31.8z M32.2,12.3c-1.4,0-2.5-1.1-2.5-2.5s1.1-2.5,2.5-2.5c1.4,0,2.5,1.1,2.5,2.5
		S33.6,12.3,32.2,12.3z"/>
									</g>
								</svg>
							</a>
							<a class="ml-2 link-hover-orange" href="https://www.youtube.com/channel/UCyuMv8sIthuCu-ZBdMJhQag" target='_blank' rel="nofollow">
								<svg id='youtube-logo' aria-labelledby='youtube-logo__title' width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
									<title id="youtube-logo__title">Youtube</title>
									<path fill="white" d="M31.5,0h-21C4.7,0,0,4.7,0,10.5v21C0,37.3,4.7,42,10.5,42h21C37.3,42,42,37.3,42,31.5V10.5C42,4.7,37.3,0,31.5,0z M34,22
	c0,2.2-0.2,4.4-0.2,4.4s-0.2,1.9-1,2.7c-1,1.1-2.1,1.1-2.6,1.2c-3.7,0.3-9.2,0.3-9.2,0.3s-6.8-0.1-8.9-0.3c-0.6-0.1-1.9-0.1-2.9-1.2
	c-0.8-0.8-1-2.7-1-2.7S8,24.2,8,22V20c0-2.2,0.2-4.4,0.2-4.4s0.2-1.9,1-2.7c1-1.1,2.1-1.1,2.6-1.2c3.7-0.3,9.2-0.3,9.2-0.3
	s5.5,0,9.2,0.3c0.5,0.1,1.6,0.1,2.6,1.2c0.8,0.8,1,2.7,1,2.7S34,17.8,34,20V22z"/>
									<g>
										<polygon fill="white" points="18.3,24.5 25.4,20.7 18.3,16.9 	"/>
									</g>
								</svg>
							</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="text-center my-5">
					<div class="collapse__btn" data-toggle="collapse" href="#collapse" role="button" aria-expanded="false" aria-controls="collapseExample">
						20-летний опыт
					</div>
				</div>
				<div class="collapse subtext text-left" id="collapse">
					<div class="row">
						<div class="col-lg-6">
							<p>
								Я стараюсь обучить своих спортсменов всем азам боксёрской деятельности, начиная с
								классики (стойка, удары, перемещения и защита)
								до отработки реакции.
							</p>
							<p>
								Я&nbsp;тренер, который для практики своих бойцов выходит на&nbsp;ринг в&nbsp;спарринги
								лично! Так я&nbsp;пытаюсь наглядно обучить всем нюансам и&nbsp;подводным камням в&nbsp;боксе.
							</p>
							<p>
								На&nbsp;тренировках включаю разнообразие, все стандартное превращаю в&nbsp;нестандартное!
								Использую инвентарь и&nbsp;подход к&nbsp;упражнениям нестандартно.
							</p>
						</div>
						<div class="col-lg-6">
							<p>
								Очень редко встретишь на&nbsp;моих тренировках повторяющийся материал, всегда что-то
								новое и&nbsp;интересное.
							</p>
							<p>
								Хочу, чтобы люди увидели, что бокс&nbsp;&mdash; это не&nbsp;мордобой, бокс&nbsp;&mdash;
								это спорт сильных, умных! Бокс&nbsp;&mdash; это искусство! Бокс&nbsp;&mdash; это спорт
								джентельменов!
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class='container'>
		<div class='row'>
			<div id="reviews" class="col-12">
				<div class="d-flex align-items-center justify-content-center">
					<div class="text-rotate-skew text-rotate-skew__h2 text-uppercase text-center color-orange text-skew-effect w-100 h-100 font-italic">
						Отзывы
					</div>
				</div>
			</div>
		</div>
		<div class='row'>
			<div class="col-md-2 col-lg-3 d-none d-md-flex align-items-start justify-content-center">
				@if(count($feedback) > 1)
					<svg class="link-hover-orange main__reviews__arrow js-main__reviews__arrow--prev mt-3 mt-md-5"
					     width="139" height="230" viewBox="0 0 139 230" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M0 152.623L3 131.531L73 21.0482L64 76.2894L29 133.539L49 174.719L41 229.96L0 152.623ZM66 132.535L69 111.443L139 0.960449L131 56.2017L96 113.452L116 154.632L108 209.873L66 132.535Z" fill="#FF7800"/>
					</svg>
				@endif
			</div>
			<div class="col-12 col-md-8 col-lg-6 pt-4">
				<div class="js-main__reviews main__reviews owl-carousel owl-theme">
					@forelse($feedback as $feedbackItem)
						<div>
							<p class="main__reviews__text">
								<b>{{ $feedbackItem->user()->get()[0]->getFirstName() }}</b>
								<span class="color-orange"></span>
							</p>
							<p class="main__reviews__text">
								{{ $feedbackItem->getText() }}
							</p>
						</div>
					@empty
						<div>
							<p class="main__reviews__text">
								<b>Анна</b>
							</p>
							<p class="main__reviews__text">
								Роберт, привет ! Очень понравилась вчера тренировка😀. Ты большой молодец и классный
								тренер 🙏.
								Да, сегодня на тренировке опять был эксклюзив 😀. Только на тренировках у Роберта и
								только для самых дисциплинированных возможны такие рождественские подарки: отличный урок
								расслабляющего массажа от двух суперпрофессиональных тренеров: Роберта Рустамяна и
								Антонины Носковой. Спасибо большое ! Это особенное удовольствие: общаться с людьми, у
								которых хочется учиться и которые умеют делится своим опытом !🙏
							</p>
						</div>
					@endforelse
				</div>
			</div>
			<div class="col-md-2 col-lg-3 d-none d-md-flex align-items-start justify-content-center">
				@if(count($feedback) > 1)
					<svg class="link-hover-orange main__reviews__arrow js-main__reviews__arrow--next"
					     width="142" height="230" viewBox="0 0 142 230" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M8 174.719L46 115.46L23 75.285L31 21.0482L76 97.3815L73 118.474L0 229.96L8 174.719ZM74 155.636L112 96.3771L89 56.2017L98 0.960449L142 77.2938L139 98.3859L66 209.873L74 155.636Z" fill="#FF7800"/>
					</svg>
				@endif
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row no-gutters d-flex justify-content-center">
			<div class="col-12 col-lg-8">
				<div id='' class="main__box-feedback-button d-flex flex-column align-items-center justify-content-center py-5">
					<div class="btn-outline-warning--orange__filter__shadow">
						<div class="mt-3 btn-outline-warning--orange__filter">
							<button id='js-form-feedback__button' class="btn btn-outline-warning btn-outline-warning--orange" type='button'>
								ОСТАВИТЬ ОТЗЫВ
							</button>
						</div>
					</div>
				</div>
				<div class='collapse js-form-feedback__collapse'>
					<div class="d-flex flex-column align-items-center justify-content-center">
						<div id='box-feedback' class='main__box-feedback py-5 d-flex flex-column align-items-center justify-content-center col-12'>
							<div class="collapse js-form-feedback__collapse--message-success text-center">
								<h4 class="my-5 text-success">
									Спасибо за отзыв!
								</h4>
							</div>
							<div class="collapse show js-form-feedback__collapse--form col-lg-11">
								<form class="js-form-feedback" method="post" action="{{ action('Requests\FeedbackController@store') }}">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									<div class='form-row mb-3'>
										<div class="form-group offset-lg-2 col-lg-4 mr-lg-4 col-md mr-md-3">
											<input id='name' name="name" data-name="name" type="text"
											       maxlength="255" class="form-control form-control--main js-form-feedback__input" placeholder="Имя">
											<div class="invalid-feedback">Как к вам обращаться?</div>
										</div>
										<div class="form-group col-lg-4 col-md">
											<input name="phone" data-name="phone" type="tel"
											       maxlength="255" class="form-control form-control--main js-form-feedback__input js-phone-mask" placeholder="Телефон">
											<div class="invalid-feedback">Обязательно укажите телефон</div>
										</div>
									</div>
									<div class='textarea-background py-2 mb-3'>
										<div class="form-group">
											<textarea name='feedback-text' class="form-control col-lg-11 js-form-feedback__input" id="feedback-text" rows="3"></textarea>
										</div>
									</div>
									@if(\App\Models\Requests\Feedback::isCaptchaReviews())
										<div class="collapse js-form-feedback__collapse__captcha">
											<div class="d-flex justify-content-center">
												{!! Captcha::display(['add-js' => false]) !!}
											</div>
											<div class="form-group px-sm-3 px-lg-5">
												<input type="hidden" data-name="g-recaptcha-response" class="form-control">
												<div class="invalid-feedback">Обязательно пройдите капчу</div>
											</div>
										</div>
									@endif
									<div class="mt-4 btn-warning--orange__filter text-center">
										<button class="btn btn-warning btn-warning--orange" type="submit">
											ОТПРАВИТЬ ОТЗЫВ
										</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="main__news">
		<div class="main__news__top-arrow">
			<img class="w-100 rotate180 lazy" data-src="/img/welcome/vector-bottom-top.svg">
		</div>
		<div class="main__news__content">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div id="news" class="d-flex align-items-center justify-content-center">
							<div class="text-rotate-skew text-rotate-skew__h1 text-uppercase text-center text-white text-skew-effect w-100 h-100 font-italic">
								Новости роберта
							</div>
						</div>
					</div>
					<div class="col-12 d-flex align-items-center justify-content-center">
						<div class="main__news__content__dots js-main__news__dot" data-select="0" data-social="vk"
						     data-start="{{$vk}}" data-page="1" data-per-page="{{$perPageVk}}" data-final="0">
							ВК
						</div>
						<div class="main__news__content__dots js-main__news__dot active" data-select="1"
						     data-social="instagram" data-start="{{$instagram}}" data-page="1" data-per-page="{{$perPageInstagram}}" data-final="0">
							ИНСТА
						</div>
						<div class="main__news__content__dots js-main__news__dot" data-select="0"
						     data-social="youtube" data-start="{{$youtube}}" data-page="1" data-per-page="{{$perPageYoutube}}" data-final="0">
							ЮТУБ
						</div>
					</div>
					<div class="col-12 col-lg-8 offset-lg-2">
						<div class="js-main__news ">

						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="main__news__bottom-arrow">
			<div class="main__news__bottom-arrow__load-more d-flex align-items-center justify-content-center">
				<div class="js-main__news__load-more link-hover-underline text-rotate-skew text-rotate-skew__h1 text-uppercase text-center color-orange text-skew-effect font-italic cursor-pointer">
					Еще
				</div>
			</div>
			<img class="w-100 lazy" data-src="/img/welcome/vector-bottom-top.svg">
		</div>
	</div>
@endsection
