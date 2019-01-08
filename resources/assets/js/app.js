


window.$ = require('jquery');
window.jQuery = window.$;
require('bootstrap');
require('owl.carousel');
require('clip-path');
require('../libs/jquery-custom-scrollbar-0.5.5/jquery.custom-scrollbar.min');
// require('jquery-touchswipe/jquery.touchSwipe.min');
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
			scrollBar.mCustomScrollbar({
				axis:"x",
				autoDraggerLength:false,
				documentTouchScroll:true,
				// theme:"dark"
			});
			// scrollBar.customScrollbar();
			/**
			let directionData = 'left';
			let distanceData = 0;
			function calculatePosition(distance){
				return new Promise(function (resolve, reject) {
					
					let widthScrollBar = $('.js-scroll-bar').width();
					let widthScrollBarSwipe = $('#mCSB_1_container').width();
					let widthScrollBarSwipeLeft = $('#mCSB_1_container').css('left');
					widthScrollBarSwipeLeft = widthScrollBarSwipeLeft.replace('-', '').replace('px', '');
					widthScrollBarSwipeLeft = Number(widthScrollBarSwipeLeft);
					console.log('distance',distance);
					let position = widthScrollBarSwipeLeft + distance;
					// position *= -1;

					console.log('widthScrollBarSwipeLeft + distance',position);
					if(position <= 0){
						if(widthScrollBarSwipeLeft <= 0){
							resolve(-1);
						}
						position = 0;
					}else{
						let r = widthScrollBarSwipe - widthScrollBar;
						if(position >= r){
							if(r <= widthScrollBarSwipeLeft){
								resolve(-1);
							}
							position = r;
						}
					}


					console.log(widthScrollBar, widthScrollBarSwipe, widthScrollBarSwipeLeft);
					
					resolve(position);
				});
			}
			let distanceDataX = 0;
			let distanceDataY = 0;
			$(".js-scroll-bar__swipe").swipe({
				swipeStatus:function(event, phase, direction, distance, fingerCount, fingerData) {
					directionData = direction;
					let rPosY = 0;
					let rPosX = 0;
					if(phase === 'start'){
						if(directionData === 'up' || directionData === 'down'){
							distanceDataX = 0;
						}
						if(directionData === 'left' || directionData === 'right'){
							distanceDataY = 0;
						}
					}else{
						if(directionData === 'up'){
							rPosY = distanceDataX + distance;
						}
						if(directionData === 'down'){
							rPosY = distanceDataX - distance;
						}
						if(directionData === 'right'){
							rPosX = distanceDataY - distance;
						}
						if(directionData === 'left'){
							rPosX = distanceDataY + distance;
						}

						if(directionData === 'up' || directionData === 'down'){
							distanceDataX = distance;
						}
						if(directionData === 'left' || directionData === 'right'){
							distanceDataY = distance;
						}
					}
					console.log('rPosY', rPosY);
					console.log('rPosX', ''+rPosX);
					if(rPosY !== 0){
						let scrollTop = $(window).scrollTop();
						scrollTop += rPosY;
						// $(window).scrollTop(scrollTop);
						let body = $("html, body");
						body.stop().animate({scrollTop:scrollTop}, 300, 'linear', function() {
							console.log("Finished animating");
						});
					}
					if(rPosX !== 0){
						// scrollBar.mCustomScrollbar("scrollTo",((rPosX > 0)?'+'+rPosX:''+rPosX));
						// calculatePosition(rPosX).then(function (position) {
						// 	if(position !== -1){
						// 		// console.log('position',position);
						// 		scrollBar.mCustomScrollbar("scrollTo",position);
						// 	}
						// });
					}
					if(phase === 'end' || phase === 'close'){
						distanceDataX = 0;
						distanceDataY = 0;
					}
					// console.log('phase',phase);
					// console.log('direction',direction);
					// console.log('distance',distance);
				},
				// swipeStatus:function(event, phase, direction, distance, fingerCount, fingerData) {
				// 	directionData = direction;
				// 	if(phase === 'start'){
				// 		if(directionData === 'left'){
				// 			distanceData = distance;
				// 		}
				// 		if(directionData === 'right'){
				// 			distanceData = -1 * distance;
				// 		}
				// 	}else{
				// 		directionData = direction;
				// 		if(directionData === 'left'){
				// 			distanceData += distance;
				// 		}
				// 		if(directionData === 'right')
				// 			distanceData += -1 * distance;{
				// 		}
				// 	}
				// 	if((directionData === 'left' || directionData === 'right')){
				// 		console.log(distanceData);
				// 		calculatePosition(distanceData).then(function (position) {
				// 			if(position !== -1){
				// 				// console.log('position',position);
				// 				scrollBar.mCustomScrollbar("scrollTo",position);
				// 			}
				// 		});
				// 	}
				// 	if(phase === 'end' || phase === 'close'){
				// 		distanceData = 0;
				// 	}
				// 	// console.log('phase',phase);
				// }
			});
			// $('.js-scroll-bar').mCustomScrollbar("scrollTo",'+100');
			 **/
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

