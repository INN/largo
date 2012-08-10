<?php
/*
 remove the unsupported default WP widgets
 complete list (for reference):
 	unregister_widget('WP_Widget_Pages');
 	unregister_widget('WP_Widget_Calendar');
 	unregister_widget('WP_Widget_Archives');
 	unregister_widget('WP_Widget_Links');
 	unregister_widget('WP_Widget_Meta');
 	unregister_widget('WP_Widget_Search');
 	unregister_widget('WP_Widget_Text');
 	unregister_widget('WP_Widget_Categories');
 	unregister_widget('WP_Widget_Recent_Posts');
 	unregister_widget('WP_Widget_Recent_Comments');
 	unregister_widget('WP_Widget_RSS');
 	unregister_widget('WP_Widget_Tag_Cloud');
 	unregister_widget('WP_Nav_Menu_Widget');
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

require_once( TEMPLATEPATH . '/inc/widgets/largo-follow.php' );
require_once( TEMPLATEPATH . '/inc/widgets/largo-footer-featured.php' );
require_once( TEMPLATEPATH . '/inc/widgets/largo-sidebar-featured.php' );
require_once( TEMPLATEPATH . '/inc/widgets/largo-about.php' );
require_once( TEMPLATEPATH . '/inc/widgets/largo-donate.php' );
require_once( TEMPLATEPATH . '/inc/widgets/largo-twitter.php' );
require_once( TEMPLATEPATH . '/inc/widgets/largo-recent-posts.php' );

// ...and then register them

function largo_load_widgets() {
    register_widget( 'largo_follow_widget' );
    register_widget( 'largo_footer_featured_widget' );
    register_widget( 'largo_sidebar_featured_widget' );
    register_widget( 'largo_about_widget' );
    register_widget( 'largo_donate_widget' );
    register_widget( 'largo_twitter_widget' );
    register_widget( 'largo_recent_posts_widget' );
}
add_action( 'widgets_init', 'largo_load_widgets' );