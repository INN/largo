<?php

/**
 * Clean up <head>
 *
 * @since 1.0
 */
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_generator' );

/**
 * Setup some defaults for robots.txt
 * See: http://codex.wordpress.org/Search_Engine_Optimization_for_WordPress#Robots.txt_Optimization
 *
 * @since 1.0
 */
function largo_robots() {
	echo "Disallow: /cgi-bin\n";
	echo "Disallow: /wp-admin\n";
	echo "Disallow: /wp-includes\n";
	echo "Disallow: /wp-content/plugins\n";
	echo "Disallow: /plugins\n";
	echo "Disallow: /wp-content/cache\n";
	echo "Disallow: /wp-content/themes\n";
	echo "Disallow: /trackback\n";
	echo "Disallow: /feed\n";
	echo "Disallow: /comments\n";
	echo "Disallow: /category/*/*\n";
	echo "Disallow: */trackback\n";
	echo "Disallow: */feed\n";
	echo "Disallow: */comments\n";
	echo "Disallow: /*?*\n";
	echo "Disallow: /*?\n";
	echo "Allow: /wp-content/uploads\n";
	echo "Allow: /assets";
}
add_action( 'do_robots', 'largo_robots' );

/**
 * Various customizations for the admin dashboard
 *
 * @since 1.0
 */

// cleanup the wordpress dashboard and add a few of our own widgets
function largo_dashboard_widgets_member() {
     global $wp_meta_boxes;

     unset(
          $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'],
          $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'],
          $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'],
          $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'],
          $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments'],
          $wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts'],
          $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']
     );

     wp_add_dashboard_widget( 'dashboard_quick_links', 'Project Largo Help', 'dashboard_quick_links' );

     wp_add_dashboard_widget( 'dashboard_member_news', 'Recent Stories from INN Members', 'dashboard_member_news' );
     $my_widget = $wp_meta_boxes['dashboard']['normal']['core']['dashboard_member_news'];
     unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_member_news']);
     $wp_meta_boxes['dashboard']['side']['core']['dashboard_member_news'] = $my_widget;

     wp_add_dashboard_widget( 'dashboard_network_news', 'INN Network News', 'dashboard_network_news' );
     $my_widget = $wp_meta_boxes['dashboard']['normal']['core']['dashboard_network_news'];
     unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_network_news']);
     $wp_meta_boxes['dashboard']['side']['core']['dashboard_network_news'] = $my_widget;
}

// we'll still clean things up a bit for non INN members
function largo_dashboard_widgets_nonmember() {
     global $wp_meta_boxes;

     unset(
          $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'],
          $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'],
          $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'],
          $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'],
          $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']
     );

     wp_add_dashboard_widget( 'dashboard_quick_links', 'Project Largo Help', 'dashboard_quick_links' );

     wp_add_dashboard_widget( 'dashboard_network_news', 'INN Network News', 'dashboard_network_news' );
     $my_widget = $wp_meta_boxes['dashboard']['normal']['core']['dashboard_network_news'];
     unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_network_news']);
     $wp_meta_boxes['dashboard']['side']['core']['dashboard_network_news'] = $my_widget;
}

// custom dashboard widgets for INN members
function dashboard_network_news() {
     echo '<div class="rss-widget">';
     wp_widget_rss_output(array(
          'url' => 'http://investigativenewsnetwork.org/all-articles.rss',
          'title' => 'INN Network News',
          'items' => 1,
          'show_summary' => 1,
          'show_author' => 0,
          'show_date' => 1
     ));
     echo "</div>";
}
function dashboard_member_news() {
     echo '<div class="rss-widget">';
     wp_widget_rss_output(array(
          'url' => 'http://investigativenewsnetwork.org/all-member-news.rss',
          'title' => 'Recent Stories from INN Members',
          'items' => 3,
          'show_summary' => 1,
          'show_author' => 1,
          'show_date' => 1
     ));
     echo "</div>";
}
function dashboard_quick_links() {
     echo '
     	<div class="list-widget">
     		<p>If you\'re having trouble with your site, want to request a new feature or are just interested in learning more about Project Largo, here are a few helpful links:</p>
     		<ul>
     			<li><a href="http://largoproject.org/using-your-project-largo-site/">Largo Project Documentation</a></li>
     			<li><a href="http://largoproject.org/setup/">Largo Setup Guide</a></li>
     			<li><a href="http://largoproject.org/questions/">Ask A Question</a></li>
     			<li><a href="http://largoproject.org/contact/">Contact Us</a></li>
     		</ul>
     		<p>Developers can also log issues on <a href="https://github.com/INN/Largo">our public github repository</a> and if you would like to be included in our Largo users\' group, please e-mail: <a href="mailto:largo@investigativenewsnetwork.org">largo@investigativenewsnetwork.org</a>.</p>
     	</div>
     ';
}

// add the largo logo to the login page
function largo_custom_login_logo() {
	echo '
		<style type="text/css">
			h1 a { background-image: url(' . get_template_directory_uri() . '/img/largo-login-logo.png) !important; }
		</style>
	';
}

// only load the dashboard customizations if this is an INN member site
if ( INN_MEMBER === TRUE ) {
	add_action('login_head', 'largo_custom_login_logo');
	add_action('wp_dashboard_setup', 'largo_dashboard_widgets_member');
} else {
	add_action('wp_dashboard_setup', 'largo_dashboard_widgets_nonmember');
}

// add a credit line to the admin footer
function largo_admin_footer_text( $default_text ) {
     return '<span id="footer-thankyou">This website powered by <a href="http://largoproject.org">Project Largo</a> from the <a href="http://investigativenewsnetwork.org">Investigative News Network</a> and <a href="http://wordpress.org">WordPress</a>.</span>';
}
add_filter( 'admin_footer_text', 'largo_admin_footer_text' );

// remove the links menu item
function largo_admin_menu() {
     remove_menu_page('link-manager.php');
}
add_action( 'admin_menu', 'largo_admin_menu' );

/**
 * Create a full text RSS feed even if site is using excerpts in the main feed
 * URL for full text feed is: http://mysite.org/?feed=fulltext
 *
 * @since 1.0
 */

function largo_full_text_feed() {
    add_filter('pre_option_rss_use_excerpt', '__return_zero');
    load_template( ABSPATH . WPINC . '/feed-rss2.php' );
}
add_feed('fulltext', 'largo_full_text_feed');