import { addCSS, addJS, base } from './utils';

// Load CSS and JS files for each block
const loadAssetsCSSandJS = () => {
	// Path to assets folder
	const path = `${base}/build/assets`;
	// Load CSS file
	addCSS(path);

	// Load JS file
	addJS(path, 'script');
};

export default loadAssetsCSSandJS;
