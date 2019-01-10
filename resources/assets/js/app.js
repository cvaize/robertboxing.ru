window.$ = require('jquery');
window.jQuery = window.$;
require('bootstrap');
require('owl.carousel');
require('clip-path');
// require('imagesloaded/imagesloaded.pkgd.min.js');
//https://gromo.github.io/jquery.scrollbar/
require('../libs/jquery.scrollbar-gh-pages/jquery.scrollbar.min');
require('../libs/photobox-master/photobox/jquery.photobox');
// require('../libs/AutomaticImageMontage/js/jquery.montage.min');
// require('malihu-custom-scrollbar-plugin');

String.prototype.isEmpty = function()
{
	let x = this;
	return (
		(typeof x == 'undefined')
		||
		(x == null)
		||
		(x == false)  //same as: !x
		||
		(x.length == 0)
		||
		(x == "")
		||
		(x.replace(/\s/g,"") == "")
		||
		(!/[^\s]/.test(x))
		||
		(/^\s*$/.test(x))
	);
};

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


	let scrollBar = $('.js-scroll-bar');
	if (scrollBar.length) {
		scrollBar.scrollbar({
			"showArrows": false,
			"scrollx": "advanced",
			"scrolly": "advanced"
		});
	}
	if ($(".js-main__reviews").length) {
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
	if ($(".js-main__news").length) {
		let POSITION = $('.js-main__news__dot[data-select="1"]').attr('data-social');
		let DATA = {
			// vk:{
			// 	page: 1,
			// 	final: false,
			// 	content: ''
			// }
		};
		setStartContent();

		function setStartContent() {


			$('.js-main__news__dot').each(function () {
				let item = $(this);
				let startData = item.attr('data-start');
				let social = item.attr('data-social');
				let final = item.attr('data-final');
				let page = item.attr('data-page');
				let perPage = item.attr('data-per-page');

				if (!DATA[social]) {
					DATA[social] = {
						page: ((page) ? Number(page) : 1),
						perPage: ((perPage) ? Number(perPage) : 3),
						final: (final && Number(final) === 1),
						loading: false,
						content: ''
					};
				}


				startData = JSON.parse(startData);
				DATA[social].content = generateContent(startData, social);
			});
			addContent(DATA[POSITION].content, true);

			$(document).on('click', '.js-main__news__dot', function () {
				let position_ = $(this).attr('data-social');

				if (POSITION !== position_) {
					POSITION = position_;

					addContent(DATA[POSITION].content, true);

					$('.js-main__news__dot').removeClass('active');
					$(this).addClass('active');
				}
			});
			$(document).on('click', '.js-main__news__load-more', function () {

				if (!DATA[POSITION].loading && !DATA[POSITION].final) {
					let social = POSITION;
					DATA[social].page = DATA[POSITION].page + 1;
					let page = DATA[POSITION].page;
					let perPage = DATA[POSITION].perPage;
					DATA[social].loading = true;
					$.ajax('/posts', {data: {social: social, page: page, perPage: perPage}}).then(function (data) {

						if (data && Array.isArray(data)) {
							if (data.length < perPage) {
								DATA[social].final = true;
							}
							if (data.length) {
								let content = generateContent(data, social);
								DATA[social].content += content;
								addContent(content, false);
							}
						}
						console.log(data);
						DATA[social].loading = false;
						checkStatusLoadMore();
					}).catch(function () {
						DATA[social].loading = false;
					});
				}

			});
		}

		function addContent(content, clear) {

			let random = Math.round(Math.random() * 10000000000);
			let classCollapse = 'js-main__news__collapse--' + random;
			let item = $('.js-main__news');

			if (clear) {
				let collapse = item.find('.collapse');
				if (collapse.length) {
					collapse.on('hidden.bs.collapse', function () {
						item.html('');
						append();
					});
					collapse.collapse('hide');
				} else {
					item.html('');
					append();
				}
			} else {
				append();
			}

			function append() {

				item.append('<div class="collapse ' + classCollapse + '">\n' +
					content +
					'</div>');

				reInitLoadedImages(classCollapse);

				setTimeout(function (classCollapse) {
					$('.' + classCollapse).collapse('show');
				}, 300, classCollapse);

				checkStatusLoadMore();
			}

			function reInitLoadedImages(classCollapse) {
				$('.js-photobox').photobox('a', {time: 0});
				// $('.'+classCollapse).imagesLoaded( function() {
				// 	$('.'+classCollapse).montage({
				// 		fillLastRow				: true,
				// 		alternateHeight			: true,
				// 		alternateHeightRange	: {
				// 			min	: 90,
				// 			max	: 240
				// 		}
				// 	});
				// });
			}

		}

		function checkStatusLoadMore() {
			if (DATA[POSITION] && DATA[POSITION].final) {
				$('.js-main__news__load-more').addClass('opacity-0');
			} else {
				$('.js-main__news__load-more').removeClass('opacity-0');
			}
		}


		function generateContent(data, social) {
			let content = '';

			if (data && Array.isArray(data)) {
				for (let key in data) {
					content += generateContentItem(data[key], social);
				}
			}

			function generateContentItem(item, social) {

				let html = '';
				let caption = '';
				let media = '';
				let url = '';


				caption += ((item['caption']) ? item['caption'] : '');

				if (item['media'] && item['media'].length) {
					media += '<div class="js-photobox main__news__wrap-instagram' +
						(caption.isEmpty()?'-flex':'')+
						' mb-2 mr-md-3">';
					for (let i in item['media']) {
						let val = item['media'][i];
						if (val.isVideo) {
							media += '<a class="wrap-media' +
								((item['media'].length > 1)?' wrap-media--gallery':' wrap-media--video') +
								((Number(i) !== 0)?' d-none':'') +
								'" href="' +
								((val.url) ? val.url : '') +
								'" rel="video"><img class="main__news__wrap-instagram__img" src="' +
								((val.first_frame) ? val.first_frame : '') +
								'" title="' +
								((social === 'youtube' && item['title']) ? item['title'] : '') +
								'"></a>';
						}
						if (val.isImage) {
							media += '<a class="wrap-media' +
								((item['media'].length > 1)?' wrap-media--gallery':' wrap-media--photo') +
								((Number(i) !== 0)?' d-none':'') +
								'" href="' +
								((val.url) ? val.url : '') +
								'"><img class="main__news__wrap-instagram__img" src="' +
								((val.url) ? val.url : '') +
								'" title="' +
								((social === 'youtube' && item['title']) ? item['title'] : '') +
								'"></a>';
						}
					}
					media += '</div>';
				}
				url += ((item['url']) ? '<div>' +
					'<div class="main__news__link-post d-flex align-items-center justify-content-end">\n' +
					'                            <a target="_blank" href="' +
					item['url'] +
					'" class="mx-1 d-block text-rotate-skew text-rotate-skew__h4 text-uppercase text-center text-white text-skew-effect font-italic">\n' +
					'                                Ссылка на пост\n' +
					'                            </a>\n' +
					'                        </div></div>' : '');
				html += `
							<div class="main__reviews__text clearfix"> 
								${media}
								
								${caption}
								${url}
							</div>
						`;

				html = '<div class="mb-4 mb-lg-5 pt-4">' +
					html +
					'</div>';

				return html;
			}

			return content;
		}

	}

})($);

