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
 * @package Largo
 */

/**
 * By default we'll assume the site is not hosted by INN.
 *
 * There should be no reason to set this. It is defined to
 * modify the default value of 'INN_MEMBER' below to true for
 * INN hosted sites.
 */
if ( ! defined( 'INN_HOSTED' ) )
	define( 'INN_HOSTED', FALSE );

/**
 * By default we'll assume the site is not for an INN member.
 *
 * Set INN_MEMBER to TRUE to show an INN logo in the footer
 * and a widget of INN member stories in the homepage sidebar
 */
if ( ! defined( 'INN_MEMBER' ) )
	define( 'INN_MEMBER', FALSE || INN_HOSTED );

/**
 * LARGO_DEBUG defines whether or not to use minified assets
 *
 * Largo by default uses minified CSS and JavaScript files.
 * set LARGO_DEBUG to TRUE to use unminified JavaScript files
 * and unminified CSS files with sourcemaps to our LESS files.
 *
 * @since 0.5
 */
if ( ! defined( 'LARGO_DEBUG' ) )
	define( 'LARGO_DEBUG', FALSE );
/**
 * Image size constants, almost 100% that you won't need to change these
 */
if ( ! defined( 'FULL_WIDTH' ) ) {
	define( 'FULL_WIDTH', 1170 );
}
if ( ! defined( 'LARGE_WIDTH' ) ) {
	define( 'LARGE_WIDTH', 771 );
}
if ( ! defined( 'MEDIUM_WIDTH' ) ) {
	define( 'MEDIUM_WIDTH', 336 );
}
if ( ! defined( 'FULL_HEIGHT' ) ) {
	define( 'FULL_HEIGHT', 9999 );
}
if ( ! defined( 'LARGE_HEIGHT' ) ) {
	define( 'LARGE_HEIGHT', 9999 );
}
if ( ! defined( 'MEDIUM_HEIGHT' ) ) {
	define( 'MEDIUM_HEIGHT', 9999 );
}
if ( ! isset( $content_width ) )
	/**
	 * Set the content width based on the theme's design and stylesheet.
	 *
	 * @ignore
	 */
	$content_width = 771;

if ( ! isset( $largo ) )
	/*
	 * Set the global $largo var
	 *
	 * @ignore
	 */
	$largo = array();

/*
 * load the options framework (used for our theme options pages)
 *
 * @ignore
 */
if ( ! function_exists( 'optionsframework_init' ) ) {
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/lib/options-framework/' );
	require_once dirname( __FILE__ ) . '/lib/options-framework/options-framework.php';
}

/**
 * A class to represent the one true Largo theme instance
 */
class Largo {

	private static $instance;

	public static function get_instance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new Largo;
			self::$instance->load();
		}

		return self::$instance;
	}

	/**
	 * Load the theme
	 */
	private function load() {

		$this->require_files();

		$this->register_nav_menus();
		$this->register_media_sizes();
		$this->template_constants();

		$this->customizer = Largo_Customizer::get_instance();

	}

	/**
	 * Load required files
	 */
	private function require_files() {

		$includes = array(
			'/largo-apis.php',
			'/inc/ajax-functions.php',
			'/inc/helpers.php',
			'/inc/plugin-init.php',
			'/inc/dashboard.php',
			'/inc/custom-feeds.php',
			'/inc/users.php',
			'/inc/term-meta.php',
			'/inc/sidebars.php',
			'/inc/customizer/customizer.php',
			'/inc/widgets.php',
			'/inc/nav-menus.php',
			'/inc/taxonomies.php',
			'/inc/term-icons.php',
			'/inc/term-sidebars.php',
			'/inc/images.php',
			'/inc/editor.php',
			'/inc/post-metaboxes.php',
			'/inc/open-graph.php',
			'/inc/verify.php',
			'/inc/post-tags.php',
			'/inc/header-footer.php',
			'/inc/related-content.php',
			'/inc/featured-content.php',
			'/inc/enqueue.php',
			'/inc/post-templates.php',
			'/inc/home-templates.php',
			'/inc/update.php',
			'/inc/avatars.php',
			'/inc/featured-media.php',
			'/inc/deprecated.php'
		);

		if ( $this->is_less_enabled() ) {
			$includes[] = '/inc/custom-less-variables.php';
		}

		if ( $this->is_plugin_active( 'ad-code-manager' ) ) {
			$includes[] = '/inc/ad-codes.php';
		}

		foreach ( $includes as $include ) {
			require_once( get_template_directory() . $include );
		}

		// If the plugin is already active, don't cause fatals
		if ( ! class_exists( 'Navis_Media_Credit' ) ) {
			require_once dirname( __FILE__ ) . '/lib/navis-media-credit/navis-media-credit.php';
		}

		if ( ! class_exists( 'Navis_Slideshows' ) ) {
			require_once dirname( __FILE__ ) . '/lib/navis-slideshows/navis-slideshows.php';
		}

		if ( ! function_exists( 'clean_contact_func' ) ) {
			require_once dirname( __FILE__ ) . '/lib/clean-contact/clean_contact.php';
		}

	}

	/**
	 * Register the nav menus for the theme
	 */
	private function register_nav_menus() {

		$menus = array(
			'global-nav' => __( 'Global Navigation', 'largo' ),
			'main-nav' => __( 'Main Navigation', 'largo' ),
			'dont-miss' => __( 'Don\'t Miss', 'largo' ),
			'footer' => __( 'Footer Navigation', 'largo' ),
			'footer-bottom' => __( 'Footer Bottom', 'largo' )
		);
		register_nav_menus( $menus );

		// Avoid database writes on the frontend
		if ( ! is_admin() ) {
			return;
		}

		//Try to automatically link menus to each of the locations.
		foreach ( $menus as $location => $label ) {
			// if a location isn't wired up...
			if ( ! has_nav_menu( $location ) ) {

				// get or create the nav menu
				$nav_menu = wp_get_nav_menu_object( $label );
				if ( ! $nav_menu ) {
					$new_menu_id = wp_create_nav_menu( $label );
					$nav_menu = wp_get_nav_menu_object( $new_menu_id );
				}

				// wire it up to the location
				$locations = get_theme_mod( 'nav_menu_locations' );
				$locations[ $location ] = $nav_menu->term_id;
				set_theme_mod( 'nav_menu_locations', $locations );
			}
		}

	}

	/**
	 * Register image and media sizes associated with the theme
	 */
	private function register_media_sizes() {

		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 140, 140, true ); // thumbnail
		add_image_size( '60x60', 60, 60, true ); // small thumbnail
		add_image_size( 'medium', MEDIUM_WIDTH, MEDIUM_HEIGHT ); // medium width scaling
		add_image_size( 'large', LARGE_WIDTH, LARGE_HEIGHT ); // large width scaling
		add_image_size( 'full', FULL_WIDTH, FULL_HEIGHT ); // full width scaling
		add_image_size( 'third-full', FULL_WIDTH / 3, 500, true ); // large width scaling
		add_image_size( 'two-third-full', FULL_WIDTH / 3 * 2, 500, true ); // large width scaling
		add_image_size( 'rect_thumb', 800, 600, true ); // used for cat/tax archive pages

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

	/**
	 * Template display constants, you can override these in your child theme's
	 * functions.php by doing something like:
	 * define( 'SHOW_GLOBAL_NAV', FALSE );
	 */
	private function template_constants() {
		/* Navigation */
		if ( ! defined( 'SHOW_GLOBAL_NAV' ) ) {
			define( 'SHOW_GLOBAL_NAV', TRUE );
		}
		if ( ! defined( 'SHOW_STICKY_NAV' ) ) {
			if ( of_get_option( 'show_sticky_nav' ) ) {
				define( 'SHOW_STICKY_NAV', TRUE );
			} else {
				define( 'SHOW_STICKY_NAV', FALSE );
			}
		}
		if ( ! defined( 'SHOW_MAIN_NAV' ) ) {
			define( 'SHOW_MAIN_NAV', TRUE );
		}
		if ( ! defined( 'SHOW_SECONDARY_NAV' ) ) {
			if ( of_get_option( 'show_dont_miss_menu' ) ) {
				define( 'SHOW_SECONDARY_NAV', TRUE );
			} else {
				define( 'SHOW_SECONDARY_NAV', FALSE );
			}
		}

		/* Category */
		if ( ! defined( 'SHOW_CATEGORY_RELATED_TOPICS' ) ) {
			define( 'SHOW_CATEGORY_RELATED_TOPICS', TRUE );
		}
	}

	/**
	 * Is the LESS feature enabled?
	 */
	public function is_less_enabled() {
		return (bool) of_get_option( 'less_enabled' );
	}

	/**
	 * Is a given plugin active?
	 *
	 * @param string $plugin_slug
	 * @return bool
	 */
	public function is_plugin_active( $plugin_slug ) {

		switch ( $plugin_slug ) {
			case 'ad-code-manager':
				return (bool) class_exists( 'Ad_Code_Manager' );

			case 'co-authors-plus':
				return (bool) class_exists( 'coauthors_plus' );

			default:
				return false;
		}

	}

}

/**
 * Load the theme
 *
 * @ignore
 */
function Largo() {
	return Largo::get_instance();
}
add_action( 'after_setup_theme', 'Largo' );

/**
 * Prints an admin warning if php is out of date.
 */
function largo_php_warning() {

	$minver = "5.3.0";
	$curver = phpversion();

	if( current_user_can('update_themes') && version_compare( $curver, $minver, "<" ) ) :
		$warning = "Largo requires <b>PHP $minver</b>. You're running <b>$curver</b>. Please upgrade your version of php.";
 		echo "<div class='update-nag'>";
    	_e( $warning, 'largo' );
 		echo "</div>";
 	endif;

}
add_action( 'admin_notices', 'largo_php_warning' );

/*
 * Load up all of the other goodies from the /inc directory
 */
$includes = array();

/*
 * This functionality is probably not for everyone so we'll make it easy to turn it on or off
 */
if ( of_get_option( 'custom_landing_enabled' ) && of_get_option( 'series_enabled' ) )
	$includes[] = '/inc/wp-taxonomy-landing/taxonomy-landing.php'; // adds taxonomy landing plugin

/*
 * Perform load
 */
foreach ( $includes as $include ) {
	require_once( get_template_directory() . $include );
}

if ( ! function_exists( 'largo_setup' ) ) {
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

if ( ! function_exists( 'of_set_option' ) ) {
	/**
	 * Helper for setting specific theme options (optionsframework).
	 *
	 * Would be nice if optionsframework included this natively
	 * See https://github.com/devinsays/options-framework-plugin/issues/167
	 */
	function of_set_option( $option_name, $option_value ) {
		$config = get_option( 'optionsframework' );

		if ( ! isset( $config['id'] ) ) {
			return false;
		}

		$options = get_option( $config['id'] );

		if ( $options ) {
			$options[$option_name] = $option_value;
			return update_option( $config['id'], $options );
		}

		return false;
	}
}
