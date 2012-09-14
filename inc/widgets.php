<?php

/**
 * remove the unsupported default WP widgets
 */
function largo_unregister_widgets() {
	unregister_widget( 'WP_Widget_Pages' );
	unregister_widget( 'WP_Widget_Calendar' );
	unregister_widget( 'WP_Widget_Links' );
	unregister_widget( 'WP_Widget_Tag_Cloud' );
	unregister_widget( 'WP_Widget_Meta' );
	unregister_widget( 'WP_Widget_Recent_Comments' );
	unregister_widget( 'WP_Nav_Menu_Widget' );
	unregister_widget( 'WP_Widget_RSS' );
	unregister_widget( 'WP_Widget_Recent_Posts' );
}
add_action( 'widgets_init', 'largo_unregister_widgets' );

// load our new widgets
require_once( get_template_directory() . '/inc/widgets/largo-follow.php' );
require_once( get_template_directory() . '/inc/widgets/largo-footer-featured.php' );
require_once( get_template_directory() . '/inc/widgets/largo-sidebar-featured.php' );
require_once( get_template_directory() . '/inc/widgets/largo-about.php' );
require_once( get_template_directory() . '/inc/widgets/largo-donate.php' );
require_once( get_template_directory() . '/inc/widgets/largo-twitter.php' );
require_once( get_template_directory() . '/inc/widgets/largo-recent-posts.php' );
require_once( get_template_directory() . '/inc/widgets/largo-inn-rss.php' );

// ...and then register them
function largo_load_widgets() {
    register_widget( 'largo_follow_widget' );
    register_widget( 'largo_footer_featured_widget' );
    register_widget( 'largo_sidebar_featured_widget' );
    register_widget( 'largo_about_widget' );
    register_widget( 'largo_donate_widget' );
    register_widget( 'largo_twitter_widget' );
    register_widget( 'largo_recent_posts_widget' );
    register_widget( 'largo_INN_RSS_widget' );
}
add_action( 'widgets_init', 'largo_load_widgets' );

?>