<?php
class WP_Plugin_UnitTestCase extends WP_UnitTestCase {

	var $plugin_slug;

	function setUp() {
		parent::setUp();
		require_once ABSPATH . '/wp-admin/includes/plugin.php';
		if ( file_exists( WP_PLUGIN_DIR . '/' . $this->plugin_slug . '.php' ) )
			activate_plugin( $this->plugin_slug . '.php' );
		else
			activate_plugin( $this->plugin_slug . '/' . $this->plugin_slug . '.php'  );
	}
}