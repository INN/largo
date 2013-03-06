<?php
/**
 * Functions for hooking in various sidebars, stylesheets, etc for the wpjobboard plugin
 */

// Enqueue styles
function largo_jobboard_enqueue() {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( is_plugin_active('wpjobboard/index.php')) {
		wp_enqueue_style( 'job-board-styles', get_template_directory_uri() . '/css/job-board.css', false, false, 'screen' );
	}
}
add_action('wp_enqueue_scripts', 'largo_jobboard_enqueue' );


// Register sidebar
function largo_jobboard_register_sidebar() {
	register_sidebar( array(
		'name' 			=> __( 'Job Board', 'largo' ),
		'description' 	=> __( 'A widget area on job board pages', 'largo' ),
		'id' 			=> 'jobboard-widgets',
		'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
		'after_widget' 	=> '</aside>',
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>',
	) );
}
add_action('widgets_init', 'largo_jobboard_register_sidebar' );


//Output sidebar. Relies on custom hook.
function largo_jobboard_output_sidebar() {
		if ( largo_is_job_page() && is_active_sidebar( 'jobboard-widgets' )) {
			dynamic_sidebar( 'jobboard-widgets' );
		}
}
add_action('largo_after_sidebar_widgets', 'largo_jobboard_output_sidebar' );


/**
 * Tests if the current page is a part of the WPJobBoard plugin
 */
function largo_is_job_page() {
	$jobboardOptions = get_option('wpjb_config', NULL);
	if (is_array($jobboardOptions)) {
		$wpjb_page_ids = array( $jobboardOptions['link_jobs'], $jobboardOptions['link_resumes'] );
	} else {
		//Options weren't present, meaning plugin isn't installed, meaning we can't be on a job page.
		return false;
	}

	if (is_singular()) :
		global $post;
		if (in_array($post->ID, $wpjb_page_ids)) return true;
	endif;

	//failure
	return false;
}