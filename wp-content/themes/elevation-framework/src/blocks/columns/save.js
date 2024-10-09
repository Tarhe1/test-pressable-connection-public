/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { useInnerBlocksProps, useBlockProps } from '@wordpress/block-editor';

export default function save({ attributes }) {
	const { hasContainer, verticalAlignment } = attributes;

	let columnsClasses = '';

	if (hasContainer) {
		columnsClasses = 'container';
	} else {
		columnsClasses = '';
	}
	const className = classnames(
		{ [columnsClasses]: columnsClasses },
		'columns',
		'row',
		'justify-content-between',
		{
			[`are-vertically-aligned-${verticalAlignment}`]: verticalAlignment,
		}
	);

	const blockProps = useBlockProps.save({ className });
	const innerBlocksProps = useInnerBlocksProps.save(blockProps);

	return <div {...innerBlocksProps} />;
}
