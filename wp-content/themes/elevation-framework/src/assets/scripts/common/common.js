var topMenu = document.querySelector('.header__top-menu');
const hideTopMenuFromAccesibility = () => {
	if (topMenu) {
		topMenu.style.visibility = 'hidden';
	}
};
const showTopMenuInAccesibility = () => {
	if (topMenu) {
		topMenu.style.visibility = 'visible';
	}
};

var body = document.body;

const addClassToBody = () => {
	if (window.scrollY) {
		body.classList.add('fixed');
		hideTopMenuFromAccesibility();
	} else {
		body.classList.remove('fixed');
		showTopMenuInAccesibility();
	}
};
addClassToBody();

const addClassToBodyOnScroll = () => {
	document.addEventListener(
		'scroll',
		() => {
			addClassToBody();
		},
		{ passive: true }
	);
};
addClassToBodyOnScroll();
