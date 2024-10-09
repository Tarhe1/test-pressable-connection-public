/**
 * Add line-clamp-3 class to excerpt if title has more than 4 words
 * @param {string} itemsSelector
 * @param {string} titleSelector
 * @param {string} excerptSelector
 */
const excerptLineClamp = (itemsSelector, titleSelector, excerptSelector) => {
	const articles = document.querySelectorAll(itemsSelector);

	if (articles && articles.length === 0) return;

	articles.forEach(function (article) {
		const titleElement = article.querySelector(titleSelector);
		const title = titleElement.textContent.trim();
		const titleWords = title.split(' ');

		if (titleWords.length >= 4) {
			const excerptElement = article.querySelector(excerptSelector);
			if (excerptElement) {
				excerptElement.classList.remove('line-clamp-4');
				excerptElement.classList.add('line-clamp-3');
			}
		}
	});
};

export default excerptLineClamp;
