<?php
require_once dirname( __FILE__ ) . '/init.php';

class WP_Test_Acme extends WP_UnitTestCase {

	var $plugin_slug = 'hello';
	
	function test_hello_dolly() {
		ob_start();
		hello_dolly();
		$hello_dolly_output = ob_get_clean();
		$this->assertContains( "id='dolly'", $hello_dolly_output );
	}
}