<?php

namespace ElevationFramework\Lib\Frontend;

class Load
{

    protected static $_instance;

    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts'], 10);

        if (function_exists('get_field')) {
            add_action('wp_head', [$this, 'add_head_scripts']);
            add_action('wp_body_open', [$this,  'add_body_scripts']);
            add_action('wp_footer', [$this, 'add_footer_scripts']);
            add_action('wp_footer', [$this, 'add_opacity_to_body']);
        }
    }

    public function enqueue_scripts()
    {
        wp_deregister_script('jquery');
        wp_enqueue_script('jquery', get_template_directory_uri() . '/src/assets/scripts/vendor/jquery-3.7.1.min.js', array(), null, true);

        wp_enqueue_style('elevation-critical-css', get_template_directory_uri() . '/build/critical-assets/style-index.css', [], null);
        wp_enqueue_script('elevation-critical-scripts', get_template_directory_uri() . '/build/critical-assets/script.js', array(), null, true);
        // wp_enqueue_style('elevation-style', get_template_directory_uri() . '/build/assets/style-index.css', [], null);
        // wp_enqueue_script('elevation-scripts', get_template_directory_uri() . '/build/assets/script.js', array(), null, true);

        // Load single templates styles 
        if (is_singular()) {
            wp_enqueue_style('elevation-style-single-post', get_template_directory_uri() . '/build/single/style-index.css', [], null);
        }

        if (is_category() || is_archive() || is_home()) {
            wp_enqueue_style('elevation-style-archive', get_template_directory_uri() . '/build/archive/style-index.css', [], null);
        }

        if (is_search() || is_404()) {
            wp_enqueue_style('elevation-style-miscellaneous', get_template_directory_uri() . '/build/miscellaneous/style-index.css', [], null);
        }
    }

    public function add_opacity_to_body()
    {
        echo '<style>body{opacity:1!important}</style>';
    }

    public function add_head_scripts()
    {
        echo get_field('inside_head_tag', 'option');
    }

    public function add_body_scripts()
    {
        echo get_field('after_body_tag_opens', 'option');
    }

    public function add_footer_scripts()
    {
        echo get_field('before_body_tag_closed', 'option');
    }

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
