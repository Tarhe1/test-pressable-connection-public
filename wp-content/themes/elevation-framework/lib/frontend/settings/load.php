<?php

namespace  ElevationFramework\Lib\Frontend\Settings;

use ElevationFramework\Lib\Frontend\Settings\Helpers;

class Load
{

    protected static $_instance;

    public function __construct()
    {
        Helpers::instance();
    }

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
