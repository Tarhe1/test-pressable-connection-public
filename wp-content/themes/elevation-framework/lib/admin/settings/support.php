<?php

namespace  ElevationFramework\Lib\Admin\Settings;

class Support
{
    protected static $_instance;

    public function __construct()
    {
        add_action('after_setup_theme', [$this, 'setup_theme']);
        add_action('init', [$this, 'register_pattern_categories']);
        add_action('init',  [$this, 'theme_json']);
        add_filter('wp_check_filetype_and_ext', [$this, 'check_filetype'], 10, 4);
        add_filter('upload_mimes', [$this, 'allow_mimes']);
        add_filter('mce_buttons_2', [$this, 'buttons_mce']);
        add_filter('tiny_mce_before_init', [$this, 'tiny_mce_init']);
        add_filter('tiny_mce_before_init', [$this, 'tiny_insert_formats']);
        add_action('wp_dashboard_setup', [$this, 'remove_draft_widget'], 999);
        add_filter('allow_dev_auto_core_updates', '__return_false');
        add_filter('get_the_archive_title', [$this, 'get_title']);
    }

    public function setup_theme()
    {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('custom-logo');

        add_image_size('hd', 1920, 1080);
        add_image_size('lg', 900, 600);
        add_image_size('md', 600, 400);

        add_theme_support(
            'html5',
            array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            )
        );

        add_theme_support('block-templates');
        remove_theme_support('core-block-patterns');
    }

    public function register_pattern_categories()
    {
        register_block_pattern_category(
            'contact',
            array('label' => __('Contact Template', 'elevationweb'))
        );
        register_block_pattern_category(
            'media-center',
            array('label' => __('Media Center', 'elevationweb'))
        );
    }

    public function theme_json()
    {
        add_theme_support('wp-block-styles');
    }

    public function check_filetype($data, $file, $filename, $mimes)
    {
        global $wp_version;

        $filetype = wp_check_filetype($filename, $mimes);

        return [
            'ext'             => $filetype['ext'],
            'type'            => $filetype['type'],
            'proper_filename' => $data['proper_filename']
        ];
    }

    public function  allow_mimes($mimes)
    {
        $mimes['json']   = 'application/json';
        $mimes['svg'] = 'image/svg+xml';
        $mimes['svgz'] = 'image/svg+xml';
        $mimes['csv']    = 'text/csv';
        return $mimes;
    }

    public function buttons_mce($buttons)
    {
        array_unshift($buttons, 'styleselect'); // Add Formats Select
        return $buttons;
    }

    public function tiny_mce_init($settings)
    {
        $settings['theme_advanced_blockformats'] = 'p,h1,h2,h3,h4';

        $style_formats = array(
            array(
                'title' => 'Headers',
                'items' => array(
                    array(
                        'title' => 'Header 1',
                        'classes' => 'h1',
                        'selector' => 'p, h1, h2, h3, h4, h5, h6, span, div, blockquote',
                    ),
                    array(
                        'title' => 'Header 2',
                        'classes' => 'h2',
                        'selector' => 'p, h1, h2, h3, h4, h5, h6, span, div, blockquote',
                    ),
                    array(
                        'title' => 'Header 3',
                        'classes' => 'h3',
                        'selector' => 'p, h1, h2, h3, h4, h5, h6, span, div, blockquote',
                    ),
                    array(
                        'title' => 'Header 4',
                        'classes' => 'h4',
                        'selector' => 'p, h1, h2, h3, h4, h5, h6, span, div, blockquote',
                    ),
                    array(
                        'title' => 'Header 5',
                        'classes' => 'h5',
                        'selector' => 'p, h1, h2, h3, h4, h5, h6, span, div, blockquote',
                    ),
                    array(
                        'title' => 'Header 6',
                        'classes' => 'h6',
                        'selector' => 'p, h1, h2, h3, h4, h5, h6, span, div, blockquote',
                    ),
                )
            ),
            array(
                'title' => 'Buttons',
                'items' => array(
                    array(
                        'title' => 'Simple Dark w/arrow Start',
                        'selector' => 'a',
                        'classes' => 'cta cta--simple-dark-arrow-left',
                        'inline' => 'span',
                    ),
                )
            ),
            array(
                'title' => 'Bold',
                'inline' => 'span',
                'classes' => 'font-bold'
            ),
            array(
                'title' => 'Title on List',
                'inline' => 'span',
                'classes' => 'd-block font-bold'
            ),
            array(
                'title' => 'Important text',
                'block' => 'div',
                'classes' => 'important-text',
                'wrapper' => false
            ),
            array(
                'title' => 'Numbers List Two Column',
                'selector' => 'ol',
                'classes' => 'column-list-2'
            ),
            array(
                'title' => 'Bullets List Two Column',
                'selector' => 'ul',
                'classes' => 'column-list-2'
            ),
        );

        $settings['style_formats'] = json_encode($style_formats);

        return $settings;
    }

    public function tiny_insert_formats($init_array)
    {
        $init_array['formats'] = json_encode([
            'spanSubhead' => [
                'selector' => 'span',
                'block'    => 'span',
                'classes'  => 'subtitle',
            ],
        ], JSON_THROW_ON_ERROR);

        // remove from that array not needed formats
        $block_formats = [
            'Paragraph=p',
            'Heading 1=h1',
            'Heading 2=h2',
            'Heading 3=h3',
            'Heading 4=h4',
            'Heading 5=h5',
            'Heading 6=h6',
            'Subhead=spanSubhead'
        ];
        $init_array['block_formats'] = implode(';', $block_formats);

        return $init_array;
    }

    public function remove_draft_widget()
    {
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    }


    public function get_title($title)
    {
        if (is_category()) {
            $title = single_cat_title('', false);
        } elseif (is_tag()) {
            $title = single_tag_title('', false);
        } elseif (is_author()) {
            $title = '<span class="vcard">' . get_the_author() . '</span>';
        } elseif (is_tax()) { //for custom post types
            $title = sprintf(__('%1$s'), single_term_title('', false));
        } elseif (is_post_type_archive()) {
            $title = post_type_archive_title('', false);
        }
        return $title;
    }

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
