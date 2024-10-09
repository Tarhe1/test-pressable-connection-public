import Swiper from 'swiper/bundle';

/**
 * Initialize the Swiper carousel
 * @param {string} swiperSelector
 */

const getSwiperParams = (dataset) => {
	// const loop = dataset.loop && dataset.loop !== '0' ? true : false;
	const centeredSlides =
		dataset.centeredSlides && dataset.centeredSlides !== '0' ? true : false;
	const grabCursor =
		dataset.grabCursor && dataset.grabCursor !== '0' ? true : false;
	const spaceBetween = dataset.spaceBetween || 30;
	const slidesPerView = dataset.slidesPerView || 1;
	const spaceBetweenSm = dataset.spaceBetweenSm || spaceBetween;
	const slidesPerViewSm = dataset.slidesPerViewSm || slidesPerView;
	const spaceBetweenMd = dataset.spaceBetweenMd || spaceBetweenSm;
	const slidesPerViewMd = dataset.slidesPerViewMd || slidesPerViewSm;
	const spaceBetweenLg = dataset.spaceBetweenLg || spaceBetweenMd;
	const slidesPerViewLg = dataset.slidesPerViewLg || slidesPerViewMd;
	const spaceBetweenXl = dataset.spaceBetweenXl || spaceBetweenLg;
	const slidesPerViewXl = dataset.slidesPerViewXl || slidesPerViewLg;
	const spaceBetweenXxl = dataset.spaceBetweenXxl || spaceBetweenXl;
	const slidesPerViewXxl = dataset.slidesPerViewXxl || slidesPerViewXl;

	const perviewSlider = dataset.perview;

	const slideToClickedSlide =
		dataset.slideToClickedSlide && dataset.slideToClickedSlide !== '0'
			? true
			: false;
	const delay = dataset.delay || 7000;
	const disableOnInteraction = dataset['disable-on-interaction']
		? true
		: false;

	const loopAdditionalSlides =
		dataset['loop-additional-slides'] !== 0
			? dataset['loop-additional-slides']
			: undefined;

	return {
		loop: true,
		centeredSlides,
		grabCursor,
		spaceBetween,
		slidesPerView,
		slideToClickedSlide,
		delay,
		disableOnInteraction,
		loopAdditionalSlides,
		breakpoints: {
			576: {
				spaceBetween: spaceBetweenSm,
				slidesPerView: slidesPerViewSm,
			},
			768: {
				spaceBetween: spaceBetweenMd,
				slidesPerView: slidesPerViewMd,
			},
			992: {
				spaceBetween: spaceBetweenLg,
				slidesPerView: slidesPerViewLg,
			},
			1200: {
				spaceBetween: spaceBetweenXl,
				slidesPerView: perviewSlider ?? slidesPerViewXl,
			},
			1400: {
				spaceBetween: spaceBetweenXxl,
				slidesPerView: perviewSlider ?? slidesPerViewXxl,
			},
		},
	};
};

export default function initSwiper(swiperSelector) {
	// Do not run if swiperSelector is not present
	if (!swiperSelector) return;

	const carousels = document.querySelectorAll(swiperSelector);
	if (carousels) {
		carousels.forEach((carousel) => {
			// Get the id of the carousel
			const id = carousel.id;

			// If the id is present, initialize the carousel
			if (id) {
				const nextButton = carousel.querySelector(
					'.swiper-button-next'
				);
				const prevButton = carousel.querySelector(
					'.swiper-button-prev'
				);

				const scrollBar = carousel.querySelector('.swiper-scrollbar');

				const paginator = carousel.querySelector('.swiper-pagination');

				const {
					centeredSlides,
					grabCursor,
					spaceBetween,
					slidesPerView,
					slideToClickedSlide,
					breakpoints,
					delay,
					disableOnInteraction,
					loopAdditionalSlides,
				} = getSwiperParams(carousel.dataset);

				const swiperCarousel = new Swiper(`#${id}`, {
					// Optional parameters
					loop: true,
					centeredSlides,
					grabCursor,
					spaceBetween,
					slidesPerView,
					slideToClickedSlide,
					loopAdditionalSlides,
					breakpoints: {
						576: breakpoints[576],
						768: breakpoints[768],
						992: breakpoints[992],
						1200: breakpoints[1200],
						1400: breakpoints[1400],
					},
					// Rotation
					autoplay: {
						delay,
						disableOnInteraction,
					},

					// Navigation arrows
					navigation: {
						nextEl: nextButton,
						prevEl: prevButton,
					},

					// And if we need scrollbar
					scrollbar: {
						el: scrollBar,
					},

					pagination: {
						el: paginator,
						clickable: true,
						dynamicBullets: true,
						dynamicMainBullets: window.innerWidth <= 768 ? 4 : 9,
					},
				});

				swiperCarousel.autoplay.stop();

				const playPauseButton = carousel.querySelector(
					'.swiper-button-play-pause'
				);

				if (playPauseButton) {
					swiperCarousel.on('resize', function () {
						if (swiperCarousel.snapGrid.length > 1) {
							playPauseButton.style.display = 'block';
						} else {
							playPauseButton.style.display = 'none';
						}
					});
					playPauseButton.addEventListener('click', function (e) {
						const arialLabel =
							playPauseButton.getAttribute('aria-label');
						if (arialLabel == 'play') {
							swiperCarousel.autoplay.start();
							playPauseButton.setAttribute('aria-label', 'pause');
						} else {
							swiperCarousel.autoplay.pause();
							playPauseButton.setAttribute('aria-label', 'play');
						}
					});
				}
			}
		});
	}
}
