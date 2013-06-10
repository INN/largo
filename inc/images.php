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
 * Bypass WordPress's native editor image insert and use picturefill instead
 *
 * @since 1.0
 */
function largo_send_image_to_editor($html, $post_id, $caption, $title, $align, $url, $size, $alt) {

	// Only use picturefill for large images
	if ( 'large' != $size && 'full' != $size ) {
		return $html;
	}

	// Check for [caption ...] in the HTML. This is put in for compatability with
	// Navis-Media-Credit plugin's [caption][/caption] wrapper.
	$matched = preg_match('#\[caption[^\]]*\]#', $html, $matches);

	$shortcode = '';

	if ($matched > 0 && $matches[0] != '') {
		$matches[0] = preg_replace( '/width="([0-9]+)"/', 'width="x"', $matches[0]);
		$shortcode .= $matches[0];
	}

	$shortcode .= '[picturefill id="' . $post_id . '"';
	if ($title){
		$shortcode .= ' title="' . esc_attr($title) . '"';
	}
	if ($alt){
		$shortcode .= ' alttext="' . esc_html($alt) . '"';
	}
	$shortcode .= $size . ' ]';

	if ($matched > 0 && $matches[0] != '') {
		$shortcode .= '[/caption]';
	}

	return $shortcode;
}
add_filter('image_send_to_editor', 'largo_send_image_to_editor', 20, 8);


function largo_picturefill_shortcode($attributes, $content = null) {

	$output = '';

	extract(shortcode_atts(array(
		'id' => null,
		'alttext' => null,
		'title' => null,
		'align' => null,
	), $attributes ) );

	if ($attributes['id']) {

		$image_large = wp_get_attachment_image_src( $attributes['id'], 'large');
		$image_medlarge = wp_get_attachment_image_src( $attributes['id'], 'medlarge');
		$image_mediasmall = wp_get_attachment_image_src( $attributes['id'], 'mediasmall');
		$image_medium = wp_get_attachment_image_src( $attributes['id'], 'medium');

		// Open tag & output for modern browsers
		$output .= '<div data-picture';
		if ($attributes['alttext'] != null) {
			$output .= ' data-alt="' . esc_attr($attributes['alttext']) . '"';
		}
		$output .= '>' . PHP_EOL;

		// Various image sizes
		if (isset($image_medium[0])) {
			$output .= '<div data-src="' . $image_medium[0] . '"></div>' . PHP_EOL;
		}
		if (isset($image_mediasmall[0])) {
			$output .= '<div data-src="' . $image_mediasmall[0] . '" data-media="(min-width: 360px)"></div>' . PHP_EOL;
		}
		if (isset($image_medlarge[0])) {
			$output .= '<div data-src="' . $image_medlarge[0] . '" data-media="(min-width: 480px)"></div>' . PHP_EOL;
		}
		if (isset($image_large[0])) {
			$output .= '<div data-src="' . $image_large[0] . '" data-media="(min-width: 980px)"></div>' . PHP_EOL;
		}

		// Set output for older IE and browsers with no JS
		$output .= '<!--[if (lt IE 9) & (!IEMobile)]><div data-src="' . $image_large[0] . '"></div><![endif]-->';
		$output .= '<noscript><img src="' . $image_large[0] . '"';
		if ($attributes['alttext'] != null) {
			$output .= ' alt="' . esc_attr($attributes['alttext']) . '"';
		}
		$output .= '></noscript>' . PHP_EOL;

		// Close tag
		$output .= '</div>' . PHP_EOL;
	}
	return $output;
}
add_shortcode( 'picturefill', 'largo_picturefill_shortcode' );

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