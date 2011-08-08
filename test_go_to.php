<?php
require_once dirname( __FILE__ ) . '/init.php';

class WP_Test_Go_To extends WP_UnitTestCase {
	
	function test_single() {
		$this->go_to( get_permalink( 1 ) );
		$this->assertTrue( is_single(), 'This is not a single post page.' );
		$this->assertTrue( have_posts() );
	}
}
