<?php

namespace  ElevationFramework\Lib\BlockLibrary;

use ElevationFramework\Lib\BlockLibrary\Controls\ACF;

class Load
{
    protected static $_instance;

    public $block_dir = ELEVATION_THEME_DIR . '/build/blocks/';

    public function __construct()
    {
        ACF::instance();
        Helpers::instance();
        add_filter('should_load_separate_core_block_assets', '__return_true');
        add_filter('styles_inline_size_limit', '__return_zero');
        add_action('init', [$this, 'load_blocks'], 10);
        add_filter('acf/settings/load_json', [$this, 'load_acf_field_group']);
        add_filter('block_categories_all', [$this, 'block_categories'], 10, 2);
        if (is_admin()) {
            add_action('admin_menu', [$this, 'block_count_add_page']);
        }
    }

    public function load_blocks()
    {
        $blocks = $this->get_blocks();
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

    // mover a acf
    function load_acf_field_group($paths)
    {
        $blocks = $this->get_blocks();
        foreach ($blocks as $block) {
            $paths[] = get_template_directory() . '/build/blocks/' . $block;
        }
        return $paths;
    }

    public function get_blocks()
    {

        $blocks = scandir(get_template_directory() . '/build/blocks/');
        $blocks = array_values(array_diff($blocks, array('..', '.', '.DS_Store', '_base-block')));
        $blocks = $this->exclude_blocks($blocks);

        return $blocks;
    }

    public function block_categories($categories)
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

    public function exclude_blocks($blocks)
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

    public function block_count_add_page()
    {
        add_options_page('Gutenberg Block Count', 'Block Count', 'manage_options', 'block_count', [$this, 'block_count_page']);
    }

    public function block_count_page()
    {
?>
        <div>
            <h2>Gutenberg Block Count</h2>
            <?php $this->count_blocks(); ?>
        </div>

<?php
    }


    public function count_blocks()
    {
        $allPosts       = get_posts(array('posts_per_page' => -1, 'post_type' => 'any', 'fields' => 'ids'));
        $blockCount     = [];
        $totalBlocks    = [];

        foreach ($allPosts as $singlePostID) {
            $name       = get_the_title($singlePostID);
            $type       = get_post_type($singlePostID);
            $content    = get_post_field('post_content', $singlePostID);
            $blocks     = has_blocks($content) ? parse_blocks($content) : false;
            $permalink  = get_permalink($singlePostID);

            $postBlocks = [];
            if ($blocks) {
                foreach ($blocks as $block) {
                    $blockName = $block['blockName'];
                    if (!empty($blockName)) {
                        if (array_key_exists($blockName, $postBlocks)) {
                            $postBlocks[$blockName]++;
                        } else {
                            $postBlocks[$blockName] = 1;
                        }
                        if (array_key_exists($blockName, $totalBlocks)) {
                            $totalBlocks[$blockName]++;
                        } else {
                            $totalBlocks[$blockName] = 1;
                        }
                    }
                }
            }

            $blockCount[] = [
                'name'      => $name,
                'type'      => $type,
                'page'      => $permalink,
                'blocks'    => $postBlocks
            ];
        }

        foreach ($blockCount as $item) {
            $emptyPage = count($item['blocks']) == 0 ? true : false;
            if ($emptyPage) {
                echo '<span style="opacity:0.5">';
            }
            echo '<a href="' . $item['page'] . '"><b>' . $item['name'] . '</b></a> (<i>' . $item['type'] . '</i>) - ';
            if ($emptyPage) {
                echo 'NONE';
            }
            if ($emptyPage) {
                echo '</span>';
            }
            foreach ($item['blocks'] as $blockName => $blockCount) {
                echo '[ ' . $blockName . ' : ' . $blockCount . ' ] ';
            }
            echo '<br>';
        }

        echo '<h2>Totals</h2>';
        foreach ($totalBlocks as $blockName => $blockCount) {
            echo '[ ' . $blockName . ' : ' . $blockCount . ' ]<br>';
        }
    }


    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
