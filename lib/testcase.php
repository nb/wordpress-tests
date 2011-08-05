<?php
class WP_UnitTestCase extends PHPUnit_Framework_TestCase {

    var $url = 'http://example.org/';

	function setUp() {
		global $wpdb;
		$wpdb->suppress_errors = false;
		$wpdb->show_errors = true;
		$wpdb->db_connect();
		ini_set('display_errors', 1 );
		$this->clean_up_global_scope();
		$this->start_transaction();
		add_filter( 'gp_get_option_uri', array( $this, 'url_filter') );
    }

	function url_filter( $url ) {
		return $this->url;
	}

	function tearDown() {
		global $wpdb;
		$wpdb->query( 'ROLLBACK' );
		remove_filter( 'gp_get_option_uri', array( $this, 'url_filter') );
	}

	function clean_up_global_scope() {
		wp_cache_flush();
		$_GET = array();
		$_POST = array();
	}
	
	function start_transaction() {
		global $wpdb;
		$wpdb->query( 'SET autocommit = 0;' );
		$wpdb->query( 'SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE;' );
		$wpdb->query( 'START TRANSACTION;' );		
	}

	function force_innodb( $schema ) {
		foreach( $schema as &$sql ) {
			$sql = str_replace( ');', ') TYPE=InnoDB;', $sql );
		}
		return $schema;
	}
	
	function assertWPError( $actual, $message = '' ) {
		$this->assertTrue( is_wp_error( $actual ), $message );
	}
	
	function assertEqualFields( $object, $fields ) {
		foreach( $fields as $field_name => $field_value ) {
			if ( $object->$field_name != $field_value ) {
				$this->fail();
			}
		}
	}
	
	function assertDiscardWhitespace( $expected, $actual ) {
		$this->assertEquals( preg_replace( '/\s*/', '', $expected ), preg_replace( '/\s*/', '', $actual ) );
	}

	function checkAtLeastPHPVersion( $version ) {
		if ( version_compare( PHP_VERSION, $version, '<' ) ) {
			$this->markTestSkipped();
		}
	}
}