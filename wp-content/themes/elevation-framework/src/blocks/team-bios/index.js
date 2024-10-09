import './style.scss';
import './editor.scss';
import initSwiper from '../../assets/scripts/utilities/swiper';

const hasBlockTeam = document.querySelector('.block__team');
if (hasBlockTeam) {
	/*
	 *	 This move the modals to before </body>
	 */
	const moveModals = document.querySelectorAll('.block__team .modal');
	if (moveModals) {
		moveModals.forEach((moveModal) => {
			document.querySelector('body').append(moveModal);
		});
	}

	/*
	 *	 Filter Actions on Layout Filter
	 */

	const filterItems = document.querySelectorAll('.filterTeam__item');
	if (filterItems) {
		filterItems.forEach((filterItem) => {
			filterItem.addEventListener('click', (event) => {
				const active = document.querySelector('.filterTeam .active');
				active.classList.remove('active');
				event.target.classList.add('active');
				const filter = event.target.getAttribute('data-filter');
				filterSelection(filter);
			});
		});
	}

	filterSelection('all');

	function filterSelection(c) {
		var x, i;
		x = document.getElementsByClassName('col-card');
		if (c == 'all') c = '';
		for (i = 0; i < x.length; i++) {
			w3RemoveClass(x[i], 'show');
			if (x[i].className.indexOf(c) > -1) w3AddClass(x[i], 'show');
		}
	}

	function w3AddClass(element, name) {
		var i, arr1, arr2;
		arr1 = element.className.split(' ');
		arr2 = name.split(' ');
		for (i = 0; i < arr2.length; i++) {
			if (arr1.indexOf(arr2[i]) == -1) {
				element.className += ' ' + arr2[i];
			}
		}
	}

	function w3RemoveClass(element, name) {
		var i, arr1, arr2;
		arr1 = element.className.split(' ');
		arr2 = name.split(' ');
		for (i = 0; i < arr2.length; i++) {
			while (arr1.indexOf(arr2[i]) > -1) {
				arr1.splice(arr1.indexOf(arr2[i]), 1);
			}
		}
		element.className = arr1.join(' ');
	}

	// Initialize Swiper
	initSwiper('.swiper__teams');
}
