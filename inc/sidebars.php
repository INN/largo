<?php

/*
 * SIDEBAR REGISTRATION
 */
function largo_register_sidebars() {
	register_sidebar( array(
		'name' => 'Main Sidebar',
		'id' => 'sidebar-main',
		'description' => 'The sidebar for index and archive pages',
		'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => 'Single Sidebar',
		'id' => 'sidebar-single',
		'description' => 'The sidebar for posts and pages',
		'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => 'Topic Sidebar',
		'id' => 'topic-sidebar',
		'description' => 'The sidebar for category and tag pages',
		'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => 'Footer Featured Posts',
		'id' => 'footer-featured-posts',
		'description' => 'Center footer column.',
		'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => 'Footer Widget Area',
		'id' => 'footer-widget-area',
		'description' => 'A configurable widget area in the far right column of the site footer.',
		'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

}
add_action( 'widgets_init', 'largo_register_sidebars' );