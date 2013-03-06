<?php
/**
 * Functions for hooking in various sidebars, stylesheets, etc for the Business Directory plugin
 */


// Enqueue styles
function largo_bd_enqueue() {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( is_plugin_active('business-directory-plugin/wpbusdirman.php')) {
		wp_enqueue_style( 'business-directory-styles', get_template_directory_uri() . '/css/business-directory.css', false, false, 'screen' );
	}
}
add_action('wp_enqueue_scripts', 'largo_bd_enqueue' );


// Register sidebar
function largo_bd_register_sidebar() {
	register_sidebar( array(
		'name' 			=> __( 'Business Directory Sidebar', 'largo' ),
		'description' 	=> __( 'The sidebar for the the business directory.', 'largo' ),
		'id' 			=> 'bd-widgets',
		'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
		'after_widget' 	=> "</aside>",
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>',
	) );
}
add_action('widgets_init', 'largo_bd_register_sidebar' );


//Output sidebar. Relies on custom hook.
function largo_bd_output_sidebar() {
	if ( is_active_sidebar( 'bd-widgets' ) && largo_is_bd_page()) {
		dynamic_sidebar( 'bd-widgets' );
   }
}
add_action('largo_sidebar', 'largo_bd_output_sidebar' );


/**
 * Checks to see if we're on a page that's outputting the Business Directory
 * Inspects page content for shortcode, which seems hack-ish but not sure there's another way
 */
function largo_is_bd_page() {
	global $post;
	if ( is_page() && stripos($post->post_content, '[businessdirectory]') !== FALSE) return true;
	return false;
}