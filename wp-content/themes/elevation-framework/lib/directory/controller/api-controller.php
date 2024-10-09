<?php

namespace  ElevationFramework\Lib\Directory\Controller;

class ApiController extends \WP_REST_Posts_Controller
{
    protected static $_instance;

    public $post_type = 'resource';

    public function __construct()
    {
        add_action('rest_api_init', array($this, 'register_rest_route'));
        add_action('init', [$this, 'support_post']);
        add_action('init', [$this, 'add_script_js']);
    }

    public function support_post()
    {
        add_post_type_support('resource', 'excerpt');
    }

    public function add_script_js()
    {
        wp_enqueue_script('elevation-custom-directory-script', ELEVATION_THEME_URL . '/lib/directory/assets/main.js', array(), null, true);
        $site_url = get_site_url();
        wp_localize_script('elevation-custom-directory-script', 'wp_site_url', $site_url);
    }

    public function register_rest_route()
    {
        parent::__construct($this->post_type);

        register_rest_route(
            'wp/v2/' . $this->post_type,
            'get_directory',
            array(
                array(
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => array($this, 'get_directory'),
                ),
            )
        );
    }

    public function get_directory()
    {
    }

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
