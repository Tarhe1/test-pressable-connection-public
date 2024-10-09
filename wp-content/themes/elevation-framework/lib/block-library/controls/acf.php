<?php

namespace ElevationFramework\Lib\BlockLibrary\Controls;

class ACF
{
    protected static $_instance;

    public $paths_json = ELEVATION_THEME_DIR . '/lib/block-library/controls/json';

    public function __construct()
    {
        add_action('acf/init', [$this, 'blocks_acf_init']);
        add_filter('acf/settings/save_json', [$this, 'save_json']);
        add_filter('acf/settings/load_json', [$this, 'load_json']);
        add_filter('tiny_mce_before_init', [$this, 'color_options']);

        $this->add_option_page();
        styles::instance();
    }

    public function blocks_acf_init()
    {
        if (function_exists('acf_register_block')) {
            $files = glob(__DIR__ . '/../build/blocks/*.php');
            foreach ($files as $file) {
                require_once($file);
            }
        }
    }

    public function load_json($paths)
    {
        // remove original path (optional)
        unset($paths[0]);
        // append path
        $paths[] = $this->paths_json;
        return $paths;
    }

    public function save_json($path)
    {
        $path = $this->paths_json;
        return $path;
    }

    public function add_option_page()
    {
        if (!function_exists('acf_add_options_page')) {
            return;
        }

        $parent = acf_add_options_page(array(
            'page_title' => 'Global Options',
            'menu_title' => 'Options',
            'menu_slug' => 'site-options',
            'capability' => 'manage_options',
            'icon_url' => 'dashicons-admin-site',
            'redirect' => false,
        ));

        // Add sub page for exclude blocks
        if (get_current_user_id() == 1) {
            $child = acf_add_options_sub_page(array(
                'page_title'  => __('Exclude Blocks'),
                'menu_title'  => __('Exclude Blocks'),
                'parent_slug' => $parent['menu_slug'],
            ));
        }
    }

    public function get_theme_colors($mce4 = false)
    {
        // Get colors palette registerd in theme support
        $color_palette_json =  wp_remote_get(ELEVATION_THEME_DIR . '/theme.json');
        $color_palette = wp_remote_retrieve_body($color_palette_json);

        if (!empty($color_palette)) {
            // Get each 'color' value (hex code)
            $colors_json = json_decode($color_palette);
            $colors = $colors_json->settings->color->palette;

            if ($mce4) {
                $mce4_colors = [];
                foreach ($colors as $color) {
                    $mce4_colors = array_merge($mce4_colors, [str_replace('#', '', $color->color), $color->slug]);
                }

                return [json_encode($mce4_colors), count($colors)];
            } else {
                $color_codes = array_column($colors, 'color');
                return $color_codes;
            }
        } else {

            return false;
        }
    }

    public function color_options($init)
    {
        $custom_colours = $this->get_theme_colors(true);

        if ($custom_colours) {
            // build colour grid default+custom colors
            $init['textcolor_map'] = $custom_colours[0];

            // change the number of rows in the grid if the number of colors changes
            // 8 swatches per row
            $init['textcolor_rows'] = round($custom_colours[1] / 8);
        }

        return $init;
    }

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
