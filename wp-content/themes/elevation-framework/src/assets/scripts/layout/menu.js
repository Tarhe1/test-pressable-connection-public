var jQ = jQuery.noConflict();

jQ(document).ready(function () {
	function myOrientResizeFunction() {
		jQ('#NavDropdown ul.navbar-nav > li.dropdown > a').removeAttr(
			'data-bs-toggle aria-haspopup aria-expanded'
		);

		if (jQ(window).width() >= 1199.99) {
			jQ('.dropdown-btn').unbind();
			jQ('.dropdown').unbind();
			jQ('.dropdown-btn').remove();

			jQ('.dropdown').hover(
				function () {
					jQ(this)
						.children('.dropdown-menu')
						.stop(true, false)
						.slideDown('fast')
						.addClass('display_dropdown');
				},
				function () {
					jQ(this)
						.children('.dropdown-menu')
						.stop(true, false)
						.slideUp('fast')
						.removeClass('display_dropdown');
				}
			);

			jQ('.dropdown').on('focusin', function () {
				jQ(this)
					.children('.dropdown-menu')
					.stop(true, false)
					.slideDown('fast')
					.addClass('display_dropdown');
			});

			jQ('.dropdown').on('focusout', function () {
				jQ(this)
					.children('.dropdown-menu')
					.stop(true, false)
					.slideUp('fast')
					.removeClass('display_dropdown');
			});

			jQ('.dropdown-btn').click(function () {
				jQ(this).next().slideToggle('fast');
			});

			var $sidenav = jQ('.header__section #NavDropdown').css(
				'width',
				'inherit'
			);
		} else if (jQ(window).width() <= 1199.98) {
			jQ('.dropdown-btn').remove();
			var itemBtn =
				'<button  class="dropdown-toggle dropdown-btn"><span class="fas fa-chevron-down"></span></button>';
			jQ('header .nav .dropdown > a').after(itemBtn);

			/*START Dropdown Main Menu Animation--------------------*/

			jQ('.dropdown-btn').unbind();
			jQ('.dropdown, .dropdown-menu').unbind();

			jQ('.dropdown-btn, .dropdown-toggle .dropdown-btn').click(
				function (e) {
					e.preventDefault();
					jQ(this).find('.fas').toggleClass('open');
					jQ(this).next().slideToggle('open');
					jQ(this).toggleClass('active-btn');
				}
			);

			/*END Dropdown Main Menu Animation--------------------*/

			// jQ('.nav .dropdown .dropdown-menu').css('display', 'none');
			jQ('.dropdown, .dropdown-menu .dropdown').unbind();
			jQ('#NavDropdown ul li.menu-item-has-children').each(function () {
				jQ('#NavDropdown ul li.menu-item-has-children ul li.active')
					.parent()
					.parent()
					.addClass('active');
			});
		}
	}
	myOrientResizeFunction();

	jQ(window).bind('resize', function (e) {
		if (window.RT) clearTimeout(window.RT);
		window.RT = setTimeout(function () {
			myOrientResizeFunction();
		}, 0);
	});

	jQ(window).resize(function () {
		myOrientResizeFunction();
		if (
			window.matchMedia('(min-width: 1199.98px) and (max-width: 1200px)')
				.matches
		) {
			location.reload();
		}
	});

	var $sidenav = jQ('.header__section #NavDropdown'),
		$toggler = jQ('.header__section .navbar-toggler');

	$toggler.click(function () {
		$toggler.toggleClass('active');
		jQ('.header__section .navbar-brand').toggleClass('active');
		jQ('.header__section .header__mobile').toggleClass('active');
		$sidenav.toggleClass('active');
		jQ('.header__buttons').toggleClass('active-menu');

		if (!$sidenav.hasClass('show')) {
			showSidenav();
		} else {
			hideSidenav();
		}
	});

	function showSidenav() {
		// $sidenav.css('display', 'block');
		window.setTimeout(function () {
			if (jQ(window).width() <= 575.98) {
				$sidenav.css({
					width: '100vw',
				});
			} else {
				$sidenav.css({
					// width: '360px',
				});
			}
		}, 400);
		$sidenav.addClass('show');
		jQ('.header__section').addClass('show');
	}

	function hideSidenav() {
		$sidenav.css({
			// width: '0px',
		});
		window.setTimeout(function () {
			// $sidenav.css('display', 'none');
		}, 400);
		$sidenav.removeClass('show');
		jQ('.header__section').removeClass('show');
	}

	jQ('#open-search, #open-search-mobile, .open-search').click(function () {
		jQ(this).toggleClass('close-search');
		jQ('#NavDropdown .wrapper-collapse').toggleClass('opened-search');
		jQ('.header__section-search').slideToggle('400');
		setTimeout(function () {
			jQ('#searchInput').focus();
		}, 500);
	});
});
