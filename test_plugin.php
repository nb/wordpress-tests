<?php

class WP_Test_Hello_Dolly extends WP_UnitTestCase {

	var $plugin_slug = 'hello';
	
	function test_hello_dolly() {
		ob_start();
		hello_dolly();
		$hello_dolly_output = ob_get_clean();
		$this->assertContains( "id='dolly'", $hello_dolly_output );
	}
}