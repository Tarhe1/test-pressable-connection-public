<?php

namespace ElevationFramework\Lib\Plugins;

class Load
{

    protected static $_instance;

    public function __construct()
    {
        Updater\load::instance();

        require_once ELEVATION_THEME_DIR . '/lib/plugins/activation/tgm.php';

        add_action('tgmpa_register', [$this, 'register_required_plugins']);

        // if (is_plugin_active('all-in-one-seo-pack-pro/all_in_one_seo_pack.php')) {
        //     add_filter('aioseo_post_metabox_priority', [$this, 'aioseo_filter_metabox_priority']);
        // }
    }

    public function register_required_plugins()
    {
        /*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
        $plugins = array(

            // This is an example of how to liblude library/a plugin bundled with a theme.
            array(
                'name'               => 'Advanced Custom Fields PRO', // The plugin name.
                'slug'               => 'advanced-custom-fields-pro', // The plugin slug (typically the folder name).
                'source'             => ELEVATION_THEME_DIR . '/lib/plugins/library/advanced-custom-fields-pro.zip', // The plugin source.
                'required'           => true, // If false, the plugin is only 'recommended' instead of required.
                'version'            => '6.2.7', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
                'force_activation'   => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
                'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
                'external_url'       => '', // If set, overrides default API URL and points to an external URL.
            ),
            array(
                'name'               => 'Gravity Forms', // The plugin name.
                'slug'               => 'gravityforms', // The plugin slug (typically the folder name).
                'source'             => ELEVATION_THEME_DIR . '/lib/plugins/library/gravityforms_2.8.4.3.zip', // The plugin source.
                'required'           => true, // If false, the plugin is only 'recommended' instead of required.
                'version'            => '2.8.4', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
                'force_activation'   => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
                'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
                'external_url'       => '', // If set, overrides default API URL and points to an external URL.
            ),
            array(
                'name'               => 'WPMU DEV Dashboard', // The plugin name.
                'slug'               => 'wpmudev', // The plugin slug (typically the folder name).
                'source'             => ELEVATION_THEME_DIR . '/lib/plugins/library/923912_wpmu-dev-dashboard-4.11.24.zip', // The plugin source.
                'required'           => false, // If false, the plugin is only 'recommended' instead of required.
                'version'            => '4.11.24', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
                'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
                'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            ),
            array(
                'name'               => 'Smush Pro', // The plugin name.
                'slug'               => 'wp-smush-pro', // The plug in slug (typically the folder name).
                'source'             => ELEVATION_THEME_DIR . '/lib/plugins/library/923912_smush-pro-3.15.5.zip', // The plugin source.
                'required'           => true, // If false, the plugin is only 'recommended' instead of required.
                'version'            => '3.15.5', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
                'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
                'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            ),
            array(
                'name'               => 'All in One SEO Pro', // The plugin name.
                'slug'               => 'all-in-one-seo-pack', // The plugin slug (typically the folder name).
                'source'             => ELEVATION_THEME_DIR . '/lib/plugins/library/aioseo-pro-v4.5.7.2.zip', // The plugin source.
                'required'           => false, // If false, the plugin is only 'recommended' instead of required.
                'version'            => '4.5.7.2', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
                'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
                'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            ),
            array(
                'name'               => 'Safe SVG', // The plugin name.
                'slug'               => 'safe-svg', // The plugin slug (typically the folder name).
                'required'           => true, // If false, the plugin is only 'recommended' instead of required.
                'version'            => '2.2.2', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
                'force_activation'   => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            ),
            array(
                'name'               => 'Search Exclude', // The plugin name.
                'slug'               => 'search-exclude', // The plugin slug (typically the folder name).
                'required'           => true, // If false, the plugin is only 'recommended' instead of required.
                'version'            => '2.0.7', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
                'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            ),
            array(
                'name'               => 'Duplicate Page', // The plugin name.
                'slug'               => 'duplicate-page', // The plugin slug (typically the folder name).
                'required'           => false, // If false, the plugin is only 'recommended' instead of required.
                'version'            => '4.5.3', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
                'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            ),
            array(
                'name'               => 'AddToAny Share Buttons', // The plugin name.
                'slug'               => 'add-to-any', // The plugin slug (typically the folder name).
                'required'           => true, // If false, the plugin is only 'recommended' instead of required.
                'version'            => '1.8.9', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
                'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            )
        );

        /*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
        $config = array(
            'id'           => 'elevation',                 // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => '',                      // Default absolute path to bundled plugins.
            'menu'         => 'elevation-install-plugins', // Menu slug.
            'parent_slug' => 'plugins.php', // Parent menu slug.
            'capability' => 'edit_theme_options',
            'dismissable'  => false,                    // If false, a user cannot dismiss the nag message.
            'is_automatic' => true,                   // Automatically activate plugins after installation or not.
            'message'      => '',                      // Message to output right before the plugins table.
            'strings'      => array(
                'page_title'                      => __('Install Required Plugins', 'elevation'),
                'menu_title'                      => __('Required Plugins', 'elevation'),
                /* translators: %s: plugin name. */
                'installing'                      => __('Installing Plugin: %s', 'elevation'),
                /* translators: %s: plugin name. */
                'updating'                        => __('Updating Plugin: %s', 'elevation'),
                'oops'                            => __('Something went wrong with the plugin API.', 'elevation'),
                'notice_can_install_required'     => _n_noop(
                    /* translators: 1: plugin name(s). */
                    'This theme requires the following plugin: %1$s.',
                    'This theme requires the following plugins: %1$s.',
                    'elevation'
                ),
                'notice_can_install_recommended'  => _n_noop(
                    /* translators: 1: plugin name(s). */
                    'This theme recommends the following plugin: %1$s.',
                    'This theme recommends the following plugins: %1$s.',
                    'elevation'
                ),
                'notice_ask_to_update'            => _n_noop(
                    /* translators: 1: plugin name(s). */
                    'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
                    'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
                    'elevation'
                ),
                'notice_ask_to_update_maybe'      => _n_noop(
                    /* translators: 1: plugin name(s). */
                    'There is an update available for: %1$s.',
                    'There are updates available for the following plugins: %1$s.',
                    'elevation'
                ),
                'notice_can_activate_required'    => _n_noop(
                    /* translators: 1: plugin name(s). */
                    'The following required plugin is currently inactive: %1$s.',
                    'The following required plugins are currently inactive: %1$s.',
                    'elevation'
                ),
                'notice_can_activate_recommended' => _n_noop(
                    /* translators: 1: plugin name(s). */
                    'The following recommended plugin is currently inactive: %1$s.',
                    'The following recommended plugins are currently inactive: %1$s.',
                    'elevation'
                ),
                'install_link'                    => _n_noop(
                    'Begin installing plugin',
                    'Begin installing plugins',
                    'elevation'
                ),
                'update_link'                       => _n_noop(
                    'Begin updating plugin',
                    'Begin updating plugins',
                    'elevation'
                ),
                'activate_link'                   => _n_noop(
                    'Begin activating plugin',
                    'Begin activating plugins',
                    'elevation'
                ),
                'return'                          => __('Return to Required Plugins Installer', 'elevation'),
                'plugin_activated'                => __('Plugin activated successfully.', 'elevation'),
                'activated_successfully'          => __('The following plugin was activated successfully:', 'elevation'),
                /* translators: 1: plugin name. */
                'plugin_already_active'           => __('No action taken. Plugin %1$s was already active.', 'elevation'),
                /* translators: 1: plugin name. */
                'plugin_needs_higher_version'     => __('Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'elevation'),
                /* translators: 1: dashboard link. */
                'complete'                        => __('All plugins installed and activated successfully. %1$s', 'elevation'),
                'dismiss'                         => __('Dismiss this notice', 'elevation'),
                'notice_cannot_install_activate'  => __('There are one or more required or recommended plugins to install, update or activate.', 'elevation'),
                'contact_admin'                   => __('Please contact the administrator of this site for help.', 'elevation'),
                'nag_type'                        => 'updated', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
            ),

        );

        tgmpa($plugins, $config);
    }

    public function aioseo_filter_metabox_priority($priority)
    {
        return 'low';
    }

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
