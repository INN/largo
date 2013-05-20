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
if ( !defined( 'MEDLARGE_WIDTH') ) {
	define( 'MEDLARGE_WIDTH', 679 );
}
if ( !defined( 'MEDIUM_WIDTH') ) {
	define( 'MEDIUM_WIDTH', 336 );
}
if ( !defined( 'MEDIA_SMALL_WIDTH') ) {
	define( 'MEDIA_SMALL_WIDTH', 420 );
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
	    add_image_size( '60x60', 60, 60, true ); // small thumbnail
	    add_image_size( 'mediasmall', MEDIA_SMALL_WIDTH, 9999 ); // medium width scaling
	    add_image_size( 'medium', MEDIUM_WIDTH, 9999 ); // medium width scaling
	    add_image_size( 'medlarge', MEDLARGE_WIDTH, 9999 ); // medium width scaling
	    add_image_size( 'large', LARGE_WIDTH, 9999 ); // large width scaling
	    add_image_size( 'full', FULL_WIDTH, 9999 ); // large width scaling
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
		update_option('thumbnail_size_w', 140);
	    update_option('thumbnail_size_h', 140);
	    update_option('thumbnail_crop', 1);
	    update_option('medium_size_w', MEDIUM_WIDTH);
	    update_option('medium_size_h', 9999);
	    update_option('large_size_w', LARGE_WIDTH);
	    update_option('large_size_h', 9999);
		update_option('embed_autourls', 1);
		update_option('embed_size_w', LARGE_WIDTH);
		update_option('embed_size_h', 9999);
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
function attachment_image_link_remove_filter( $content ) {
    $content =
        preg_replace(
            array('{<a(.*?)(wp-att|wp-content\/uploads)[^>]*><img}',
                '{ wp-image-[0-9]*" /></a>}'),
            array('<img','" />'),
            $content
        );
    return $content;
}
add_filter( 'the_content', 'attachment_image_link_remove_filter' );