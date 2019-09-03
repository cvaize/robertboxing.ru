<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name='description' content='Роберт Рустамян – тренер по боксу с нестандартным подоходом к тренировкам, на которые захочется приходить вновь и вновь. На занятиях всегда интересные и разнообразные задания, которые никогда не заставят вас скучать!'>

	<title>{{ config('app.name', 'Laravel') }}</title>

	<link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
	<link rel="manifest" href="/favicon/site.webmanifest">
	<link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#ff7800">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="theme-color" content="#ffffff">

	<!-- Fonts --><!-- Fonts -->
	<link rel="dns-prefetch" href="https://fonts.googleapis.com">
	<link rel="dns-prefetch" href="https://use.fontawesome.com">

	{{--<link href="https://fonts.googleapis.com/css?family=Philosopher" rel="stylesheet">--}}
	{{--<link href="https://fonts.googleapis.com/css?family=Oswald:300,400,600&amp;subset=cyrillic" rel="stylesheet">--}}
	{{--<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,700&amp;subset=cyrillic" rel="stylesheet">--}}
	{{--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">--}}

	<!-- Styles -->
	<link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>

@include('layouts.modules._navbar')
{{--@include('layouts.modules._flashMessages')--}}

<main class="main">
	<h1 style='display: none'>{{ config('app.name', 'Laravel') }}</h1>
	@yield('content')
</main>
@include('layouts.modules._footer')
@include('layouts.modules._preloader')
<!-- Scripts -->
<script async defer src='https://www.google.com/recaptcha/api.js'></script>
<!-- Yandex.Metrika counter -->
<script async defer type="text/javascript" >
    (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

    ym(51851810, "init", {
        id:51851810,
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
    });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/51851810" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>--}}
<script async defer src="{{ mix('js/app.js') }}"></script>
</body>
</html>
