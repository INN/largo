<?php

/**
 * Wrapper for wp_nav_menu() that previously cached nav menus. Removed caching mechanism and
 * changed function name to largo_nav_menu in 0.4. See largo_nav_menu.
 *
 * @since 0.3
 * @deprecated 0.4
 * @deprecated Use largo_nav_menu()
 * @see largo_nav_menu()
 *
 * @param array $args
 */
function largo_cached_nav_menu( $args = array(), $prime_cache = false ) {
	_deprecated_function(__FUNCTION__, '0.4', 'largo_nav_menu');
	return largo_nav_menu($args);
}

/**
 * Returns the featured image for a post; deprecated in 0.5.1
 * to be used as the hero image with caption and credit (if available)
 *
 * @since 0.4
 * @deprecated 0.5.1
 */
if ( ! function_exists( 'largo_hero_with_caption' ) ) {
	function largo_hero_with_caption( $post_id ) {
		largo_featured_image_hero($post_id);
	}
}
