var selector = document.querySelector('.gt_selector');

if (selector) {
	// Function to approximate the width of an option
	function getOptionWidth(option) {
		var length = option.textContent.length;
		return length * 7;
	}

	// Function to set the width of the selector
	function setSelectorWidth() {
		var selected_option = selector.options[selector.selectedIndex];
		var option_width = getOptionWidth(selected_option);
		selector.style.width = option_width + 55 + 'px';
	}

	// Set the width of the selector when the page loads
	setSelectorWidth();

	// Set the width of the selector when an option is changed
	selector.addEventListener('change', setSelectorWidth);
}
