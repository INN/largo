<?php

/*
 * SIDEBAR REGISTRATION
 */
function argo_register_sidebars() {
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
		'name' => 'Footer Area One',
		'id' => 'footer-1',
		'description' => 'left footer region',
		'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => 'Footer Area Two',
		'id' => 'footer-2',
		'description' => 'center footer regio',
		'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => 'Footer Area Three',
		'id' => 'footer-3',
		'description' => 'right footer region',
		'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'argo_register_sidebars' );