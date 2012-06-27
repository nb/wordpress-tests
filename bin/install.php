<?php
/**
 * Installs WordPress for the purpose of the unit-tests
 *
 * @todo Reuse the init/load code in init.php
 */
error_reporting( E_ALL & ~E_DEPRECATED & ~E_STRICT );

$config_file_path = $argv[1];

define( 'WP_INSTALLING', true );
require_once $config_file_path;

$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
$_SERVER['HTTP_HOST'] = WP_TESTS_DOMAIN;
$PHP_SELF = $GLOBALS['PHP_SELF'] = $_SERVER['PHP_SELF'] = '/index.php';

require_once ABSPATH . '/wp-settings.php';

require_once ABSPATH . '/wp-admin/includes/upgrade.php';
require_once ABSPATH . '/wp-includes/wp-db.php';

define( 'WP_TESTS_DB_VERSION_FILE', ABSPATH . '.wp-tests-db-version' );

$wpdb->suppress_errors();
$wpdb->hide_errors();
$installed = $wpdb->get_var( "SELECT option_value FROM $wpdb->options WHERE option_name = 'siteurl'" );

if ( $installed && file_exists( WP_TESTS_DB_VERSION_FILE ) ) {
	$install_db_version = file_get_contents( WP_TESTS_DB_VERSION_FILE );
	$db_version = get_option( 'db_version' );
	if ( $db_version == $install_db_version ) {
		return;
	}
}
$wpdb->query( 'SET storage_engine = INNODB;' );
$wpdb->query( 'DROP DATABASE IF EXISTS '.DB_NAME.";" );
$wpdb->query( 'CREATE DATABASE '.DB_NAME.";" );
$wpdb->select( DB_NAME, $wpdb->dbh );

echo "Installing…" . PHP_EOL;
wp_install( WP_TESTS_TITLE, 'admin', WP_TESTS_EMAIL, true, '', 'a' );

if ( defined('WP_ALLOW_MULTISITE') && WP_ALLOW_MULTISITE ) {
	echo "Installing network…" .PHP_EOL;

	define( 'WP_INSTALLING_NETWORK', true );
	//wp_set_wpdb_vars();
	// We need to create references to ms global tables to enable Network.
	foreach ( $wpdb->tables( 'ms_global' ) as $table => $prefixed_table )
		$wpdb->$table = $prefixed_table;
	install_network();
	$result = populate_network(1, WP_TESTS_DOMAIN, WP_TESTS_EMAIL, WP_TESTS_NETWORK_TITLE, ABSPATH, WP_TESTS_SUBDOMAIN_INSTALL);

	system( WP_PHP_BINARY . ' ' . escapeshellarg( dirname( __FILE__ ) . '/ms-install.php' ) . ' ' . escapeshellarg( $config_file_path ) );

}

file_put_contents( WP_TESTS_DB_VERSION_FILE, get_option('db_version') );
