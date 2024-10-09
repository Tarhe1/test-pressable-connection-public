/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import {
	InnerBlocks,
	BlockControls,
	BlockVerticalAlignmentToolbar,
	InspectorControls,
	useBlockProps,
	useSetting,
	useInnerBlocksProps,
	store as blockEditorStore,
} from '@wordpress/block-editor';
import { PanelBody, RangeControl } from '@wordpress/components';
import { useSelect, useDispatch } from '@wordpress/data';
import { sprintf, __ } from '@wordpress/i18n';

export default function columnEdit({ attributes, setAttributes, clientId }) {
	const {
		templateLock,
		allowedBlocks,
		verticalAlignment,
		width,
		widthSm,
		widthMd,
		widthLg,
		widthXl,
		widthXxl,
	} = attributes;
	const { columnsIds, hasChildBlocks, rootClientId } = useSelect(
		(select) => {
			const { getBlockOrder, getBlockRootClientId } =
				select(blockEditorStore);

			const rootId = getBlockRootClientId(clientId);

			return {
				hasChildBlocks: getBlockOrder(clientId).length > 0,
				rootClientId: rootId,
				columnsIds: getBlockOrder(rootId),
			};
		},
		[clientId]
	);

	const { updateBlockAttributes } = useDispatch(blockEditorStore);

	const updateAlignment = (value) => {
		// Update own alignment.
		setAttributes({ verticalAlignment: value });
		// Reset parent Columns block.
		updateBlockAttributes(rootClientId, {
			verticalAlignment: null,
		});
	};

	const classes = classnames(
		'block-elevation-columns',
		'col',
		{
			[`col-${width}`]: width,
		},
		{
			[`col-sm-${widthSm}`]: widthSm,
		},
		{
			[`col-md-${widthMd}`]: widthMd,
		},
		{
			[`col-lg-${widthLg}`]: widthLg,
		},
		{
			[`col-xl-${widthXl}`]: widthXl,
		},
		{
			[`col-xxl-${widthXxl}`]: widthXxl,
		},
		{
			[`align-items-${verticalAlignment}`]: verticalAlignment,
		}
	);

	const blockProps = useBlockProps({
		className: classes,
	});

	const columnsCount = columnsIds.length;
	const currentColumnPosition = columnsIds.indexOf(clientId) + 1;

	const label = sprintf(
		/* translators: 1: Block label (i.e. "Block: Column"), 2: Position of the selected block, 3: Total number of sibling blocks of the same type */
		__('%1$s (%2$d of %3$d)'),
		blockProps['aria-label'],
		currentColumnPosition,
		columnsCount
	);

	const innerBlocksProps = useInnerBlocksProps(
		{ ...blockProps, 'aria-label': label },
		{
			templateLock,
			allowedBlocks,
			renderAppender: hasChildBlocks
				? undefined
				: InnerBlocks.ButtonBlockAppender,
		}
	);

	const controls = (
		<>
			<BlockControls>
				<BlockVerticalAlignmentToolbar
					onChange={updateAlignment}
					value={verticalAlignment}
				/>
			</BlockControls>
			<InspectorControls>
				<PanelBody title={__('Column settings')}>
					<RangeControl
						allowReset={true}
						label={__('Column Width XS', 'elevation')}
						resetFallbackValue={0}
						step={1}
						withInputField={true}
						renderTooltipContent={() => '<576px'}
						value={Number(width)}
						onChange={(value) => {
							setAttributes({ width: value });
						}}
						min={0}
						max={12}
					/>
					<RangeControl
						allowReset={true}
						label={__('Column Width Small', 'elevation')}
						resetFallbackValue={0}
						step={1}
						withInputField={true}
						renderTooltipContent={() => '≥576px'}
						value={Number(widthSm)}
						onChange={(value) => {
							setAttributes({ widthSm: value });
						}}
						min={0}
						max={12}
					/>
					<RangeControl
						allowReset={true}
						label={__('Column Width Medium', 'elevation')}
						resetFallbackValue={0}
						step={1}
						withInputField={true}
						renderTooltipContent={() => '≥768px'}
						value={Number(widthMd)}
						onChange={(value) => {
							setAttributes({ widthMd: value });
						}}
						min={0}
						max={12}
					/>
					<RangeControl
						allowReset={true}
						label={__('Column Width Large', 'elevation')}
						resetFallbackValue={0}
						step={1}
						withInputField={true}
						renderTooltipContent={() => '≥992px'}
						value={Number(widthLg)}
						onChange={(value) => {
							setAttributes({ widthLg: value });
						}}
						min={0}
						max={12}
					/>
					<RangeControl
						allowReset={true}
						label={__('Column Width Extra Large', 'elevation')}
						resetFallbackValue={0}
						step={1}
						withInputField={true}
						renderTooltipContent={() => '≥1200px'}
						value={Number(widthXl)}
						onChange={(value) => {
							setAttributes({ widthXl: value });
						}}
						min={0}
						max={12}
					/>
					<RangeControl
						allowReset={true}
						label={__(
							'Column Width Extra extra Large',
							'elevation'
						)}
						resetFallbackValue={0}
						step={1}
						withInputField={true}
						renderTooltipContent={() => '≥1400px'}
						value={Number(widthXxl)}
						onChange={(value) => {
							setAttributes({ widthXxl: value });
						}}
						min={0}
						max={12}
					/>
				</PanelBody>
			</InspectorControls>
		</>
	);

	return (
		<>
			{controls}
			<div {...innerBlocksProps} />
		</>
	);
}
