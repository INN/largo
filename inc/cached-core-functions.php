<?php

/**
 * Wrapper function around wp_nav_menu() that will cache the wp_nav_menu for all tag/category
 * pages used in the nav menus
 */
function largo_cached_nav_menu( $args = array(), $prime_cache = false ) {
	global $wp_query;

	$queried_object_id = empty( $wp_query->queried_object_id ) ? 0 : (int) $wp_query->queried_object_id;

	$last_edit = get_option( 'nav_menu_last_edit', 0 );

	$nav_menu_key = 'nav-menu-' . md5( serialize( $args ) . '-' . $queried_object_id . '-' . $last_edit );
	$my_args = wp_parse_args( $args );
	$my_args = apply_filters( 'wp_nav_menu_args', $my_args );
	$my_args = (object) $my_args;

	if ( ( isset( $my_args->echo ) && true === $my_args->echo ) || !isset( $my_args->echo ) ) {
		$echo = true;
	} else {
		$echo = false;
	}

	if ( true === $prime_cache || false === ( $nav_menu = get_transient( $nav_menu_key ) ) ) {
		if ( false === $echo ) {
			$nav_menu = wp_nav_menu( $args );
		} else {
			ob_start();
			wp_nav_menu( $args );
			$nav_menu = ob_get_clean();
		}

		set_transient( $nav_menu_key, $nav_menu, MINUTE_IN_SECONDS * 15 );
	}
	if ( true === $echo ) {
		echo $nav_menu;
	} else {
		return $nav_menu;
	}
}

/**
 * Tracking when any nav menus were last edited
 * makes cache purging much easier
 */
function _largo_action_wp_update_nav_menu() {
	update_option( 'nav_menu_last_edit', time() );
}
add_action( 'wp_update_nav_menu', '_largo_action_wp_update_nav_menu' );
