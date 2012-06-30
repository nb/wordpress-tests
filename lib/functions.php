<?php
/**
 * Generate a hash to be used when comparing installed version against
 * codebase and current configuration
 * @return string $hash sha1 hash
 **/
function test_version_check_hash() {
	$hash = '';
	$db_version = get_option( 'db_version' );
	if ( defined('WP_ALLOW_MULTISITE') && WP_ALLOW_MULTISITE ) {
		$version = $db_version;
		if( defined( 'WP_TESTS_BLOGS' ) ) {
			$version .= WP_TESTS_BLOGS;
		}
		if( defined( 'WP_TESTS_SUBDOMAIN_INSTALL' ) ) {
			$version .= WP_TESTS_SUBDOMAIN_INSTALL;
		}
		if( defined( 'WP_TESTS_DOMAIN' ) ) {
			$version .= WP_TESTS_DOMAIN;
		}

	} else {
		$version = $db_version;
	}

	return sha1($version);
}

// For adding hooks before loading WP
function tests_add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
	global $wp_filter, $merged_filters;

	$idx = _test_filter_build_unique_id($tag, $function_to_add, $priority);
	$wp_filter[$tag][$priority][$idx] = array('function' => $function_to_add, 'accepted_args' => $accepted_args);
	unset( $merged_filters[ $tag ] );
	return true;
}

function _test_filter_build_unique_id($tag, $function, $priority) {
	global $wp_filter;
	static $filter_id_count = 0;

	if ( is_string($function) )
		return $function;

	if ( is_object($function) ) {
		// Closures are currently implemented as objects
		$function = array( $function, '' );
	} else {
		$function = (array) $function;
	}

	if (is_object($function[0]) ) {
		return spl_object_hash($function[0]) . $function[1];
	} else if ( is_string($function[0]) ) {
		// Static Calling
		return $function[0].$function[1];
	}
}

