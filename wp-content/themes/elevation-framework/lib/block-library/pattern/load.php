<?php

namespace ElevationFramework\Lib\BlockLibrary\Pattern;

class Load
{
    protected static $_instance;

    public function __construct()
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
