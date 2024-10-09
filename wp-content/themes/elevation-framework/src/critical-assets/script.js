import loadBlocksCSSandJS from './scripts/loadBlocksCSSandJS';
import loadAssetsCSSandJS from './scripts/loadAssetsCSSandJS';
import waitForUserInteraction from './scripts/waitForUserInteraction';
import getFirstsBlock from './scripts/getFirstBlock';
import showBody from './scripts/showBody';

const excludedBlocks = [];

// Include the first block in the excludedBlocks array
const firstTwoBlockIds = getFirstsBlock();
if (firstTwoBlockIds) {
	excludedBlocks.push(...firstTwoBlockIds);
}

// Add more blocks to the excludedBlocks array if needed
// excludedBlocks.push('BLOCK_ID_1');

loadBlocksCSSandJS(excludedBlocks, true);

// showBody();

// Wait for user interaction before loading CSS and JS files
waitForUserInteraction(() => {
	loadAssetsCSSandJS();
	loadBlocksCSSandJS(excludedBlocks);
});
