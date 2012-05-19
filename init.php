<?php
/**
 * Installs WordPress for running the tests and loads WordPress and the test libraries
 */

error_reporting( E_ALL & ~E_DEPRECATED & ~E_STRICT );

require_once 'PHPUnit/Autoload.php';

$config_file_path = dirname( __FILE__ ) . '/unittests-config.php';

/*
 * Globalize some WordPress variables, because PHPUnit loads this file inside a function
 * See: https://github.com/sebastianbergmann/phpunit/issues/325
 *
 * These are not needed for WordPress 3.3+, only for older versions
*/
global $table_prefix, $wp_embed, $wp_locale, $_wp_deprecated_widgets_callbacks, $wp_widget_factory;

// These are still needed
global $wpdb, $current_site, $current_blog, $wp_rewrite, $shortcode_tags, $wp;

require_once $config_file_path;

$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
$_SERVER['HTTP_HOST'] = WP_TESTS_DOMAIN;
$PHP_SELF = $GLOBALS['PHP_SELF'] = $_SERVER['PHP_SELF'] = '/index.php';

system( 'php '.escapeshellarg( dirname( __FILE__ ) . '/bin/install.php' ) . ' ' . escapeshellarg( $config_file_path ) );

// Stop most of WordPress from being loaded.
define('SHORTINIT', true);

// Load the basics part of WordPress.
require_once ABSPATH . '/wp-settings.php';

// Load early WordPress hooks defined in the bootstrap file.
if(isset($GLOBALS['hooks'])) {
	foreach ($GLOBALS['hooks'] as $function) {
		$function();
	}

	unset($GLOBALS['hooks']);
}

// Load the rest of wp-settings.php, start from where we left off.
$wp_settings_content = file_get_contents(ABSPATH.'/wp-settings.php');
$offset = strpos($wp_settings_content, '// Load the l18n library.');
eval(substr($wp_settings_content, $offset));
unset($wp_settings_content, $offset);

require dirname( __FILE__ ) . '/lib/testcase.php';
require dirname( __FILE__ ) . '/lib/exceptions.php';
