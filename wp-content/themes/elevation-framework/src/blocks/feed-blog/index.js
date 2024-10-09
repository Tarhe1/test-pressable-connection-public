import './style.scss';
import './editor.scss';
import initSwiper from '../../assets/scripts/utilities/swiper';
import sameHeight from '../../assets/scripts/utilities/sameHeight';

// Initialize Swiper
initSwiper('.block-feed-blog__swiper');

// Set the same height for the related block and the card body
sameHeight('.block-feed-blog__swiper', '.card .card__body');
