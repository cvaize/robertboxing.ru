<?php

$slider = [
  [
      'img'=>'/img/welcome/slider/1.png',
      'text'=>'Бокс',
      'link'=>'#',
  ],
  [
      'img'=>'/img/welcome/slider/2.png',
      'text'=>'Силовые',
      'link'=>'#',
  ],
  [
      'img'=>'/img/welcome/slider/3.jpg',
      'text'=>'Коррекция фигуры',
      'link'=>'#',
  ],
];

?>
@extends('layouts.app')

@section('content')
    <div class="container-fluid--max">
        <div class="row no-gutters">
            <div class="col-md-4 d-flex align-items-center justify-content-center mb-4">
                <div class="main__big-logo">
                    <img class="w-100" src="/img/welcome/big-logo.svg" alt="РОБЕРТ РУСТАМЯН, тренер по боксу">
                </div>
            </div>
            <div class="col-md-8 mb-4">
                <div class="js-scroll-bar">
                    <div class="scroll-bar js-scroll-bar__swipe">
                        @foreach($slider as $item)
                            <div class="scroll-bar__item d-flex align-items-center justify-content-center" style="background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url({{$item['img']}});">
                                <a class="text-uppercase text-skew-effect w-100 h-100 d-block" href="{{$item['link']}}">

                                    <svg viewBox="0 0 300 300">
                                        <text width="300" transform="skewX(-17)">
                                            <textPath xlink:href="#curve" startOffset="54%">
                                                {{$item['text']}}
                                            </textPath>
                                        </text>
                                    </svg>

                                </a>
                            </div>
                        @endforeach
                        @foreach($slider as $item)
                                <div class="scroll-bar__item d-flex align-items-center justify-content-center" style="background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url({{$item['img']}});">
                                    <a class="text-uppercase text-skew-effect w-100 h-100 d-block" href="{{$item['link']}}">

                                        <svg viewBox="0 0 300 300">
                                            <text width="300" transform="skewX(-17)">
                                                <textPath xlink:href="#curve" startOffset="54%">
                                                    {{$item['text']}}
                                                </textPath>
                                            </text>
                                        </svg>

                                    </a>
                                </div>
                        @endforeach
                            <div class="scroll-bar__item d-flex align-items-center justify-content-center">
                                <a class="text-uppercase text-skew-effect w-100 h-100 d-block" href="#">

                                    <svg viewBox="0 0 300 300">
                                        <text width="300" transform="skewX(-17)">
                                            <textPath xlink:href="#curve" startOffset="54%">
                                                Подобрать тренировку
                                            </textPath>
                                        </text>
                                    </svg>
                                    <div class="scroll-bar__item__subtext">
                                        Расскажите о цели <br>
                                        и я подберу программу
                                    </div>
                                </a>
                            </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="d-none">
        <svg viewBox="0 0 300 300">
            <path id="curve" stroke-width="0" stroke="transparent" d="M -250,255 L 550,5" />
        </svg>
        <svg viewBox="0 0 600 600">
            <path id="curve2" stroke-width="0" stroke="transparent" d="M -550,255 L 850,5" />
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
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="main__box-bg-action d-flex align-items-center justify-content-center">
                    <div class="my-5 btn-warning--orange__filter">
                        <button class="btn btn-warning btn-warning--orange">
                            ЗАПИСАТЬСЯ НА ТРЕНИРОВКУ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <p class="text-center subtext mt-5 mb-0">
                    Приходите, посмотрите: <br>
                    Фитнес клуб "ХАЛК", ТЦ Квадрат, ул. Пархоменко, 41 <br>
                    Работаем с 8:00 до 23:00 ежедневно
                </p>
            </div>
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-center">
                    <div class="text-rotate-skew text-rotate-skew__h1 text-uppercase text-center color-orange text-skew-effect w-100 h-100 font-italic">
                        Заряжаю на тренировку
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid--max main__bg-tree mt-md-5">
        <div class="main__bg-tree__img"></div>
        <div class="container">
            <div class="row no-gutters">
                <div class="col-lg-6">
                    <div class="text-white main__bg-tree__text">
                        <p>
                            Тренерую боксу и сам участвую в соревнованиях.
                            Это приемущество в обучении: рассказываю как действуют соперники в бою и испытываю знания на практике.
                        </p>
                        <p>
                            Боксу я учу творчески. Ведь для победы в бою нужна фантазия: неожиданные ходы, меняющаяся стратегия боя, понимание психологии противника.
                        </p>
                        <p>
                            Мы тренируемся так, чтобы у вас не возникало отвращения от физического напряжения. Тренировки проходят весело и в поту!
                            Каждую неделю придумываю новое исполнение одной и той же техники. Боксерский удар отрабатываем на мячиках, цепях, людях, грушах.
                            Интерес и мышцы всегда в тонусе! Часть упражнений я показываю на себе для наглядности.
                        </p>
                    </div>
                    <div class="text-white main__bg-tree__text my-0">
                        <p>
                            Роберт Рустамян
                            <a class="ml-5 link-hover-orange" href="#">
                                <svg width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M27.4948 0.322998H14.7297C3.07972 0.322998 0.321289 3.08143 0.321289 14.7314V27.4965C0.321289 39.1464 3.07972 41.9049 14.7297 41.9049H27.4948C39.1447 41.9049 41.9032 39.1464 41.9032 27.4965V14.7314C41.9032 3.08143 39.1154 0.322998 27.4948 0.322998ZM33.892 29.9908H30.8694C29.725 29.9908 29.3728 29.0811 27.3187 26.9976C25.5287 25.2663 24.7363 25.0315 24.2962 25.0315C23.6799 25.0315 23.5038 25.2076 23.5038 26.0586V28.7877C23.5038 29.5213 23.2691 29.9615 21.3323 29.9615C18.1337 29.9615 14.583 28.0247 12.0886 24.4153C8.33248 19.1332 7.3054 15.1716 7.3054 14.3499C7.3054 13.9097 7.48147 13.4989 8.33248 13.4989H11.355C12.118 13.4989 12.4114 13.851 12.7049 14.6727C14.2015 18.9864 16.6958 22.7719 17.7229 22.7719C18.1044 22.7719 18.2804 22.5959 18.2804 21.6275V17.167C18.1631 15.1129 17.0773 14.9368 17.0773 14.2032C17.0773 13.851 17.3707 13.4989 17.8403 13.4989H22.5942C23.2397 13.4989 23.4745 13.851 23.4745 14.614V20.6297C23.4745 21.2753 23.768 21.5101 23.944 21.5101C24.3255 21.5101 24.6483 21.2753 25.3526 20.5711C27.5241 18.1354 29.0794 14.3793 29.0794 14.3793C29.2848 13.9391 29.637 13.5283 30.3999 13.5283H33.4225C34.3322 13.5283 34.5376 13.9978 34.3322 14.6434C33.9507 16.4041 30.2532 21.6275 30.2532 21.6275C29.9304 22.1557 29.813 22.3904 30.2532 22.9773C30.576 23.4175 31.6324 24.3272 32.3367 25.1489C33.6279 26.6161 34.6256 27.8486 34.8897 28.6996C35.1832 29.5506 34.743 29.9908 33.892 29.9908Z" fill="white"/>
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
                                Я стараюсь обучить своих спортсменов всем азам боксёрской деятельности, начиная с классики (стойка, удары, перемещения и защита)
                                до отработки реакции.
                            </p>
                            <p>
                                Я тренер, который для практики своих бойцов выхожу на ринг в спарринги лично! Так я пытаюсь наглядно обучить
                                всем нюансам и подводным камням в боксе.
                            </p>
                            <p>
                                На тренировках включаю разнообразие, все стандартное превращаю в нестандартное!
                                Использовать инвентарь и подход к упражнениям нестандартно!
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <p>
                                На тренировках включаю разнообразие, все стандартное превращаю в нестандартное!
                            </p>
                            <p>
                                Очень редко встретишь на моих тренировках повторяющийся материал, всегда что то новое и интересное!
                            </p>
                            <p>
                                Хочу чтобы люди увидели что бокс это не мордобой, бокс это спорт сильных, умных! Бокс это искусство!
                                Бокс это спорт джентельменов!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-center">
                    <div class="text-rotate-skew text-rotate-skew__h2 text-uppercase text-center color-orange text-skew-effect w-100 h-100 font-italic">
                        Отзывы
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-lg-3 d-none d-md-flex align-items-start justify-content-center">
                <svg class="link-hover-orange main__reviews__arrow js-main__reviews__arrow--prev mt-3 mt-md-5" width="139" height="230" viewBox="0 0 139 230" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 152.623L3 131.531L73 21.0482L64 76.2894L29 133.539L49 174.719L41 229.96L0 152.623ZM66 132.535L69 111.443L139 0.960449L131 56.2017L96 113.452L116 154.632L108 209.873L66 132.535Z" fill="#FF7800"/>
                </svg>
            </div>
            <div class="col-12 col-md-8 col-lg-6 pt-4">
                <div class="js-main__reviews main__reviews owl-carousel owl-theme">
                    @foreach(range(1, 5) as $i)
                        <div>
                            <p class="main__reviews__text">
                                <b>Тоня,</b> <span class="color-orange">тренеруюсь год в группе силовой тренировки</span>
                            </p>
                            <p class="main__reviews__text">
                                Сайт рыбатекст поможет дизайнеру, верстальщику, вебмастеру сгенерировать несколько абзацев
                                более менее осмысленного текста рыбы на русском языке, а начинающему оратору отточить навык
                                публичных выступлений в домашних условиях. При создании генератора мы использовали небезизвестный
                                универсальный код речей. Текст генерируется абзацами случайным образом от двух до десяти предложений в
                                абзаце, что позволяет сделать текст более привлекательным и живым для визуально-слухового восприятия.
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-2 col-lg-3 d-none d-md-flex align-items-start justify-content-center">
                <svg class="link-hover-orange main__reviews__arrow js-main__reviews__arrow--next" width="142" height="230" viewBox="0 0 142 230" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 174.719L46 115.46L23 75.285L31 21.0482L76 97.3815L73 118.474L0 229.96L8 174.719ZM74 155.636L112 96.3771L89 56.2017L98 0.960449L142 77.2938L139 98.3859L66 209.873L74 155.636Z" fill="#FF7800"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="main__news">
        <div class="main__news__top-arrow">
            <img class="w-100 rotate180" src="/img/welcome/vector-bottom-top.svg">
        </div>
        <div class="main__news__content">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="text-rotate-skew text-rotate-skew__h1 text-uppercase text-center text-white text-skew-effect w-100 h-100 font-italic">
                                Новости роберта
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="main__news__content__dots js-main__news__dot" data-slide="0">
                            ВК
                        </div>
                        <div class="main__news__content__dots js-main__news__dot active" data-slide="1">
                            ИНСТА
                        </div>
                        <div class="main__news__content__dots js-main__news__dot" data-slide="2">
                            ЮТУБ
                        </div>
                    </div>
                    <div class="col-12 col-lg-8 offset-lg-2">
                        <div class="js-main__news owl-carousel owl-theme">
                            <div>
                                <div class="js-main__news__container" data-social="vk" data-slide="0" data-start="" data-page="1" data-per-page="{{$perPageVk??3}}">
                                    <p class="main__reviews__text">
                                        <b>Тоня,</b> <span class="color-orange">тренеруюсь год в группе силовой тренировки</span>
                                    </p>
                                    <p class="main__reviews__text">
                                        Сайт рыбатекст поможет дизайнеру, верстальщику, вебмастеру сгенерировать несколько абзацев
                                        более менее осмысленного текста рыбы на русском языке, а начинающему оратору отточить навык
                                        публичных выступлений в домашних условиях. При создании генератора мы использовали небезизвестный
                                        универсальный код речей. Текст генерируется абзацами случайным образом от двух до десяти предложений в
                                        абзаце, что позволяет сделать текст более привлекательным и живым для визуально-слухового восприятия.
                                    </p>
                                </div>
                            </div>
                            <div>
                                <div class="js-main__news__container" data-social="instagram" data-slide="1" data-start="" data-page="1" data-per-page="{{$perPageInstagram??3}}">
                                    <p class="main__reviews__text">
                                        <b>Тоня,</b> <span class="color-orange">тренеруюсь год в группе силовой тренировки</span>
                                    </p>
                                    <p class="main__reviews__text">
                                        Сайт рыбатекст поможет дизайнеру, верстальщику, вебмастеру сгенерировать несколько абзацев
                                        более менее осмысленного текста рыбы на русском языке, а начинающему оратору отточить навык
                                        публичных выступлений в домашних условиях. При создании генератора мы использовали небезизвестный
                                        универсальный код речей. Текст генерируется абзацами случайным образом от двух до десяти предложений в
                                        абзаце, что позволяет сделать текст более привлекательным и живым для визуально-слухового восприятия.
                                    </p>
                                </div>
                            </div>
                            <div>
                                <div class="js-main__news__container" data-social="youtube" data-slide="2" data-start="" data-page="1" data-per-page="{{$perPageYoutube??3}}">
                                    <p class="main__reviews__text">
                                        <b>Тоня,</b> <span class="color-orange">тренеруюсь год в группе силовой тренировки</span>
                                    </p>
                                    <p class="main__reviews__text">
                                        Сайт рыбатекст поможет дизайнеру, верстальщику, вебмастеру сгенерировать несколько абзацев
                                        более менее осмысленного текста рыбы на русском языке, а начинающему оратору отточить навык
                                        публичных выступлений в домашних условиях. При создании генератора мы использовали небезизвестный
                                        универсальный код речей. Текст генерируется абзацами случайным образом от двух до десяти предложений в
                                        абзаце, что позволяет сделать текст более привлекательным и живым для визуально-слухового восприятия.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="main__news__bottom-arrow">
            <div class="main__news__bottom-arrow__load-more d-flex align-items-center justify-content-center">
                <div class="link-hover-underline text-rotate-skew text-rotate-skew__h1 text-uppercase text-center color-orange text-skew-effect font-italic cursor-pointer">
                    Еще
                </div>
            </div>
            <img class="w-100" src="/img/welcome/vector-bottom-top.svg">
        </div>
    </div>
@endsection
