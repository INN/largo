<?php

/**
 * Define medium and large image sizes
 *
 * @since 1.0
 */
if ( !defined( 'FULL_WIDTH') ) {
	define( 'FULL_WIDTH', 1170 );
}
if ( !defined( 'LARGE_WIDTH') ) {
	define( 'LARGE_WIDTH', 771 );
}
if ( !defined( 'MEDIUM_WIDTH') ) {
	define( 'MEDIUM_WIDTH', 336 );
}

/**
 * Create custom image sizes used by the largo parent theme
 *
 * @since 1.0
 */
if ( ! function_exists( 'largo_create_image_sizes' ) ) {
	function largo_create_image_sizes() {
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 140, 140, true ); // thumbnail
		add_image_size( 'home-logo', 50, 50, true ); // small thumbnail
		add_image_size( '60x60', 60, 60, true ); // small thumbnail
		add_image_size( 'medium', MEDIUM_WIDTH, 9999 ); // medium width scaling
		add_image_size( 'large', LARGE_WIDTH, 9999 ); // large width scaling
		add_image_size( 'full', FULL_WIDTH, 9999 ); // large width scaling
		add_image_size( 'third-full', FULL_WIDTH / 3, 500, true ); // large width scaling
		add_image_size( 'two-third-full', FULL_WIDTH / 3 * 2, 500, true ); // large width scaling
	}
}
add_action( 'after_setup_theme', 'largo_create_image_sizes' );

/**
 * Replace all the defaults in settings > media with our preferred settings
 *
 * @since 1.0
 */
if ( ! function_exists( 'largo_set_media_options' ) ) {
	function largo_set_media_options() {

		add_filter( 'pre_option_thumbnail_size_w', function(){
			return 140;
		});
		add_filter( 'pre_option_thumbnail_size_h', function(){
			return 140;
		});
		add_filter( 'pre_option_thumbnail_crop', '__return_true' );
		add_filter( 'pre_option_medium_size_w', function(){
			return MEDIUM_WIDTH;
		});
		add_filter( 'pre_option_medium_size_h', function(){
			return 9999;
		});
		add_filter( 'pre_option_large_size_w', function(){
			return LARGE_WIDTH;
		});
		add_filter( 'pre_option_large_size_h', function(){
			return 9999;
		});
		add_filter( 'pre_option_embed_autourls', '__return_true' );
		add_filter( 'pre_option_embed_size_w', function(){
			return LARGE_WIDTH;
		});
		add_filter( 'pre_option_embed_size_h', function(){
			return 9999;
		});

	}
}
add_action( 'after_setup_theme', 'largo_set_media_options' );

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

		if ( ! empty( $logo ) ) {
			$logo = preg_replace( '#-\d+x\d+(\.[^.]+)$#', '\1', $logo );

			$cache_key = 'largo_logo_thumbnail_sq_attachment_id';
			if ( false === ( $attachment_id = get_transient( $cache_key ) ) ) {
				$attachment_id = $wpdb->get_var( $wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE guid = %s", $logo) );
				set_transient( $cache_key, $attachment_id );
			}
			echo wp_get_attachment_image( $attachment_id, $size );
		} else {
			echo '<i class="icon-home ' . esc_attr( $class ) . '"></i>';
		}
	}
}

/**
 * Clear the home icon cache when options are updated
 */
function largo_clear_home_icon_cache( $option ) {

	if ( 'largo' === $option ) {
		delete_transient( 'largo_logo_thumbnail_sq_attachment_id' );
	}

}
add_action( 'update_option', 'largo_clear_home_icon_cache' );
