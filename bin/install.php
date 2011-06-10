<?php
/**
 * Installs WordPress for the purpose of the unit-tests
 * 
 * @todo Reuse the init/load code in init.php
 */
error_reporting( E_ALL & ~E_DEPRECATED & ~E_STRICT );

$config_file_path = $argv[1];

define( 'WP_INSTALLING', true );

$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
$_SERVER['HTTP_HOST'] = 'example.org';
$PHP_SELF = $GLOBALS['PHP_SELF'] = $_SERVER['PHP_SELF'] = '/index.php';

require_once $config_file_path;
require_once ABSPATH . '/wp-settings.php';

require_once ABSPATH . '/wp-admin/includes/upgrade.php';
require_once ABSPATH . '/wp-includes/wp-db.php';

$wpdb->query( 'DROP DATABASE IF EXISTS '.DB_NAME.";" );
$wpdb->query( 'CREATE DATABASE '.DB_NAME.";" );
$wpdb->select( DB_NAME, $wpdb->dbh );

add_filter( 'dbdelta_create_queries', function($queries) {
	foreach( $queries as &$query ) {
		$query .= ' ENGINE=InnoDB';
	}
	return $queries;
});

wp_install( "Baba's blog", 'admin', 'admin@baba.net', true, '', 'a' );