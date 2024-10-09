import './style.scss';
import './editor.scss';
import initSwiper from '../../assets/scripts/utilities/swiper';
import sameHeight from '../../assets/scripts/utilities/sameHeight';
import excerptLineClamp from '../../assets/scripts/utilities/excerptLineClamp';

// Initialize Swiper
initSwiper('.block__related .swiper__feed');

// Set the same height for the related block and the card body
sameHeight('.block__related', '.card .card__body');

// Set the amount of lines for the excerpt depending on the title length
excerptLineClamp(
	'.block__related .swiper-wrapper article.card',
	'.card__title span',
	'.card__excerpt'
);
