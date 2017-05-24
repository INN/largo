<?php

/**
 * Remove links to attachments
 *
 * @param object the post content
 * @return object post content with image links stripped out
 * @since 0.1
 */
function largo_attachment_image_link_remove_filter( $content ) {
	$content =
    preg_replace(
      array('{<a(.*?)(wp-att|wp-content\/uploads|files)[^>]*><img}',
        '{ wp-image-[0-9]*" /></a>}'),
      array('<img','" />'),
      $content
    );
	return $content;
}
add_filter( 'the_content', 'largo_attachment_image_link_remove_filter' );

if ( ! function_exists( 'largo_home_icon' ) ) {
	/**
	 * Get the home icon for the sticky nav
	 *
	 * @param (string) $class any additional classes you would like to add to icon when returned
	 * @param (string) $size the size of the logo to return
	 * @return (string) $result markup for the sticky nav home icon (logo if available, otherwise just an icon)
	 *
	 * @since 0.4
	 */
	function largo_home_icon( $class = '', $size = '60x60' ) {
		global $wpdb;

		$logo = of_get_option( 'sticky_header_logo' );
		$default = '<i class="icon-home ' . esc_attr( $class ) . '"></i>';

		if ( ! empty( $logo ) ) {
			if ( preg_match( '/^http(s)?\:\/\//', $logo ) ) {
				echo '<img src="' . $logo . '" class="attachment-home-logo" alt="logo">';
			} else {
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

/**
 * Similar to `media_sideload_image` except that it simply returns the attachment's ID on success
 *
 * @param (string) $file the url of the image to download and attach to the post
 * @param (integer) $post_id the post ID to attach the image to
 * @param (string) $desc an optional description for the image
 *
 * @since 0.5.2
 */
function largo_media_sideload_image($file, $post_id, $desc=null) {
	if (!empty($file)) {
		include_once ABSPATH . 'wp-admin/includes/image.php';
		include_once ABSPATH . 'wp-admin/includes/file.php';
		include_once ABSPATH . 'wp-admin/includes/media.php';

		// Set variables for storage, fix file filename for query strings.
		preg_match('/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches);
		$file_array = array();
		$file_array['name'] = basename($matches[0]);
		// Download file to temp location.
		$file_array['tmp_name'] = download_url($file);
		// If error storing temporarily, return the error.
		if (is_wp_error($file_array['tmp_name'])) {
			return $file_array['tmp_name'];
		}
		// Do the validation and storage stuff.
		$id = media_handle_sideload($file_array, $post_id, $desc);
		// If error storing permanently, unlink.
		if (is_wp_error($id)) {
			@unlink($file_array['tmp_name']);
		}
		return $id;
	}
}

/**
 * Set the default image link to 'none'
 *
 * This is so that we can eventually do away with largo_attachment_image_link_remove_filter
 *
 * @since 0.5.4
 * @see largo_attachment_image_link_remove_filter
 */
if ( ! function_exists( 'largo_image_default_link_type' ) ) {
	function largo_image_default_link_type() {
		update_option('image_default_link_type','none');
	}
}
add_action('admin_init', 'largo_image_default_link_type');
