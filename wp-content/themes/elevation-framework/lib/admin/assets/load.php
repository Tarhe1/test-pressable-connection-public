<?php

namespace  ElevationFramework\Lib\Admin\Assets;

class Load
{
    protected static $_instance;

    public function __construct()
    {
        add_action('admin_head', [$this, 'add_admin_styles']);
        add_action('init', [$this, 'add_editor_styles']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function add_admin_styles()
    {
        echo '<link rel="stylesheet" href="' . ELEVATION_THEME_URL . '/lib/admin/assets/admin.css' . '" media="all" />';
    }

    public function add_editor_styles()
    {
        add_editor_style('lib/admin/assets/editor.css');
    }

    function enqueue_scripts()
    {
        wp_enqueue_style('elevation-admin-style', ELEVATION_THEME_URL . '/build/assets/index.css', array(), ELEVATION_VERSION);
    }


    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
