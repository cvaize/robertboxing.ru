let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css').version();

mix.browserSync({
	proxy:  // проксирование вашего удаленного сервера, не важно на чем back-end
		{
			target: "http://robertboxing.loc",
			ws: true
		},
	logPrefix: 'robertboxing.loc', // префикс для лога bs, маловажная настройка
	host: 'robertboxing.loc', // можно использовать ip сервера
	port: 3000, // порт через который будет проксироваться сервер
	// open: 'external', // указываем, что наш url внешний
	notify: true,
	ghost: true,
	// httpModule: 'http2',
	// https: {
	//     key: "./ssl/privkey.pem",
	//     cert: "./ssl/fullchain.pem",
	// },
});
