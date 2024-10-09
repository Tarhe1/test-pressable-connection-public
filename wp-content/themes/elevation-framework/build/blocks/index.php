<?php

namespace ElevationFramework\Blocks;

/**
 * Exclude blocks
 */

function exclude_blocks($blocks)
{
    $blocks_to_exclude = [];
    if (function_exists('get_field')) {
        $blocks_to_exclude = get_field('acf_exclude_blocks', 'option');
    }
    if ($blocks_to_exclude !== null && is_array($blocks_to_exclude)) {
        array_push($blocks_to_exclude, 'index.php');

        $filtered_blocks = array_filter($blocks, function ($block) use ($blocks_to_exclude) {
            return !in_array($block, $blocks_to_exclude);
        });

        return $filtered_blocks;
    }

    return $blocks;
}

/**
 * Load Blocks
 */
function load_blocks()
{
    $blocks = get_blocks();
    $block_path = get_template_directory() . '/build/blocks/';

    foreach ($blocks as $block) {
        if (file_exists($block_path . $block . '/block.json')) {

            register_block_type($block_path . $block . '/block.json');

            if (file_exists($block_path . $block . '/index.php')) {
                include_once $block_path . $block . '/index.php';
            }
        }
    }
}
add_action('init', __NAMESPACE__ . '\load_blocks', 10);

/**
 * Load ACF field groups for blocks
 */
function load_acf_field_group($paths)
{
    $blocks = get_blocks();
    foreach ($blocks as $block) {
        $paths[] = get_template_directory() . '/build/blocks/' . $block;
    }
    return $paths;
}
add_filter('acf/settings/load_json', __NAMESPACE__ . '\load_acf_field_group');

/**
 * Get Blocks
 */
function get_blocks()
{

    $blocks = scandir(get_template_directory() . '/build/blocks/');
    $blocks = array_values(array_diff($blocks, array('..', '.', '.DS_Store', '_base-block')));
    $blocks = exclude_blocks($blocks);

    return $blocks;
}


/**
 * Register Block categories
 *
 * @since 1.0.0
 */
function block_categories($categories)
{

    // Check to see if we already have a elevation category
    $include = true;
    foreach ($categories as $category) {
        if ('elevation' === $category['slug']) {
            $include = false;
        }
    }

    if ($include) {
        $categories = array_merge(
            [
                [
                    'slug'  => 'elevation-blocks',
                    'title' => 'Elevation Blocks'
                ],
                [
                    'slug'  => 'elevation-template',
                    'title' => 'Elevation Template'
                ],
                [
                    'slug'  => 'elevation-structure',
                    'title' => 'Elevation Structure'
                ]
            ],
            $categories
        );
    }

    return $categories;
}
add_filter('block_categories_all', __NAMESPACE__ . '\block_categories', 10, 2);
