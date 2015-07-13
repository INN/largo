<?php
/**
 * This file acts as a wrapper around APIs that exist within Largo.
 * It should be included in any Largo child themes like so:
 * require_once( get_template_directory() . '/largo-apis.php' );
 *
 * @package Largo
 */

/**
 * @ignore
 */
$includes = array(
	'/inc/metabox-api.php', // library-esque convenience functions for hooking into Largo meta boxen
);

// Perform load
foreach ( $includes as $include ) {
	include_once( get_template_directory() . $include );
}
