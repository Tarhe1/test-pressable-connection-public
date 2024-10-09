<?php

namespace  ElevationFramework\Lib\Admin\Settings;

class Disable
{
    protected static $_instance;

    public function __construct()
    {
        add_action('admin_init', [$this, 'disable_comments']);
        add_filter('comments_open', [$this, 'disable_comments_status'], 20, 2);
        add_filter('pings_open', [$this, 'disable_comments_status'], 20, 2);
        add_filter('comments_array', [$this, 'disable_comments_existing_comments'], 10, 2);
        add_action('admin_menu', [$this, 'disable_comments_admin_menu']);
        add_action('admin_init', [$this, 'disable_comments_admin_menu_redirect']);
        add_action('admin_init', [$this, 'disable_comments_dashboard']);
        add_action('init', [$this, 'disable_comments_admin_bar']);
        add_filter('rest_endpoints', [$this, 'disable_default_endpoints']);
    }

    public function disable_default_endpoints($endpoints)
    {
        $endpoints_to_remove = array(
            '/wp/v2/users'
        );

        if (!is_user_logged_in()) {
            foreach ($endpoints_to_remove as $rem_endpoint) {
                // $base_endpoint = "/wp/v2/{$rem_endpoint}";
                foreach ($endpoints as $maybe_endpoint => $object) {
                    if (stripos($maybe_endpoint, $rem_endpoint) !== false) {
                        unset($endpoints[$maybe_endpoint]);
                    }
                }
            }
        }
        return $endpoints;
    }

    public function disable_comments()
    {
        $post_types = get_post_types();
        foreach ($post_types as $post_type) {
            if (post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    }

    public function disable_comments_status()
    {
        return false;
    }

    public function disable_comments_existing_comments($comments)
    {
        $comments = array();
        return $comments;
    }

    public function disable_comments_admin_menu()
    {
        remove_menu_page('edit-comments.php');
    }

    public function disable_comments_admin_menu_redirect()
    {
        global $pagenow;
        if ($pagenow === 'edit-comments.php') {
            wp_redirect(admin_url());
            exit;
        }
    }

    public function disable_comments_dashboard()
    {
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    }


    public function disable_comments_admin_bar()
    {
        if (is_admin_bar_showing()) {
            remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
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
