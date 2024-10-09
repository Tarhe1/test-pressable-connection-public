import './style.scss';
import './editor.scss';

const accordionButtons = document.querySelectorAll('.accordion__button');

accordionButtons.forEach(function (button) {
	button.addEventListener('click', function () {
		const isOpen = button.getAttribute('aria-expanded') === 'true';
		if (isOpen) {
			// Check if the accordion exists

			setTimeout(function () {
				const bodyRect = document.body.getBoundingClientRect();
				const elemRect = button.getBoundingClientRect();
				const offsetTop = elemRect.top - bodyRect.top;
				const headerHeight =
					document.querySelector('header.header').offsetHeight;

				// Scroll to the top of the accordion less headerHeight + 6px <-- This is the space between accordion items
				window.scrollTo({
					top: offsetTop - (headerHeight + 6),
					behavior: 'smooth',
				});
			}, 300); // Wait for the accordion default open transition to finish
		}
	});
});
