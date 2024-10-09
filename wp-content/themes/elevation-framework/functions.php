<?php

/*
 * Uses composer to autoload our vendor modules. 
 * Make sure you are using composer update --prefer dist in your terminal first, so these modules are loaded!
 */
//require_once 'vendor/autoload.php';


define('ELEVATION_THEME_NAME', 'elevation-framework');
define('ELEVATION_THEME_VERSION', '0.2.0');
define('ELEVATION_THEME_FILE', __FILE__);
define('ELEVATION_THEME_DIR', __DIR__ . DIRECTORY_SEPARATOR);
define('ELEVATION_THEME_URL', get_template_directory_uri());
define('ELEVATION_THEME_PREFIX', 'elevation-framework');
define('ELEVATION_THEME_DEVELOPMENT_MODE', false);
define('ELEVATION_VERSION', '1.0.0');

require_once 'lib/load.php';

