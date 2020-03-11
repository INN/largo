<?php
/**
 * Deprecated functions
 */

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

/**
 * Deprecated function to check whether a Facebook user/page was followable.
 *
 * This function used to use the Facebook Follow button's HTML markup
 * https://developers.facebook.com/docs/archive/docs/plugins/follow-button/
 * to determine whether a page or user could be followed. The button was
 * deprecated by Facebook on February 5, 2018, and as a result, this function
 * stopped working.
 *
 * There are no plans to provide similar functionality in the future, because
 * the relevant Facebook Graph API calls require Facebook API tokens,
 * and it seems excessive to require theme users to get one just for this check.
 *
 * @param   string  $username a valid Facebook username or page name. They're generally indistinguishable, except pages get to use '-'
 * @return  bool    False
 */
function largo_fb_user_is_followable( $username = '' ) {
	if ( WP_DEBUG || LARGO_DEBUG ) {
		error_log( 'largo_db_user_is_followable is deprecated and should not be used.' );
	}
	return false;
}

/**
 * Former shortcode version of `largo_render_user_list`
 *
 * @param $atts array The attributes of the shortcode.
 * @since 0.4
 * @deprecated 0.7
 * @link https://github.com/INN/staff
 * @link https://github.com/INN/largo/issues/1505
 */
function largo_render_staff_list_shortcode($atts=array()) {
	error_log(var_export( 'function largo_render_staff_list_shortcode is deprecated. Use INN\'s Staff plugin instead', true));
}

/**
 * Formerly, a list of user profiles based on the array of users passed
 *
 * @param $users array The WP_User objects to use in rendering the list.
 * @param $show_users_with_empty_desc bool Whether we should skip users that have no bio/description.
 * @since 0.4
 * @deprecated 0.7
 * @link https://github.com/INN/staff
 * @link https://github.com/INN/largo/issues/1505
 */
function largo_render_user_list($users, $show_users_with_empty_desc=false) {
	error_log(var_export( 'function largo_render_user_list is deprecated. Use INN\'s Staff plugin instead', true));
}
