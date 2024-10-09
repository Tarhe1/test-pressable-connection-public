import {
	spinerOn,
	animation,
	getParams,
	getSearch,
	getValuesParams,
	getFeaturePosts,
} from './helpers.js';

import './select.js';
import {
	addFilters,
	removeFilters,
	getValuesFiltered,
	removeValuesFiltered,
	getFilterById,
	selectAll,
	modalFilters,
	toggleClearFilterButton,
	addFiltersSelected,
	updateFilterSelected,
	resetPaginator,
} from './filters.js';

var curentPage = 1;
var filters = [];
var postType = '';
var currentValues = {};
var baseUrl = wp_site_url + '/wp-json/wp/v2/';
const container = document.querySelector('#data-container-directory');

document.addEventListener('DOMContentLoaded', function () {
	if (container) {
		init();
	}
});

function getFilters() {
	const taxes = document.querySelectorAll('[data-type="filter"]');
	return getFilterById(taxes);
}

function init() {
	postType = container.dataset.postType;
	filters = getFilters();
	currentValues = getValuesParams();

	search();
	paginator();
	selectAll();
	clearFilters();
	addFilters(currentValues);
	addFiltersSelected(filters);
	removeFilters();
}

function getDirectory() {
	currentValues = getValuesFiltered(filters);
	var search = getSearch();
	var feature_posts = getFeaturePosts();
	var url = baseUrl + postType + '/get_directory';

	var params = {
		...currentValues,
		actualPage: curentPage,
	};

	if (search) {
		params = {
			...params,
			search: search,
		};
	}

	if (feature_posts) {
		params = {
			...params,
			feature_posts: feature_posts,
		};
	}

	const paramsUrl = getParams(params);
	setNewUrl(paramsUrl);
	filters = getFilters();
	spinerOn();

	fetch(url + '?' + paramsUrl)
		.then((response) => response.json())
		.then((data) => {
			animation();
			container.innerHTML = data;
			addFilters(currentValues);
			removeFilters();
			paginator();
			handleSortBy();
			updateFilterSelected(filters);
			toggleClearFilterButton();
			document.dispatchEvent(new Event('getDirectoryFetch'));
		});
}

function setNewUrl(paramsUrl) {
	var urlActual = window.location.href;
	var url = new URL(urlActual);
	url.search = '';
	window.history.replaceState({}, '', url + '?' + paramsUrl);
}

function handleSortBy() {
	const selectOrderBy = document.querySelector('#sortBy');

	if (selectOrderBy) {
		selectOrderBy.addEventListener('change', function (e) {
			e.preventDefault();
			getDirectory();
		});
	}
}

function search() {
	const searchButton = document.querySelector('#search_directory');
	const filterButton = document.querySelector('#filter_search');
	const inputSearch = document.querySelector('#keyword');

	if (filterButton) {
		filterButton.addEventListener('click', function (e) {
			e.preventDefault();
			modalFilters(filters);
			removeCurrentPage();
			resetPaginator();
			getDirectory();
		});
	}

	if (searchButton) {
		searchButton.addEventListener('click', function (e) {
			e.preventDefault();
			removeCurrentPage();
			resetPaginator();
			getDirectory();
		});
	}

	if (inputSearch) {
		inputSearch.addEventListener('keypress', function (e) {
			if (e.key === 'Enter') {
				e.preventDefault();
				removeCurrentPage();
				resetPaginator();
				getDirectory();
			}
		});
	}

	handleSortBy();
}

function paginator() {
	const nav_links = document.querySelectorAll('.nav-links a');
	nav_links.forEach((link) => {
		link.addEventListener('click', (e) => {
			e.preventDefault();
			const currentLink = e.target;

			if (currentLink.classList.contains('next')) {
				curentPage += 1;
			} else if (currentLink.classList.contains('prev')) {
				curentPage -= 1;
			} else {
				curentPage = parseInt(currentLink.innerHTML.trim());
			}
			getDirectory();
		});
	});
}

function clearFilters() {
	[
		document.querySelector('#clearFiltersModal'),
		document.querySelector('#clear_filters'),
	].forEach((e) => {
		getClearFilter(e);
	});
	removeCurrentPage();
	resetPaginator();
}

function getClearFilter(btn) {
	if (btn) {
		btn.addEventListener('click', (e) => {
			e.preventDefault();
			removeValuesFiltered(filters);
			btn.classList.add('visually-hidden');
			getDirectory();
		});
	}
}

function removeCurrentPage() {
	delete currentValues.curentPage;
	curentPage = 1;
}
