/**
 * Set the same height for all the items in the container
 * @param {string} containerSelector
 * @param {string} itemSelector
 */
const feedItemsSameHeight = (containerSelector, itemSelector) => {
	const carousels = document.querySelectorAll(containerSelector);
	if (carousels) {
		carousels.forEach((carousel) => {
			const items = carousel.querySelectorAll(itemSelector);
			items.forEach((item) => {
				item.style.minHeight = '0';
			});
			// reset the height
			// set the height
			let maxHeight = 0;
			items.forEach((item) => {
				const itemHeight = item.offsetHeight;
				if (maxHeight < itemHeight) {
					maxHeight = itemHeight;
				}
			});

			items.forEach((item) => {
				item.style.minHeight = maxHeight + 'px';
			});
		});
	}
};

/**
 * Set the same height for the items in the container
 * @param {string} containerSelector
 * @param {string} itemSelector
 * @param {string} [classForChecking]
 */

const sameHeight = (
	containerSelector,
	itemSelector,
	classForChecking = 'height-set'
) => {
	const setFeedItemsSameHeight = setInterval(() => {
		const feedCard = document.querySelector(containerSelector);
		if (
			feedCard !== null &&
			!feedCard.classList.contains(classForChecking)
		) {
			feedItemsSameHeight(containerSelector, itemSelector);
			feedCard.classList.add(classForChecking);
		} else {
			clearInterval(setFeedItemsSameHeight);
		}
	}, 200);

	let windowWidth = window.innerWidth;
	// Trigers when resize window
	const reportWindowSize = () => {
		if (windowWidth !== window.innerWidth) {
			feedItemsSameHeight();
			windowWidth = window.innerWidth;
		}
	};

	window.addEventListener('resize', reportWindowSize);
};

export default sameHeight;
