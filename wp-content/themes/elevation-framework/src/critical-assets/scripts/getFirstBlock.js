const getFirstsBlock = () => {
	const blocks = Array.from(
		document.querySelectorAll('.dynamic-assets-load')
	);

	const firstTwoBlockIds = blocks
		.map((block) => block.dataset.id)
		.slice(0, 2);

	return firstTwoBlockIds;
};

export default getFirstsBlock;
