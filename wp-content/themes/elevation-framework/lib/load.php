<?php

namespace ElevationFramework\Lib;

final class Load
{

    protected static $_instance;

    public function __construct()
    {

        spl_autoload_register([$this, 'autoload']);
        Admin\Load::instance();
        Frontend\Load::instance();
        if (defined('ACF_PRO') && ACF_PRO) {
            BlockLibrary\Load::instance();
            Directory\Load::instance();
        }
        Plugins\Load::instance();
    }

    public function autoload($class_to_load)
    {

        if (0 !== strpos($class_to_load, __NAMESPACE__)) {
            return;
        }

        if (!class_exists($class_to_load)) {

            $class_file = $this->class_file($class_to_load);

            if (is_readable($class_file)) {
                include_once($class_file);
            } else {
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    $warning_message = sprintf(__('"Can\'t find %s" for "%s" in "%s".', 'elevation'), $class_file, $class_to_load, __NAMESPACE__);
                    trigger_error($warning_message, E_USER_NOTICE);
                }
            }
        }
    }

    public static function class_file($class_name)
    {
        $class_name = str_replace(__NAMESPACE__, '', $class_name);
        $filename = strtolower(preg_replace(['/([a-z])([A-Z])/', '/_/', '/\\\/'], ['$1-$2', '-', DIRECTORY_SEPARATOR], $class_name));

        if (strpos($filename, 'build') !== false) {
            return ELEVATION_THEME_DIR . $filename . '.php';
        }
        return ELEVATION_THEME_DIR . 'lib/' . $filename . '.php';
    }

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}

function INIT()
{
    return Load::instance();
}

INIT();
