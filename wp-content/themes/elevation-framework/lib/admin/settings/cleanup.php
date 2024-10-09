<?php

namespace  ElevationFramework\Lib\Admin\Settings;

use  ElevationFramework\Lib\Admin\Controls\Load as Controls;

class Cleanup
{

    protected static $_instance;

    public function __construct()
    {
        add_action('after_setup_theme', [$this, 'start_cleanup']);
    }

    public function start_cleanup()
    {
        // Launching operation cleanup.
        add_action('init', [$this,  'wp_cleanup_head']);

        // Remove WP version from RSS.
        add_filter('the_generator', [$this, 'wp_remove_rss_version']);

        // Remove pesky injected css for recent comments widget.
        add_filter('wp_head', [$this, 'wp_remove_wp_widget_recent_comments_style'], 1);

        // Clean up comment styles in the head.
        add_action('wp_head', [$this, 'wp_remove_recent_comments_style'], 1);

        // Remove inline width attribute from figure tag
        add_filter('img_caption_shortcode', [$this, 'wp_remove_figure_inline_style'], 10, 3);

        // Remove default Users Sitemap
        add_filter('wp_sitemaps_add_provider', [$this, 'wp_remove_users_sitemap'], 10, 2);

        // Remove default Users JSON API
        add_filter('rest_authentication_errors', [$this, 'wp_snippet_disable_rest_api']);
    }

    public function wp_cleanup_head()
    {

        // EditURI link.
        remove_action('wp_head', 'rsd_link');

        // Category feed links.
        remove_action('wp_head', 'feed_links_extra', 3);

        // Post and comment feed links.
        remove_action('wp_head', 'feed_links', 2);

        // Windows Live Writer.
        remove_action('wp_head', 'wlwmanifest_link');

        // Index link.
        remove_action('wp_head', 'index_rel_link');

        // Previous link.
        remove_action('wp_head', 'parent_post_rel_link', 10);

        // Start link.
        remove_action('wp_head', 'start_post_rel_link', 10);

        // Canonical.
        remove_action('wp_head', 'rel_canonical', 10);

        // Shortlink.
        remove_action('wp_head', 'wp_shortlink_wp_head', 10);

        // Links for adjacent posts.
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);

        // WP version.
        remove_action('wp_head', 'wp_generator');

        // Emoji detection script.
        remove_action('wp_head', 'print_emoji_detection_script', 7);

        // Emoji styles.
        remove_action('wp_print_styles', 'print_emoji_styles');

        // Remove dns-prefetch Link from WordPress Head (Frontend)
        remove_action('wp_head', 'wp_resource_hints', 2);

        // Remove WP dashicon css
        if (!is_admin_bar_showing() && !Controls::instance()->is_login() && !is_admin()) {
            wp_deregister_style('dashicons');
            wp_deregister_script('wp-polyfill');
            wp_deregister_script('regenerator-runtime');
        }
    }

    public function wp_remove_rss_version()
    {
        return '';
    }

    public function wp_remove_users_sitemap($provider, $name)
    {
        return ($name == 'users') ? false : $provider;
    }

    public function wp_snippet_disable_rest_api()
    {
        if (str_contains($_SERVER['REQUEST_URI'], '/wp-json/wp/v2/users') && !is_user_logged_in()) {
            return new WP_Error('rest_disabled', __('The WordPress REST API users endpoint has been disabled.'), array('status' => 404));
        }
    }

    public function wp_remove_wp_widget_recent_comments_style()
    {
        if (has_filter('wp_head', 'wp_widget_recent_comments_style')) {
            remove_filter('wp_head', 'wp_widget_recent_comments_style');
        }
    }

    public function wp_remove_recent_comments_style()
    {
        global $wp_widget_factory;
        if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
            remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
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
