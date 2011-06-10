<?php
require_once dirname( __FILE__ ) . '/init.php';

class WP_Test_Test extends WP_UnitTestCase {

	function test_is_email() {
		$this->assertEquals( 'nb@nikolay.bg', is_email( 'nb@nikolay.bg' ) );
		$this->assertFalse( is_email( 'nb@nikolay' ) );
	}
	
	function test_option() {
		$this->assertEquals( false, get_option( 'baba' ) );
		add_option( 'baba', 'dyado' );
		$this->assertEquals( 'dyado', get_option( 'baba' ) );
	}
	
	function test_array_option() {
		$this->assertEquals( false, get_option( 'baba' ) );
		add_option( 'baba', array( 'baba' => 'dyado' ) );
		$this->assertEquals( array( 'baba' => 'dyado' ), get_option( 'baba' ) );
	}	
}
