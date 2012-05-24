<?php
/**
 * Bootstrap with early WordPress hooks, which allows for overriding 
 * some settings like activated plugins and theme without modifying 
 * database. 
 *
 * To run PHPUnit with this bootstrap, please use this command:
 *
 *    $ phpunit --bootstrap bootstrap-with-hooks.php all
 */

$GLOBALS['hooks'] = array();

// Activate the Hello Dolly plugin
$GLOBALS['hooks'][] = function() {
	add_filter('option_active_plugins', function($plugins){
		$plugins[] = 'hello.php';
		return $plugins;
	});
};

// Activate the TwentyEleven theme.
$GLOBALS['hooks'][] = function() {
	add_filter('option_template', function($stylesheet){
		return 'twentyeleven';
	});

	add_filter('option_stylesheet', function($stylesheet){
		return 'twentyeleven';
	});
};

/**
 * Initiate WordPress-tests
 */

$path = 'init.php';

if( file_exists( $path ) ) {
    require_once $path;
} else {
    exit( "Couldn't find path to wordpress-tests/init.php\n" );
}