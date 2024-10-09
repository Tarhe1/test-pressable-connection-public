// Function to add CSS file
export const addCSS = (path) => {
	let css = document.createElement('link');
	css.rel = 'stylesheet';
	css.href = `${path}/style-index.css`;
	document.head.appendChild(css);
};

// Function to add JS file
export const addJS = (path, name = 'index') => {
	let js = document.createElement('script');
	js.src = `${path}/${name}.js`;
	document.body.appendChild(js);
};

// Set the base path for the CSS and JS files
export const base = `${window.location.origin}/wp-content/themes/elevation-framework`;

export const blocksWithNoScript = [
	'accordion',
	'buttons',
	'callout',
	'cards',
	'column',
	'columns',
	'directory',
	'directory-map',
	'directory-post',
	'feed-directory',
	'feed-event',
	'feed-post',
	'image-carousel',
	'related-posts',
	'single-page-container',
	'single-page-container-column',
	'sponsors',
	'statistics',
	'team-bios',
	'template-cards',
	'template-contact-box',
	'template-media-contacts',
	'template-presskit',
	'template-sponsors',
	'testimonials',
	'text-photo',
	'timeline-horizontal',
	'timeline-vertical',
	'video',
];
