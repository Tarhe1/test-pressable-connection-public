/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { useInnerBlocksProps, useBlockProps } from '@wordpress/block-editor';

export default function save({ attributes }) {
	const {
		verticalAlignment,
		width,
		widthSm,
		widthMd,
		widthLg,
		widthXl,
		widthXxl,
	} = attributes;

	const wrapperClasses = classnames(
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

	const blockProps = useBlockProps.save({
		className: wrapperClasses,
	});
	const innerBlocksProps = useInnerBlocksProps.save(blockProps);

	return <div {...innerBlocksProps} />;
}
