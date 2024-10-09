<?php

namespace ElevationFramework\Lib\Admin\Menu;

class Load
{
    protected static $_instance;

    public function __construct()
    {
        // $this->mega_menu();
        add_filter('after_setup_theme', [$this, 'register_menus']);
    }

    public function register_menus()
    {
        \register_nav_menus(
            array(
                'menu-1' => esc_html__('Main Menu', 'elevation'),
                'menu-2' => esc_html__('Sub Menu', 'elevation'),
                'menu-3' => esc_html__('Footer Menu', 'elevation'),
            )
        );
    }

    public static function mega_menu($current_menu = 'menu-1')
    {
        $array_menu = wp_get_nav_menu_items($current_menu);

        $menu = [];
        if ($array_menu) {
            foreach ($array_menu as $m) {
                if (empty($m->menu_item_parent)) {
                    $menu[$m->ID]              = [];
                    $menu[$m->ID]['ID']        = $m->ID;
                    $menu[$m->ID]['object_id'] = $m->object_id;
                    $menu[$m->ID]['title']     = $m->title;
                    $menu[$m->ID]['url']       = $m->url;
                    $menu[$m->ID]['className'] = $m->classes;
                    $menu[$m->ID]['children']  = [];
                }
            }
        }
        $submenu = [];
        $subsubmenu = [];
        if ($array_menu) {
            foreach ($array_menu as $m) {
                if ($m->menu_item_parent) {
                    $submenu[$m->ID]              = [];
                    $submenu[$m->ID]['ID']        = $m->ID;
                    $submenu[$m->ID]['object_id'] = $m->object_id;
                    $submenu[$m->ID]['title']     = $m->title;
                    $submenu[$m->ID]['url']       = $m->url;
                    $submenu[$m->ID]['className'] = $m->classes;
                    foreach ($array_menu as $c) {
                        if ($c->menu_item_parent) {
                            $subsubmenu[$c->ID]                                = [];
                            $subsubmenu[$c->ID]['ID']                          = $c->ID;
                            $subsubmenu[$c->ID]['object_id']                   = $c->object_id;
                            $subsubmenu[$c->ID]['title']                       = $c->title;
                            $subsubmenu[$c->ID]['url']                         = $c->url;
                            $submenu[$c->menu_item_parent]['children'][$c->ID] = $subsubmenu[$c->ID];
                        }
                    }

                    $menu[$m->menu_item_parent]['children'][$m->ID] = $submenu[$m->ID];
                }
            }
        }

        return $menu;
    }


    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
