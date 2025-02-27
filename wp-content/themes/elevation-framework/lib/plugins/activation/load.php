<?php

namespace  ElevationFramework\Lib\Plugins\Activation;

class Load
{

    protected static $_instance;

    public function __construct()
    {

        if (!class_exists('TGM_Plugin_Activation')) {
        }

        if (!function_exists('load_tgm_plugin_activation')) {
            /**
             * Ensure only one instance of the class is ever invoked.
             *
             * @since 2.5.0
             */
            function load_tgm_plugin_activation()
            {
                $GLOBALS['tgmpa'] = TGM_Plugin_Activation::get_instance();
            }
        }

        if (did_action('plugins_loaded')) {
            load_tgm_plugin_activation();
        } else {
            add_action('plugins_loaded', 'load_tgm_plugin_activation');
        }

        if (!function_exists('tgmpa')) {
            /**
             * Helper function to register a collection of required plugins.
             *
             * @since 2.0.0
             * @api
             *
             * @param array $plugins An array of plugin arrays.
             * @param array $config  Optional. An array of configuration values.
             */
            function tgmpa($plugins, $config = array())
            {
                $instance = call_user_func(array(get_class($GLOBALS['tgmpa']), 'get_instance'));

                foreach ($plugins as $plugin) {
                    call_user_func(array($instance, 'register'), $plugin);
                }

                if (!empty($config) && is_array($config)) {
                    // Send out notices for deprecated arguments passed.
                    if (isset($config['notices'])) {
                        _deprecated_argument(__FUNCTION__, '2.2.0', 'The `notices` config parameter was renamed to `has_notices` in TGMPA 2.2.0. Please adjust your configuration.');
                        if (!isset($config['has_notices'])) {
                            $config['has_notices'] = $config['notices'];
                        }
                    }

                    if (isset($config['parent_menu_slug'])) {
                        _deprecated_argument(__FUNCTION__, '2.4.0', 'The `parent_menu_slug` config parameter was removed in TGMPA 2.4.0. In TGMPA 2.5.0 an alternative was (re-)introduced. Please adjust your configuration. For more information visit the website: http://tgmpluginactivation.com/configuration/#h-configuration-options.');
                    }
                    if (isset($config['parent_url_slug'])) {
                        _deprecated_argument(__FUNCTION__, '2.4.0', 'The `parent_url_slug` config parameter was removed in TGMPA 2.4.0. In TGMPA 2.5.0 an alternative was (re-)introduced. Please adjust your configuration. For more information visit the website: http://tgmpluginactivation.com/configuration/#h-configuration-options.');
                    }

                    call_user_func(array($instance, 'config'), $config);
                }
            }
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
