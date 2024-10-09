/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	Notice,
	PanelBody,
	RangeControl,
	ToggleControl,
} from '@wordpress/components';

import {
	InspectorControls,
	useInnerBlocksProps,
	BlockControls,
	BlockVerticalAlignmentToolbar,
	__experimentalBlockVariationPicker,
	useBlockProps,
	store as blockEditorStore,
} from '@wordpress/block-editor';
import { withDispatch, useDispatch, useSelect } from '@wordpress/data';
import {
	createBlock,
	createBlocksFromInnerBlocksTemplate,
	store as blocksStore,
} from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import { getMappedColumnWidths, getRedistributedColumnWidths } from './utils';

/**
 * Allowed blocks constant is passed to InnerBlocks precisely as specified here.
 * The contents of the array should never change.
 * The array should contain the name of each block that is allowed.
 * In columns block, the only block we allow is 'elevation/column'.
 *
 * @constant
 * @type {string[]}
 */
const ALLOWED_BLOCKS = ['elevation/column'];

function ColumnsEditContainer({
	attributes: { hasContainer, verticalAlignment, templateLock },
	setAttributes,
	updateAlignment,
	updateColumns,
	clientId,
}) {
	const { count, canInsertColumnBlock } = useSelect(
		(select) => {
			return {
				count: select(blockEditorStore).getBlockCount(clientId),
				canInsertColumnBlock: select(
					blockEditorStore
				).canInsertBlockType('elevation/column', clientId),
			};
		},
		[clientId]
	);

	let columnsClasses = '';

	if (hasContainer) {
		columnsClasses = 'container';
	} else {
		columnsClasses = '';
	}

	const classes = classnames(
		{ [columnsClasses]: columnsClasses },
		'columns',
		'row',
		'justify-content-between',
		{
			[`are-vertically-aligned-${verticalAlignment}`]: verticalAlignment,
		}
	);

	const blockProps = useBlockProps({
		className: classes,
	});

	const innerBlocksProps = useInnerBlocksProps(blockProps, {
		allowedBlocks: ALLOWED_BLOCKS,
		orientation: 'horizontal',
		renderAppender: false,
		templateLock,
	});

	return (
		<>
			<BlockControls>
				<BlockVerticalAlignmentToolbar
					onChange={updateAlignment}
					value={verticalAlignment}
				/>
			</BlockControls>
			<InspectorControls>
				<PanelBody>
					<ToggleControl
						label="Container"
						help={
							hasContainer
								? 'It has container.'
								: 'It has not container.'
						}
						checked={hasContainer}
						onChange={(state) => {
							setAttributes({
								hasContainer: state,
							});
						}}
					/>
					{canInsertColumnBlock && (
						<>
							<RangeControl
								__nextHasNoMarginBottom
								label={__('Columns')}
								value={count}
								onChange={(value) =>
									updateColumns(count, value)
								}
								min={1}
								max={Math.max(6, count)}
							/>
							{count > 6 && (
								<Notice status="warning" isDismissible={false}>
									{__(
										'This column count exceeds the recommended amount and may cause visual breakage.'
									)}
								</Notice>
							)}
						</>
					)}
				</PanelBody>
			</InspectorControls>
			<div {...innerBlocksProps} />
		</>
	);
}

const ColumnsEditContainerWrapper = withDispatch(
	(dispatch, ownProps, registry) => ({
		/**
		 * Update all child Column blocks with a new vertical alignment setting
		 * based on whatever alignment is passed in. This allows change to parent
		 * to overide anything set on a individual column basis.
		 *
		 * @param {string} verticalAlignment the vertical alignment setting
		 */
		updateAlignment(verticalAlignment) {
			const { clientId, setAttributes } = ownProps;
			const { updateBlockAttributes } = dispatch(blockEditorStore);
			const { getBlockOrder } = registry.select(blockEditorStore);

			// Update own alignment.
			setAttributes({ verticalAlignment });

			// Update all child Column Blocks to match.
			const innerBlockClientIds = getBlockOrder(clientId);
			innerBlockClientIds.forEach((innerBlockClientId) => {
				updateBlockAttributes(innerBlockClientId, {
					verticalAlignment,
				});
			});
		},

		/**
		 * Updates the column count, including necessary revisions to child Column
		 * blocks to grant required or redistribute available space.
		 *
		 * @param {number} previousColumns Previous column count.
		 * @param {number} newColumns      New column count.
		 */
		updateColumns(previousColumns, newColumns) {
			const { clientId } = ownProps;
			const { replaceInnerBlocks } = dispatch(blockEditorStore);
			const { getBlocks } = registry.select(blockEditorStore);

			let innerBlocks = getBlocks(clientId);

			// Redistribute available width for existing inner blocks.
			const isAddingColumn = newColumns > previousColumns;

			if (isAddingColumn) {
				innerBlocks = [
					...innerBlocks,
					...Array.from({
						length: newColumns - previousColumns,
					}).map(() => {
						return createBlock('elevation/column');
					}),
				];
			} else {
				// The removed column will be the last of the inner blocks.
				innerBlocks = innerBlocks.slice(
					0,
					-(previousColumns - newColumns)
				);

				// Redistribute as if block is already removed.
			}

			const widths = getRedistributedColumnWidths(innerBlocks);

			innerBlocks = getMappedColumnWidths(innerBlocks, widths);

			console.log('innerBlocks', innerBlocks);

			replaceInnerBlocks(clientId, innerBlocks);
		},
	})
)(ColumnsEditContainer);

function Placeholder({ clientId, name, setAttributes }) {
	const { blockType, defaultVariation, variations } = useSelect(
		(select) => {
			const {
				getBlockVariations,
				getBlockType,
				getDefaultBlockVariation,
			} = select(blocksStore);

			return {
				blockType: getBlockType(name),
				defaultVariation: getDefaultBlockVariation(name, 'block'),
				variations: getBlockVariations(name, 'block'),
			};
		},
		[name]
	);
	const { replaceInnerBlocks } = useDispatch(blockEditorStore);
	const blockProps = useBlockProps();

	return (
		<div {...blockProps}>
			<__experimentalBlockVariationPicker
				icon={blockType?.icon?.src}
				label={blockType?.title}
				variations={variations}
				onSelect={(nextVariation = defaultVariation) => {
					if (nextVariation.attributes) {
						setAttributes(nextVariation.attributes);
					}
					if (nextVariation.innerBlocks) {
						replaceInnerBlocks(
							clientId,
							createBlocksFromInnerBlocksTemplate(
								nextVariation.innerBlocks
							),
							true
						);
					}
				}}
				allowSkip
			/>
		</div>
	);
}

const Edit = (props) => {
	const { clientId } = props;
	const hasInnerBlocks = useSelect(
		(select) => select(blockEditorStore).getBlocks(clientId).length > 0,
		[clientId]
	);
	const Component = hasInnerBlocks
		? ColumnsEditContainerWrapper
		: Placeholder;

	return <Component {...props} />;
};

export default Edit;
