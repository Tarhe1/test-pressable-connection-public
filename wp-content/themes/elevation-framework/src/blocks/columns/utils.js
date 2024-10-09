/**
 * External dependencies
 */
import { mapValues } from 'lodash';

/**
 * Returns an effective width for a given block. An effective width is equal to
 * its attribute value if set, or a computed value assuming equal distribution.
 *
 * @param {WPBlock} block           Block object.
 * @param {number}  totalBlockCount Total number of blocks in Columns.
 *
 * @return {number} Effective column width.
 */
export function getEffectiveColumnWidth(totalBlockCount) {
	const widthXxl = 12 / totalBlockCount;

	return Math.floor(widthXxl);
}

/**
 * Returns an object of `clientId` → `width` of effective column widths.
 *
 * @param {WPBlock[]} blocks          Block objects.
 * @param {?number}   totalBlockCount Total number of blocks in Columns.
 *                                    Defaults to number of blocks passed.
 *
 * @return {Object<string,number>} Column widths.
 */
export function getColumnWidths(blocks, totalBlockCount = blocks.length) {
	const width = getEffectiveColumnWidth(totalBlockCount);
	const conlumnWidths = blocks.reduce((accumulator, block) => {
		return Object.assign(accumulator, { [block.clientId]: width });
	}, {});

	return conlumnWidths;
}

/**
 * Returns an object of `clientId` → `width` of column widths as redistributed
 * proportional to their current widths, constrained or expanded to fit within
 * the given available width.
 *
 * @param {WPBlock[]} blocks          Block objects.
 * @param {number}    availableWidth  Maximum width to fit within.
 * @param {?number}   totalBlockCount Total number of blocks in Columns.
 *                                    Defaults to number of blocks passed.
 *
 * @return {Object<string,number>} Redistributed column widths.
 */
export function getRedistributedColumnWidths(
	blocks,
	totalBlockCount = blocks.length
) {
	const mappedValues = mapValues(
		getColumnWidths(blocks, totalBlockCount),
		(widthXxl) => {
			return widthXxl;
		}
	);

	return mappedValues;
}

/**
 * Returns a copy of the given set of blocks with new widths assigned from the
 * provided object of redistributed column widths.
 *
 * @param {WPBlock[]}             blocks Block objects.
 * @param {Object<string,number>} widths Redistributed column widths.
 *
 * @return {WPBlock[]} blocks Mapped block objects.
 */
export function getMappedColumnWidths(blocks, widths) {
	return blocks.map((block) => ({
		...block,
		attributes: {
			...block.attributes,
			className: '',
			width: '12',
			widthSm: '12',
			widthMd: `${widths[block.clientId]}`,
			widthLg: `${widths[block.clientId]}`,
			widthXl: `${widths[block.clientId]}`,
			widthXxl: `${widths[block.clientId]}`,
		},
	}));
}

/**
 * Returns an array with columns widths values, parsed or no depends on `withParsing` flag.
 *
 * @param {WPBlock[]} blocks      Block objects.
 * @param {?boolean}  withParsing Whether value has to be parsed.
 *
 * @return {Array<number,string>} Column widths.
 */
export function getWidths(blocks, withParsing = true) {
	return blocks.map((innerColumn) => {
		const innerColumnWidth =
			innerColumn.attributes.width || 12 / blocks.length;

		return withParsing ? parseFloat(innerColumnWidth) : innerColumnWidth;
	});
}
