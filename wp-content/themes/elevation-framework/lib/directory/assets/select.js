// import { addFiltersSelected } from './filters';

function isElementInView(element) {
	var bounding = element.getBoundingClientRect();

	return (
		bounding.top >= 0 &&
		bounding.left >= 0 &&
		bounding.bottom <=
			(window.innerHeight || document.documentElement.clientHeight) &&
		bounding.right <=
			(window.innerWidth || document.documentElement.clientWidth)
	);
}

function getActionFromKey(event, menuOpen) {
	const { key, altKey, ctrlKey, metaKey } = event;
	const openKeys = ['ArrowDown', 'ArrowUp', 'Enter', ' ']; // all keys that will do the default open action
	// handle opening when closed
	if (!menuOpen && openKeys.includes(key)) {
		return SelectActions.Open;
	}

	// home and end move the selected option when open or closed
	if (key === 'Home') {
		return SelectActions.First;
	}
	if (key === 'End') {
		return SelectActions.Last;
	}

	// handle keys when open
	if (menuOpen) {
		if (key === 'ArrowUp' && altKey) {
			return SelectActions.CloseSelect;
		} else if (key === 'ArrowDown' && !altKey) {
			return SelectActions.Next;
		} else if (key === 'ArrowUp') {
			return SelectActions.Previous;
		} else if (key === 'PageUp') {
			return SelectActions.PageUp;
		} else if (key === 'PageDown') {
			return SelectActions.PageDown;
		} else if (key === 'Escape') {
			return SelectActions.Close;
		} else if (key === 'Tab') {
			return SelectActions.NextTab;
		} else if (key === 'Enter' || key === ' ') {
			return SelectActions.Active;
		}
	}
}

const SelectActions = {
	Close: 0,
	CloseSelect: 1,
	First: 2,
	Last: 3,
	Next: 4,
	Open: 5,
	PageDown: 6,
	PageUp: 7,
	Previous: 8,
	Select: 9,
	Type: 10,
	Active: 11,
	NextTab: 12,
};

const Select = function (el) {
	this.el = el;
	this.labelEl = el.querySelector('.filter__label');
	this.comboEl = el.querySelector('.multiselect__selectBox .select');
	this.listboxEl = el.querySelector('.multiselect__checkboxes');

	// data
	this.idBase = !!this.comboEl ? this.comboEl.id : false;
	this.options = this.el.querySelectorAll('.form-check');

	// state
	this.activeIndex = 0;
	this.open = false;
	this.searchString = '';
	this.searchTimeout = null;

	// init
	if (el && this.comboEl && this.listboxEl) {
		this.init();
	}
};

Select.prototype.init = function () {
	this.labelEl.addEventListener('click', this.onLabelClick.bind(this)); // added
	this.comboEl.addEventListener('blur', this.onComboBlur.bind(this));
	this.listboxEl.addEventListener('focusout', this.onComboBlur.bind(this));
	this.comboEl.addEventListener('click', this.onComboClick.bind(this));
	this.comboEl.addEventListener('keydown', this.onComboKeyDown.bind(this));
};

Select.prototype.onLabelClick = function () {
	this.comboEl.focus();
};

Select.prototype.onComboBlur = function (event) {
	// do nothing if relatedTarget is contained within listboxEl
	if (this.listboxEl.contains(event.relatedTarget)) {
		return;
	}

	// select current option and close
	if (this.open && event.relatedTarget != this.comboEl) {
		this.updateMenuState(false, false);
	}
};

Select.prototype.onComboClick = function () {
	this.updateMenuState(!this.open, false);
};

Select.prototype.onComboKeyDown = function (event) {
	const { key } = event;
	const max = this.options.length - 1;

	const action = getActionFromKey(event, this.open);
	switch (action) {
		case SelectActions.Last:
		case SelectActions.First:
			this.updateMenuState(true);
		// intentional fallthrough
		case SelectActions.Next:
		case SelectActions.Previous:
		case SelectActions.PageUp:
		case SelectActions.PageDown:
			event.preventDefault();
			return this.onOptionChange(
				getUpdatedIndex(this.activeIndex, max, action)
			);
		case SelectActions.CloseSelect:
			event.preventDefault();
		case SelectActions.Close:
			event.preventDefault();
			return this.updateMenuState(false);
		case SelectActions.Open:
			event.preventDefault();
			return this.updateMenuState(true);
		case SelectActions.NextTab:
			return this.updateMenuState(false);
		case SelectActions.Active:
			event.preventDefault();
			return this.ActiveInput();
	}
};

function getUpdatedIndex(currentIndex, maxIndex, action) {
	const pageSize = 10; // used for pageup/pagedown

	switch (action) {
		case SelectActions.First:
			return 0;
		case SelectActions.Last:
			return maxIndex;
		case SelectActions.Previous:
			return Math.max(0, currentIndex - 1);
		case SelectActions.Next:
			return Math.min(maxIndex, currentIndex + 1);
		case SelectActions.PageUp:
			return Math.max(0, currentIndex - pageSize);
		case SelectActions.PageDown:
			return Math.min(maxIndex, currentIndex + pageSize);
		default:
			return currentIndex;
	}
}

function isScrollable(element) {
	return element && element.clientHeight < element.scrollHeight;
}
Select.prototype.onOptionChange = function (index) {
	// update state
	this.activeIndex = index;

	// update active option styles
	const options = this.options;

	if (!options.length) {
		return;
	}

	[...options].forEach((optionEl) => {
		optionEl.classList.remove('option-current');
	});

	options[index].classList.add('option-current');
	// ensure the new option is in view
	if (isScrollable(this.listboxEl)) {
		maintainScrollVisibility(options[index], this.listboxEl);
	}

	// ensure the new option is in view
	if (!isElementInView(options[index])) {
		options[index].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
	}
};

Select.prototype.updateMenuState = function (open, callFocus = true) {
	if (this.open === open) {
		return;
	}
	// update state
	this.open = open;
	this.comboEl.setAttribute('aria-expanded', `${open}`);

	this.onOptionChange(0);

	open
		? this.listboxEl.classList.add('active')
		: this.listboxEl.classList.remove('active');
	// update aria-expanded and styles

	if (!open === '' && !isElementInView(this.comboEl)) {
		this.comboEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
	}

	// move focus back to the combobox, if needed
	callFocus && this.comboEl.focus();
};

Select.prototype.ActiveInput = function () {
	const current = this.options[this.activeIndex].querySelector('input');

	current.checked ? (current.checked = false) : (current.checked = true);
	const box = this.el.querySelector('.multiselect');
	const boxWidth = box.offsetWidth - 10;
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
};

window.addEventListener('load', function () {
	const selectEls = document.querySelectorAll('.filter__item');

	selectEls.forEach((el) => {
		new Select(el);
	});
});

function maintainScrollVisibility(activeElement, scrollParent) {
	const { offsetHeight, offsetTop } = activeElement;
	const { offsetHeight: parentOffsetHeight, scrollTop } = scrollParent;

	const isAbove = offsetTop < scrollTop;
	const isBelow = offsetTop + offsetHeight > scrollTop + parentOffsetHeight;

	if (isAbove) {
		scrollParent.scrollTo(0, offsetTop);
	} else if (isBelow) {
		scrollParent.scrollTo(0, offsetTop - parentOffsetHeight + offsetHeight);
	}
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
