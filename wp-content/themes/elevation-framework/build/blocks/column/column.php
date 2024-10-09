<?php

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function column_block_init()
{
    $path = dirname(__DIR__, 2);
    register_block_type($path . '/blocks/column');
}
add_action('init', 'column_block_init');
