<?php

namespace ElevationFramework\Lib\Admin;

use ElevationFramework\Lib\Admin\Assets\Load as Assets;
use ElevationFramework\Lib\Admin\Settings\Cleanup;
use ElevationFramework\Lib\Admin\Settings\Disable;
use  ElevationFramework\Lib\Admin\Settings\Support;
use  ElevationFramework\Lib\Admin\Controls\Load as Controls;
use  ElevationFramework\Lib\Admin\Menu\Load as Menu;
use ElevationFramework\Lib\Admin\Settings\Customizer;

class Load
{
    protected static $_instance;

    public function __construct()
    {
        Assets::instance();
        Controls::instance();
        Support::instance();
        Cleanup::instance();
        Disable::instance();
        Customizer::instance();
        Menu::instance();
    }

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
