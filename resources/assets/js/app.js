


window.$ = require('jquery');
window.jQuery = window.$;
require('bootstrap');
require('owl.carousel');
require('clip-path');
//https://gromo.github.io/jquery.scrollbar/
require('../libs/jquery.scrollbar-gh-pages/jquery.scrollbar.min');
require('malihu-custom-scrollbar-plugin');



/**
 * Блок кода отвечает за слайдер и его скролл
 */
(function ($) {
	/**
	 * Отказался от sticky-top
	$(window).scroll(function() {
		if ($(window).scrollTop() > 28) {
			$('.js-navbar').addClass('navbar__shadow');
		} else {
			$('.js-navbar').removeClass('navbar__shadow');
		}
	});

	 */
	$(window).on("load",function(){
		let scrollBar = $('.js-scroll-bar');
		if(scrollBar.length){
			scrollBar.scrollbar({
				"showArrows": false,
				"scrollx": "advanced",
				"scrolly": "advanced"
			});
		}
		if($(".js-main__reviews").length){
			let mainReviews = $(".js-main__reviews").owlCarousel({
				items: 1,
				dots: true,
				responsive: {
					768: {

						dots: false,
					}
				}
			});
			$(document).on('click', '.js-main__reviews__arrow--prev', function () {
				mainReviews.trigger('prev.owl.carousel', 600);
			});
			$(document).on('click', '.js-main__reviews__arrow--next', function () {
				mainReviews.trigger('next.owl.carousel', 600);
			});
		}
		if($(".js-main__news").length){
			let mainNews = $(".js-main__news").owlCarousel({
				items: 1,
				startPosition: 1,
				dots: false,
			});
			$(document).on('click', '.js-main__news__dot', function () {
				let slide = $(this).attr('data-slide');
				if(slide){
					$('.js-main__news__dot').removeClass('active');
					$(this).addClass('active');
					mainNews.trigger('to.owl.carousel', Number(slide), 600);
				}
			});
		}
	});

})($);

