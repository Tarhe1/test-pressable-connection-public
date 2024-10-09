export function getSearch() {
	const search = document.querySelector('#keyword').value;
	return search;
}

export function getFeaturePosts() {
	const feturePosts = document.getElementById('data-container-directory')
		?.dataset?.['feature_posts'];
	return feturePosts;
}

export function spinerOn() {
	var dataContainer = document.getElementById('data-container-directory');
	dataContainer.innerHTML = '<span class="spiner-contain"></span>';
}

export function arrayDiff(arr1, arr2) {
	return JSON.stringify(arr1) !== JSON.stringify(arr2);
}

export function getValuesParams() {
	var parametros = window.location.search;
	parametros = parametros.substring(1);
	var paramsArr = parametros.split('&');
	var paramsObj = {};

	paramsArr.forEach(function (el) {
		if (!el.length) {
			return '';
		}
		var params = el.split('=');
		var tax = decodeURIComponent(params[0]);
		var val = decodeURIComponent(params[1]);
		const currentFilter = document.querySelector('#' + tax);

		if (tax == 'search') {
			document.querySelector('#keyword').value = val;
		} else {
			activeCheckboxes(currentFilter, val);
		}
		paramsObj = { ...paramsObj, [tax]: val };
	});

	return paramsObj;
}

function getWidthOption(option) {
	var span = document.createElement('span');
	span.textContent = option.textContent;
	span.style.visibility = 'hidden';
	span.style.whiteSpace = 'nowrap';
	document.body.appendChild(span);
	var width = span.offsetWidth;
	document.body.removeChild(span);
	return width;
}

function addViewSelect(box, boxWidth) {
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
	const currentBoxWidth = getWidthOption(currentBox);

	if (currentBoxWidth >= boxWidth) {
		currentBox.innerHTML = inputs.length + ' Selected';
	}
	if (!inputs.length) {
		currentBox.innerHTML = 'All';
	}
}

function activeCheckboxes(current, val) {
	if (
		!!current &&
		(current.classList.contains('multiselect') ||
			current.classList.contains('group-filter'))
	) {
		const taxes = val.split(',');
		const boxWidth = current.offsetWidth;

		taxes.forEach((tax) => {
			current.querySelector('#checkbox-' + tax).checked = true;
		});
		addViewSelect(current, boxWidth);
	} else if (!!current) {
		current.value = val;
	}
}

export function animation() {
	var dataContainerPosition =
		document.getElementById('data-container-directory').offsetTop - 200;

	window.scrollTo({
		top: dataContainerPosition,
		behavior: 'smooth',
	});
}

export function getParams(params) {
	return Object.keys(params)
		.map((key) => key + '=' + params[key])
		.join('&');
}
