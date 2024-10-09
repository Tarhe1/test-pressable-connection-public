/**
 * WordPress dependencies
 */
import {
	createBlock,
	createBlocksFromInnerBlocksTemplate,
} from '@wordpress/blocks';

const MAXIMUM_SELECTED_BLOCKS = 6;

const transforms = {
	from: [
		{
			type: 'block',
			isMultiBlock: true,
			blocks: ['*'],
			__experimentalConvert: (blocks) => {
				const columnWidth = +(100 / blocks.length).toFixed(2);
				const innerBlocksTemplate = blocks.map(
					({ name, attributes, innerBlocks }) => [
						'elevation/column',
						{
							width: '12',
							widthSm: '12',
							widthMd: `${columnWidth}`,
							widthLg: `${columnWidth}`,
							widthXl: `${columnWidth}`,
							widthXxl: `${columnWidth}`,
						},
						[[name, { ...attributes }, innerBlocks]],
					]
				);
				return createBlock(
					'elevation/columns',
					{},
					createBlocksFromInnerBlocksTemplate(innerBlocksTemplate)
				);
			},
			isMatch: ({ length: selectedBlocksLength }, blocks) => {
				// If a user is trying to transform a single Columns block, skip
				// the transformation. Enabling this functiontionality creates
				// nested Columns blocks resulting in an unintuitive user experience.
				// Multiple Columns blocks can still be transformed.
				if (
					blocks.length === 1 &&
					blocks[0].name === 'elevation/columns'
				) {
					return false;
				}

				return (
					selectedBlocksLength &&
					selectedBlocksLength <= MAXIMUM_SELECTED_BLOCKS
				);
			},
		},
	],
	to: [
		{
			type: 'block',
			blocks: ['*'],
			transform: (attributes, innerBlocks) =>
				innerBlocks.flatMap((innerBlock) => innerBlock.innerBlocks),
		},
	],
};

export default transforms;
