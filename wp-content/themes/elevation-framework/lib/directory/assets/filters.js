export function addFilters(currentValues) {
	const resultsLabel = document.querySelector('.filter-results > .label');
	const filterContainer = document.querySelector('.group-results');

	if (!!filterContainer) {
		filterContainer.style.display = 'flex';
		filterContainer.innerHTML = '';

		var newHtml = '';

		Object.keys(currentValues).forEach((tax) => {
			const filter = currentValues[tax].split(',');
			const parent = document.querySelector('#' + tax);
			const search = document.querySelector('#keyword');
			if (
				parent &&
				!parent.classList.contains('sort__results--select') &&
				filter
			) {
				filter.forEach((el) => {
					const label = parent.querySelector(
						'label[for="checkbox-' + el + '"]'
					);
					let showLabel = '';
					if (!!label) {
						showLabel = label.innerHTML;
					} else {
						showLabel = el
							.replace('-', ' ')
							.replace(/\b\w/g, (c) => c.toUpperCase());
					}
					const parentId = parent.id;
					const targetName = label ? `checkbox-${el}` : parentId;
					const type = label
						? 'checkbox'
						: targetName === 'keyword'
							? 'text'
							: 'select';
					if (
						!document.querySelector(
							`.result_filter[data-label="${showLabel}"]`
						)
					) {
						newHtml += `<button class="result_filter" data-type="${type}" data-target="${targetName}" data-label="${showLabel}"><span class="visually-hidden">Remove </span>${showLabel}<span class="visually-hidden">from filtering</span><span aria-hidden="true" class="remove_filter"></span></button>`;
					}
				});
			}
		});
		filterContainer.insertAdjacentHTML('beforeend', newHtml);
		if (resultsLabel) {
			if (newHtml) {
				resultsLabel.style.display = 'block';
			} else {
				resultsLabel.style.display = 'none';
			}
		}
	}
}

export function toggleClearFilterButton() {
	const resultsFilters = document.querySelectorAll(
		'.group-results .result_filter'
	);
	const searchByKeyword = document.querySelector('#keyword');

	const clearFilters = document.querySelector('#clear_filters');
	if (resultsFilters?.length > 0 || searchByKeyword?.value !== '') {
		clearFilters?.classList.remove('visually-hidden');
	} else {
		clearFilters?.classList.add('visually-hidden');
	}
}

export function removeFilters() {
	const resultsFilters = document.querySelectorAll(
		'.group-results .result_filter'
	);
	const searchButton = document.querySelector('#search_directory');
	const clearFilters = document.querySelector('#clear_filters');
	const searchByKeyword = document.querySelector('#keyword');

	clearFilters.addEventListener('click', function () {
		searchByKeyword.value = '';
		resultsFilters.forEach((filter) => {
			const curr = document.querySelector('#' + filter.dataset.target);
			const type = filter.dataset.type;
			if (curr) {
				if (type == 'select') {
					curr.value = '';
				} else {
					curr.checked = false;
				}
			}
			filter.remove();
		});
		searchButton.click();
	});

	resultsFilters.forEach((filter) => {
		console.log(filter);
		filter.addEventListener('click', function () {
			const curr = document.querySelector('#' + filter.dataset.target);
			const type = filter.dataset.type;
			if (curr) {
				if (type == 'select') {
					curr.value = '';
				} else if (type == 'text') {
					curr.value = '';
				} else {
					curr.checked = false;
				}
			}
			filter.remove();
			searchButton.click();
		});
	});
}

export function getValuesFiltered(filters) {
	var values = {};
	var val = '';

	filters.forEach((filter) => {
		const currentFilter = document.querySelector('#' + filter);
		const search = document.querySelector('#keyword');
		if (
			!!currentFilter &&
			(currentFilter.classList.contains('multiselect') ||
				currentFilter.classList.contains('group-filter'))
		) {
			const inputFilters =
				currentFilter.querySelectorAll('input:checked');

			val = Array.from(inputFilters)
				.map(({ value }) => value)
				.join(',');
		} else if (
			!!currentFilter &&
			currentFilter.tagName.toLowerCase() == 'select'
		) {
			val = currentFilter.value;
		} else if (filter === 'keyword' && !!search) {
			val = search.value;
		}
		if (!!val) {
			values = { ...values, [filter]: val };
		}
	});
	return values;
}

export function removeValuesFiltered(filters) {
	const values = [];

	document.querySelector('#keyword').value = '';

	filters.forEach((filter) => {
		const currentFilter = document.querySelector('#' + filter);

		if (
			currentFilter.classList.contains('multiselect') ||
			currentFilter.classList.contains('group-filter')
		) {
			const inputFilters =
				currentFilter.querySelectorAll('input:checked');
			const currentBox = currentFilter.querySelector('.select');
			currentBox.innerHTML = 'All';

			inputFilters.forEach((input) => {
				input.checked = false;
			});
		} else {
			currentFilter.value = '';
		}
	});

	return values;
}

export function getFilterById(taxes) {
	return Array.from(taxes).map((el) => el.id);
}

export function selectAll() {
	document.querySelectorAll('.group-filter').forEach((groupFilter) => {
		groupFilter
			.querySelector('.select')
			.addEventListener('click', function () {
				const inputs = groupFilter.querySelectorAll('input');
				inputs.forEach((e) => {
					e.checked = true;
				});
			});

		groupFilter
			.querySelector('.unselect')
			.addEventListener('click', function () {
				const inputs = groupFilter.querySelectorAll('input');
				inputs.forEach((e) => {
					e.checked = false;
				});
			});
	});
}

export function modalFilters(filters) {
	const modal = document.querySelector('.modal-body');

	filters.forEach((e) => {
		const inputs = modal.querySelectorAll(
			`div[data-filter="${e}"] input:checked`
		);
		inputs.forEach((e) => {
			document.querySelector(`#${e.dataset.target}`).checked = true;
		});
	});
}

function getWidthElement(opcion) {
	var span = document.createElement('span');
	span.textContent = opcion.textContent;
	span.style.visibility = 'hidden';
	span.style.whiteSpace = 'nowrap';
	document.body.appendChild(span);
	var width = span.offsetWidth;
	document.body.removeChild(span);
	return width;
}

export function addFiltersSelected(filters) {
	filters.forEach((el) => {
		const box = document.querySelector('#' + el);
		const boxWidth = box.offsetWidth - 10;

		if (
			!!box &&
			(box.classList.contains('multiselect') ||
				box.classList.contains('group-filter'))
		) {
			box.addEventListener('change', function (e) {
				const inputs = box.querySelectorAll('input:checked');
				const currentBox = box.querySelector('.select');

				var textSelect = '';
				inputs.forEach((input, idx) => {
					const label = box.querySelector(`label[for="${input.id}"]`);
					const labelText = label
						? label.textContent.trim()
						: input.value.split('-').join(' ');

					if (idx === 0) {
						textSelect += labelText;
					} else {
						textSelect += ', ' + labelText;
					}
				});

				currentBox.innerHTML = textSelect;
				const currentBoxWidth = getWidthElement(currentBox);

				if (currentBoxWidth >= boxWidth) {
					currentBox.innerHTML = inputs.length + ' Selected';
				}
				if (!inputs.length) {
					currentBox.innerHTML = 'All';
				}
			});
		}
	});
}

export function updateFilterSelected(filters) {
	filters.forEach((el) => {
		const box = document.querySelector('#' + el);

		if (
			!!box &&
			(box.classList.contains('multiselect') ||
				box.classList.contains('group-filter'))
		) {
			const boxWidth = box.offsetWidth;
			const inputs = box.querySelectorAll('input:checked');
			const currentBox = box.querySelector('.select');

			var textSelect = '';
			inputs.forEach((input, idx) => {
				const label = box.querySelector(`label[for="${input.id}"]`);
				const labelText = label
					? label.textContent.trim()
					: input.value.split('-').join(' ');

				if (idx === 0) {
					textSelect += labelText;
				} else {
					textSelect += ', ' + labelText;
				}
			});

			currentBox.innerHTML = textSelect;
			const currentBoxWidth = getWidthElement(currentBox);

			if (currentBoxWidth >= boxWidth) {
				currentBox.innerHTML = inputs.length + ' Selected';
			}
			if (!inputs.length) {
				currentBox.innerHTML = 'All';
			}
		}
	});
}

export function resetPaginator() {
	// Check the URL for the 'actualPage' parameter
	const url = new URL(window.location.href);
	const actualPage = url.searchParams.get('actualPage');
	if (actualPage && parseInt(actualPage) > 1) {
		// Reset 'actualPage' to 1
		url.searchParams.delete('actualPage');
		window.history.replaceState({}, '', url);
	}

	if (
		window.location.pathname === '/resource-hub/' &&
		!window.location.search.includes('actualPage=')
	) {
		window.addEventListener('DOMContentLoaded', (event) => {
			localStorage.setItem('resourceHubURL', window.location.href);
		});
	}
}
