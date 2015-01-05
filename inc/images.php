<?php

/**
 * Remove links to attachments
 *
 * @param object the post content
 * @return object post content with image links stripped out
 * @since 1.0
 */
function largo_attachment_image_link_remove_filter( $content ) {
	$content =
    preg_replace(
      array('{<a(.*?)(wp-att|wp-content\/uploads)[^>]*><img}',
        '{ wp-image-[0-9]*" /></a>}'),
      array('<img','" />'),
      $content
    );
	return $content;
}
add_filter( 'the_content', 'largo_attachment_image_link_remove_filter' );

/**
 * Load the picturefill.wp plugin
 */
//require_once(get_template_directory() . '/inc/picturefill/picturefill-wp.php');

if ( ! function_exists( 'largo_home_icon' ) ) {
	function largo_home_icon( $class='', $size = 'home-logo' ) {
		global $wpdb;

		$logo = of_get_option( 'logo_thumbnail_sq' );
		$default = '<i class="icon-home ' . esc_attr( $class ) . '"></i>';

		if ( ! empty( $logo ) ) {
			$cache_key = 'largo_logo_thumbnail_sq_attachment_id';
			if ( false === ( $attachment_id = get_transient( $cache_key ) ) ) {
				$attachment_id = $wpdb->get_var( $wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE guid = %s", $logo) );
				set_transient( $cache_key, $attachment_id );
			}
			if (!empty($attachment_id))
				echo wp_get_attachment_image( $attachment_id, $size );
			else {
				if (preg_match('/^http(s)?\:\/\//', $logo))
					echo '<img width="50" height="50" src="' . $logo . '" class="attachment-home-logo" alt="logo">';
				else
					echo $default;
			}
		} else {
			echo $default;
		}
	}
}

/**
 * Clear the home icon cache when options are updated
 */
function largo_clear_home_icon_cache($option) {
	if (get_option('stylesheet') === $option)
		delete_transient('largo_logo_thumbnail_sq_attachment_id');
}
add_action('update_option', 'largo_clear_home_icon_cache');
