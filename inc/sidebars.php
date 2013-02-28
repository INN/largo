<?php

/**
 * Register our sidebars and other widget areas
 *
 * @since 1.0
 */
function largo_register_sidebars() {
	register_sidebar( array(
		'name' 			=> __( 'Main Sidebar', 'largo' ),
		'description' 	=> __( 'The sidebar for the homepage. If you do not add widgets to any of the other sidebars, this will also be used on all of the other pages of your site.', 'largo' ),
		'id' 			=> 'sidebar-main',
		'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
		'after_widget' 	=> "</aside>",
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>',
	) );

	register_sidebar( array(
		'name' 			=> __( 'Single Sidebar', 'largo' ),
		'description' 	=> __( 'The sidebar for posts and pages', 'largo' ),
		'id' 			=> 'sidebar-single',
		'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
		'after_widget' 	=> "</aside>",
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>',
	) );

	if ( of_get_option( 'use_topic_sidebar' ) ) {
		register_sidebar( array(
			'name' 			=> __( 'Archive/Topic Sidebar', 'largo' ),
			'description' 	=> __( 'The sidebar for category, tag and other archive pages', 'largo' ),
			'id' 			=> 'topic-sidebar',
			'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
			'after_widget' 	=> "</aside>",
			'before_title' 	=> '<h3 class="widgettitle">',
			'after_title' 	=> '</h3>',
		) );
	}

	register_sidebar( array(
		'name' 			=> __( 'Footer 1', 'largo' ),
		'description' 	=> __( 'The first footer widget area.', 'largo' ),
		'id' 			=> 'footer-1',
		'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
		'after_widget' 	=> "</aside>",
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>',
	) );

	register_sidebar( array(
		'name' 			=> __( 'Footer 2', 'largo' ),
		'description' 	=> __( 'The second footer widget area.', 'largo' ),
		'id' 			=> 'footer-2',
		'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
		'after_widget' 	=> "</aside>",
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>',
	) );

	register_sidebar( array(
		'name' 			=> __( 'Footer 3', 'largo' ),
		'description' 	=> __( 'The third footer widget area.', 'largo' ),
		'id' 			=> 'footer-3',
		'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
		'after_widget' 	=> "</aside>",
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>',
	) );

	if ( of_get_option('footer_layout') == '4col' ) :
		register_sidebar( array(
			'name' 			=> __( 'Footer 4', 'largo' ),
			'description' 	=> __( 'The fourth footer widget area.', 'largo' ),
			'id' 			=> 'footer-4',
			'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
			'after_widget' 	=> "</aside>",
			'before_title' 	=> '<h3 class="widgettitle">',
			'after_title' 	=> '</h3>',
		) );
	endif;

	if ( of_get_option('homepage_layout') == '3col' ) :
		register_sidebar( array(
			'name' 			=> __( 'Homepage Left Rail', 'largo' ),
			'description' 	=> __( 'An optional widget area that, when enabled, appears to the left of the main content area on the homepage.', 'largo' ),
			'id' 			=> 'homepage-left-rail',
			'before_widget' => '<div id="%1$s" class="%2$s">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> '<h3 class="widgettitle">',
			'after_title' 	=> '</h3>',
		) );
	endif;

	if ( of_get_option('homepage_bottom') == 'widgets' ) :
		register_sidebar( array(
			'name' 			=> __( 'Homepage Bottom', 'largo' ),
			'description' 	=> __( 'An optional widget area at the bottom of the homepage', 'largo' ),
			'id' 			=> 'homepage-bottom',
			'before_widget' => '<div id="%1$s" class="%2$s span6">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> '<h3 class="widgettitle">',
			'after_title' 	=> '</h3>',
		) );
	endif;

	//job board widget area, if we're running wpjobboard
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if (is_plugin_active('wpjobboard/index.php')) :
		register_sidebar( array(
			'name' 			=> __( 'Job Board', 'largo' ),
			'description' 	=> __( 'A widget area on job board pages', 'largo' ),
			'id' 			=> 'jobboard-widgets',
			'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
			'after_widget' 	=> '</aside>',
			'before_title' 	=> '<h3 class="widgettitle">',
			'after_title' 	=> '</h3>',
		) );
	endif;
}
add_action( 'widgets_init', 'largo_register_sidebars' );