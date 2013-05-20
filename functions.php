<?php
/**
 * Largo functions and definitions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'eleven_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 */

/**
 * By default we'll assume the site is not for an INN member
 * set INN_MEMBER to TRUE to show an INN logo in the header
 * and a widget of INN member stories in the homepage sidebar
 */
if ( ! defined( 'INN_MEMBER' ) )
	define( 'INN_MEMBER', FALSE );

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 771;

// load the options framework (used for our theme options pages)
if ( ! function_exists( 'optionsframework_init' ) ) {
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/lib/options-framework/' );
	require_once dirname( __FILE__ ) . '/lib/options-framework/options-framework.php';
}

/**
 * Load up all of the other goodies from the /inc directory
 */
$includes = array(
	'/inc/largo-plugin-init.php',			// a list of recommended plugins
	'/inc/dashboard.php',							// custom dashboard widgets
	'/inc/robots.php',								// default robots.txt config
	'/inc/custom-feeds.php',					// create custom RSS feeds
	'/inc/users.php',									// add custom fields for user profiles
	'/inc/sidebars.php',							// register sidebars
	'/inc/widgets.php',								// register widgets
	'/inc/nav-menus.php',							// register nav menus
	'/inc/taxonomies.php',						// add our custom taxonomies
	'/inc/images.php',								// setup custom image sizes
	'/inc/editor.php',								// add tinymce customizations and shortcodes
	'/inc/post-meta.php',							// add post meta boxes
	'/inc/open-graph.php',						// add open graph, twittercard and google publisher markup to the header
	'/inc/post-tags.php',							// add some custom template tags (mostly used in single posts)
	'/inc/header-footer.php',					// some additional template tags used in the header and footer
	'/inc/related-content.php',				// functions dealing with related content
	'/inc/featured-content.php',			// functions dealing with featured content
	'/inc/enqueue.php',								// enqueue our js and css files
	'/inc/post-templates.php',				// single post templates
	'/inc/post-meta.php',							// add post meta boxes
	'/inc/ad-codes.php',							// register ad codes
	'/inc/custom-less-variables.php'	// add UI to alter variables.less
	'/inc/feed-input/feed-input.php', 				// Pull in posts via RSS or Atom feeds
	'/inc/wp-taxonomy-landing/taxonomy-landing.php'	// adds taxonomy landing plugin
);

// Perform load
foreach ( $includes as $include ) {
	require_once( get_template_directory() . $include );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override largo_setup() in a child theme, add your own largo_setup() to your child theme's
 * functions.php file.
 */
if ( ! function_exists( 'largo_setup' ) ) {

	function largo_setup() {

		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style('/css/editor-style.css');

		// Add default posts and comments RSS feed links to <head>.
		add_theme_support( 'automatic-feed-links' );

		// Add support for localization (this is a work in progress)
		load_theme_textdomain('largo', get_template_directory() . '/lang');

	}
}
add_action( 'after_setup_theme', 'largo_setup' );



/**
 * Bypass WordPress's native editor image insert
 */
function largo_send_image_to_editor($html, $post_id, $caption, $title, $align, $url, $size, $alt) {

	// NOTE: This functionality also bypasses the Navis-Media-Credit plugin's [caption][/caption]
	// wrapper.

	$shortcode = '';

	$shortcode .= '[picturefill id="' . $post_id . '"';
	if ($title){
		$shortcode .= ' title="' . esc_attr($title) . '"';
	}
	if ($alt){
		$shortcode .= ' alttext="' . esc_html($alt) . '"';
	}
	$shortcode .= ' ]';

	return $shortcode;
}
add_filter('image_send_to_editor', 'largo_send_image_to_editor', 1, 8);


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

