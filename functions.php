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
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/options-framework/' );
	require_once dirname( __FILE__ ) . '/inc/options-framework/options-framework.php';
}

/**
 * Load up all of the other goodies from the /inc directory
 */

$includes = array(
	'/inc/largo-plugin-init.php',		// a list of recommended plugins
	'/inc/special-functionality.php',	// header cleanup and robots.txt
	'/inc/users.php',			// add custom fields for user profiles
	'/inc/sidebars.php',			// register sidebars
	'/inc/widgets.php',			// register widgets
	'/inc/nav-menus.php',			// register nav menus
	'/inc/taxonomies.php',			// add our custom taxonomies
	'/inc/images.php',			// setup custom image sizes
	'/inc/editor.php',			// add tinymce customizations and shortcodes
	'/inc/post-meta.php',			// add post meta boxes
	'/inc/open-graph.php',			// add open graph, twittercard and google publisher markup to the header
	'/inc/post-tags.php',			// add some custom template tags (mostly used in single posts)
	'/inc/header-footer.php',		// some additional template tags used in the header and footer
	'/inc/related-content.php',		// functions dealing with related content
	'/inc/featured-content.php',		// functions dealing with featured content
	'/inc/enqueue.php'			// enqueue our js and css files
);

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