const waitForUserInteraction = (cb) => {
	var flag = true;
	const scriptInit = () => {
		if (flag) {
			flag = false;
			cb();
		}
	};
	// Mouseover event
	document.querySelector('body').addEventListener('mouseover', scriptInit, {
		once: true,
	});
	// Keydown event
	window.addEventListener('keydown', scriptInit, {
		once: true,
	});
	// Scroll event
	window.addEventListener('scroll', scriptInit, {
		once: true,
	});
};

export default waitForUserInteraction;
