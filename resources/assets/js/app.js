window.$ = require('jquery');
window.jQuery = window.$;
require('bootstrap');
require('owl.carousel');
require('clip-path');
require('jquery-mask-plugin');
// require('imagesloaded/imagesloaded.pkgd.min.js');
//https://gromo.github.io/jquery.scrollbar/
require('../libs/jquery.scrollbar-gh-pages/jquery.scrollbar.min');
// require('../libs/photobox-master/photobox/jquery.photobox');
require('../libs/fancybox-master/dist/jquery.fancybox.min');
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

//
// $(window).on('load', function () {
//     console.log('all loaded');
// });

(function(w, d){
    var b = d.getElementsByTagName('body')[0];
    var s = d.createElement("script");
    var v = !("IntersectionObserver" in w) ? "8.17.0" : "10.19.1";
    s.async = true; // This includes the script as async. See the "recipes" section for more information about async loading of LazyLoad.
    s.src = "https://cdn.jsdelivr.net/npm/vanilla-lazyload@" + v + "/dist/lazyload.min.js";
    w.lazyLoadOptions = {
            elements_selector: ".lazy",
        	to_webp: true
    };
    b.appendChild(s);
}(window, document));

/**
 * Блок кода отвечает за слайдер и его скролл
 */
(function ($) {
	// let pathName = location.pathname;
	// if (pathName.search(/admin/i) === -1) {
    //     window.paceOptions = {
    //         ajax: true,
    //         document: true,
    //         eventLag: false
    //     };
	//
    //     Pace.on('done', function() {
    //         $('#preloader').delay(500).fadeOut(800);
    //     });
	// }

	$('.js-phone-mask').mask('8(000)000-00-00');

    $(document).on('click', '#training-link', function(e) {
        $('html, body').animate({
            scrollTop: $("#box-request").offset().top
        }, 777);
        e.preventDefault();
        return false;
    });

    /*$('.btn-warning--orange').ClipPath('3.2% 0, 100% 0, 96.8% 100%, 0% 100%');
    $('.btn-outline-warning--orange').ClipPath('3.8% 2%, 99.2% 2%, 96.2% 98%, 0.8% 98%');
    $('.btn-outline-warning--orange__filter').ClipPath('3.2% 0, 100% 0, 96.8% 100%, 0% 100%');
    $('.scroll-bar__item').ClipPath('13% 0, 100% 0, 87% 100%, 0% 100%');
    $('.scroll-element .scroll-bar .scroll-bar_body').ClipPath('4px 0, 100% 0, calc(100% - 4px) 100%, 0% 100%');
    $('.main__bg-tree__img').ClipPath('0 12%, 33.3333% 0, 33.3333% 12%, 66.6666% 0, 66.6666% 12%, 100% 0, 100% 88%, 66.6666% 100%, 66.6666% 88%, 33.3333% 100%, 33.3333% 88%, 0% 100%');
    if (window.innerWidth <= 480) {
        $('.main__bg-tree__img').ClipPath('0 24%, 100% 0, 100% 88%, 0 100%');
    }
    if (window.innerWidth <= 575.98) {
        $('.scroll-bar__item').ClipPath('none');
        $('.scroll-element .scroll-bar .scroll-bar_body').ClipPath('none');
	}
    // Small devices (landscape phones, 576px and up)
    if (window.innerWidth > 1200) {
        $('.main__box-bg-action').ClipPath('8% 0, 100% 0, 92% 100%, 0% 100%');
        $('.main__box-feedback').ClipPath('6.2% 0, 100% 0, 93.8% 100%, 0% 100%');
        $('.main__box-feedback .textarea-background').ClipPath('3% 0, 100% 0, 97% 100%, 0% 100%');
        $('.main__scroll-bar-container').ClipPath('6.1% 0, 100% 0, 100% 100%, 0% 100%');
    }*/


    let timeTraining = $('#next-training__time').data('time');
    if (undefined !== timeTraining && 0 !== timeTraining.length) {
        let hours = timeTraining.match( /hours-(\d{0,2})/i ),
            minutes = timeTraining.match( /minutes-(\d{0,2})/i ),
            seconds = timeTraining.match( /seconds-(\d{0,2})/i );

        if (hours.length > 1)
        	hours = hours[1];
        else
        	hours = 0;
        if (minutes.length > 1)
            minutes = minutes[1];
        else
            minutes = 0;
        if (seconds.length > 1)
            seconds = seconds[1];
        else
            seconds = 0;

        if (hours !== 0 || minutes !== 0 || seconds !== 0) {
            var x = setInterval(function() {
                hours = (hours < 10 && hours.toString().length < 2) ? '0' + hours : hours;
                minutes = (minutes < 10 && minutes.toString().length < 2) ? '0' + minutes : minutes;
                seconds = (seconds < 10 && seconds.toString().length < 2) ? '0' + seconds : seconds;
                // Display the result in the element with id="demo"
                let time = hours + ":" + minutes + "<span id='time__dots'>:</span>" + seconds;
                document.getElementById("next-training__time").innerHTML = time;
                $('#time__dots').fadeOut(200).fadeIn(200);

                if (parseInt(seconds) - 1 >= 0)
                    seconds--;
                else {
                    if (parseInt(hours) !== 0 || parseInt(minutes) !== 0)
                        seconds = 59;
                    else
                        seconds = 0;

                    if (parseInt(minutes) - 1 >= 0)
                        minutes--;
                    else {
                        if (parseInt(hours) !== 0)
                            minutes = 59;
                        else
                            minutes = 0;

                        if (parseInt(hours) - 1 >= 0)
                            hours--;
                    }
                }

                if (parseInt(hours) === 0 && parseInt(minutes) === 0 && parseInt(seconds) === 0) {
                    clearInterval(x);
                    document.getElementById("next-training__time").innerHTML = "00:00:00";
                }
            }, 1000);
		}
	}

	window.handleErrorImage = function (vm) {
		let elem = $(vm);
		let newItem = elem.attr('data-new-item');
		if(newItem){
			$('div[data-new-item='+newItem+']').each(function () {
				$(this).css({
					backgroundImage: 'url(/images/no-image.webp)'
				});
			});
			$('img[data-new-item='+newItem+']').each(function () {
				$(this).attr('src', '/images/no-image.webp')
			});
		}
	};

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
	$('.js-form-requests__input').on('input', function () {
		let elem = $(this);
		let open = true;
		let val = elem.val();
		if(val.isEmpty()){
			elem.addClass('is-invalid');
		}else{
			elem.removeClass('is-invalid');
		}
		$('.js-form-requests__input').each(function () {
			let elem = $(this);
			let val = elem.val();
			if(val.isEmpty()){
				open = false;
			}
		});
		if(open){
			$('.js-form-requests__collapse').collapse('show');
		}else{
			$('.js-form-requests__collapse').collapse('hide');
		}
	});
	$('.js-form-requests').on('submit', function (e) {
		e.preventDefault();
		let formData = $(this).serialize();
		let method = $(this).attr('method');
		let action = $(this).attr('action');
		// console.log('submit', formData);
		$.ajax({
			type: method,
			url: action,
			data: formData,
			async: true,
			success: function (data) {
				// console.log(data);
				if(data.type === 'success'){
					let elem = $('.js-form-requests__collapse--form');
					elem.on('hidden.bs.collapse', function () {
						$('.js-form-requests__collapse--message-success').collapse('show');
					});
					elem.collapse('hide');
				} else if(data.type === 'error') {
                    let elem = $('.js-form-requests__collapse--form');
                    elem.on('hidden.bs.collapse', function () {
                        $('.js-form-requests__collapse--message-error').collapse('show');
                    });
                    elem.collapse('hide');
				}
			},
			error: function (data) {
				if(data.responseJSON){
					if(data.responseJSON.errors){
						$('[data-name]').each(function () {
							let elem = $(this);
							let name = elem.attr('data-name');
							if(data.responseJSON.errors[name]){
								let sibling = elem.siblings(".invalid-feedback");
								if(sibling && data.responseJSON.errors[name][0]){
									sibling.text(data.responseJSON.errors[name][0]);
								}
								elem.addClass('is-invalid');
							}else{
								elem.removeClass('is-invalid');
							}
						});
					}
				}
				// console.log(data.responseJSON);
			}
		});


		return false;
	});

	let hideFeedbackForm = true;
	$(document).on('click', '#js-form-feedback__button', function (e) {
		if (hideFeedbackForm) {
            $('.js-form-feedback__collapse').collapse('show');
            hideFeedbackForm = false;
		} else {
            $('.js-form-feedback__collapse').collapse('hide');
            hideFeedbackForm = true;
		}
    });

    $('.js-form-feedback__input').on('input', function () {
        let elem = $(this);
        let open = true;
        let val = elem.val();
        if(val.isEmpty()){
            elem.addClass('is-invalid');
        }else{
            elem.removeClass('is-invalid');
        }
        $('.js-form-feedback__input').each(function () {
            let elem = $(this);
            let val = elem.val();
            if(val.isEmpty()){
                open = false;
            }
        });
        if(open){
            $('.js-form-feedback__collapse__captcha').collapse('show');
        }else{
            $('.js-form-feedback__collapse__captcha').collapse('hide');
        }
    });
    $('.js-form-feedback').on('submit', function (e) {
        e.preventDefault();
        let formData = $(this).serialize();
        let method = $(this).attr('method');
        let action = $(this).attr('action');
        // console.log('submit', formData);
        $.ajax({
            type: method,
            url: action,
            data: formData,
            async: true,
            success: function (data) {
                // console.log(data);
                if(data.type === 'success'){
                    let elem = $('.js-form-feedback__collapse--form');
                    elem.on('hidden.bs.collapse', function () {
                        $('.js-form-feedback__collapse--message-success').collapse('show');
                    });
                    elem.collapse('hide');
                }
            },
            error: function (data) {
                if(data.responseJSON){
                    if(data.responseJSON.errors){
                        $('[data-name]').each(function () {
                            let elem = $(this);
                            let name = elem.attr('data-name');
                            if(data.responseJSON.errors[name]){
                                let sibling = elem.siblings(".invalid-feedback");
                                if(sibling && data.responseJSON.errors[name][0]){
                                    sibling.text(data.responseJSON.errors[name][0]);
                                }
                                elem.addClass('is-invalid');
                            }else{
                                elem.removeClass('is-invalid');
                            }
                        });
                    }
                }
                // console.log(data.responseJSON);
            }
        });


        return false;
    });

	$("a.js-link-anchor").on("click", function(e){
		let anchor = $(this);
		$('html, body').stop().animate({
			scrollTop: $(anchor.attr('href')).offset().top
		}, 777);
		e.preventDefault();
		return false;
	});

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
			},
            autoHeight:true
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
						// console.log(data);
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
				// $('.js-photobox').photobox('a', {time: 0, zoomable: false});

                $('[data-fancybox="images"], [data-fancybox]').fancybox({

                });
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
					media += '<div class="js-fancybox main__news__wrap-instagram' +
						(caption.isEmpty()?'-flex':'')+
						' mb-3">';
					for (let i in item['media']) {
						let random = (i+1)*Math.round(Math.random() * 10000000000);
						let dataFancybox = (social === 'vk') ? 'data-fancybox data-type="iframe"' : 'data-fancybox';

						let val = item['media'][i];
						if (val.isVideo) {
							media += '<a class="wrap-media' +
								((item['media'].length > 1)?' wrap-media--gallery':' wrap-media--video') +
								((Number(i) !== 0)?' d-none':'') +
								'"' +
								dataFancybox +
								' href="' +
								((val.url) ? val.url : '') +
                                // '" rel="video">' +
                                '">' +
								'<div class="embed-responsive embed-responsive-16by9">\n' +
								'<div class="embed-responsive-item">' +
								'<div class="images-cover bg-dark w-100 h-100"' +
								' data-new-item="' + random +
								'" onerror="handleErrorImage(this);" style="background-image: url(' +
								((val.first_frame) ? val.first_frame : '') +
								');"></div>' +
								'</div>\n' +
								'</div>' +
								'<img class="main__news__wrap-instagram__img lazy" ' +
								' data-new-item="' + random +
								'" onerror="handleErrorImage(this);" data-src="' +
								((val.first_frame) ? val.first_frame : '') +
								'" title="' +
								((social === 'youtube' && item['title']) ? item['title'] : '') +
								'"></a>';
						}
						if (val.isImage) {
							media += '<a class="wrap-media' +
								((item['media'].length > 1)?' wrap-media--gallery':' wrap-media--photo') +
								((Number(i) !== 0)?' d-none':'') +
								'"  data-fancybox="images" href="' +
								((val.url) ? val.url : '') +
								'" rel="nofollow">' +
								'<div class="embed-responsive embed-responsive-16by9">\n' +
								'<div class="embed-responsive-item">' +
								'<div class="images-cover bg-dark w-100 h-100" ' +
								' data-new-item="' + random + '"' +
								' onerror="handleErrorImage(this);" style="background-image: url(' +
								((val.url) ? val.url : '') +
								');"></div>' +
								'</div>\n' +
								'</div>' +
								'<img class="main__news__wrap-instagram__img lazy" ' +
								' data-new-item="' + random + '"' +
								' onerror="handleErrorImage(this);" data-src="' +
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
					'                            <a target="_blank" rel="nofollow" href="' +
					item['url'] +
					'" class="mx-1 d-block text-rotate-skew text-rotate-skew__h4 text-uppercase text-center text-white text-skew-effect font-italic">\n' +
					'                                Ссылка на пост\n' +
					'                            </a>\n' +
					'                        </div></div>' : '');
				html += `
							<div class="main__reviews__text"> 
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

