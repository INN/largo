<?php
/**
 * Various customizations for the admin dashboard
 *
 * @package Largo
 * @since 0.1
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

	wp_add_dashboard_widget( 'dashboard_quick_links', __( 'Project Largo Help', 'largo' ), 'largo_dashboard_quick_links' );

	wp_add_dashboard_widget( 'dashboard_network_news', __( 'INN Network News', 'largo' ), 'largo_dashboard_network_news' );
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

	wp_add_dashboard_widget( 'dashboard_quick_links', __( 'Project Largo Help', 'largo' ), 'largo_dashboard_quick_links' );

	wp_add_dashboard_widget( 'dashboard_network_news', __( 'INN Network News', 'largo' ), 'largo_dashboard_network_news' );
	$my_widget = $wp_meta_boxes['dashboard']['normal']['core']['dashboard_network_news'];
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_network_news']);
	$wp_meta_boxes['dashboard']['side']['core']['dashboard_network_news'] = $my_widget;
}

// custom dashboard widgets for INN members
function largo_dashboard_network_news() {
	echo '<div class="rss-widget">';
	wp_widget_rss_output( array(
		'url' => 'https://feeds.feedburner.com/INNArticles',
		'title' => __( 'INN Network News', 'largo' ),
		'items' => 1,
		'show_summary' => 1,
		'show_author' => 0,
		'show_date' => 1
	));
	echo '</div>';
}

/**
 * Largo dashboard quick links
 */
function largo_dashboard_quick_links() {
	echo '
		<div class="list-widget">
			<p>If you\'re having trouble with your site, want to request a new feature or are just interested in learning more about Project Largo, here are a few helpful links:</p>
			<ul>
				<li><a href="https://largo.wpbuddy.co/" target="_blank" rel="external noopener noreferrer">Largo Project Website</a></li>
				<li><a href="https://largo.readthedocs.io/" target="_blank" rel="external noopener noreferrer">Largo Documentation</a></li>
				<li><a href="mailto:largo@wpbuddy.co">Contact Us</a></li>
				<li><a href="https://largo.wpbuddy.co/support" target="_blank" rel="external noopener noreferrer">Help Desk</a></li>
				<li><a href="https://largo.wpbuddy.co/support" target="_blank" rel="external noopener noreferrer">Knowledge Base</a></li>
			</ul>
			<p>Developers can also log issues on <a href="https://github.com/WPBuddy/largo/issues">our public github repository</a></p>
		</div>
	';
}

// add the largo logo to the login page
function largo_custom_login_logo() {
	echo '
		<style type="text/css">
			.login h1 a {
			  background-image: url(' . get_template_directory_uri() . '/img/largo-login-logo.png) !important;
			  background-size:  164px 169px;
			  height: 169px;
			  width: 164px;
			}
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

/**
 * Largo Dashboard / Admin Bar Menu
 * -- Priority 15 Places between WordPress Logo and My Sites
 * -- To move menu to end of items use something like priority 999
 */

add_action( 'admin_bar_menu', 'largo_dash_admin_menu', 15 );
function largo_dash_admin_menu( $wp_admin_bar ) {

	// Add Top Level Text Node for Dropdown
	$args = array( 'id' => 'largo_admin_mega', 'title' => 'Largo' );
	$wp_admin_bar->add_node( $args );

	// Main Website
	$args = array( 'id' => 'website', 'title' => 'Main Website', 'href' => 'https://largo.wpbuddy.co/', 'parent' => 'largo_admin_mega' );
	$wp_admin_bar->add_node( $args );

	// Documentation
	$args = array( 'id' => 'largo_docs', 'title' => 'Documentation', 'href' => 'https://largo.readthedocs.io/', 'parent' => 'largo_admin_mega' );
	$wp_admin_bar->add_node( $args );

	// Knowledge Base
	$args = array( 'id' => 'knowledge_base', 'title' => 'Knowledge Base', 'href' => 'https://largo.wpbuddy.co/support', 'parent' => 'largo_admin_mega' );
	$wp_admin_bar->add_node( $args );

	// Member Help Desk
	$args = array( 'id' => 'support', 'title' => 'Help Desk', 'href' => 'https://largo.wpbuddy.co/support', 'parent' => 'largo_admin_mega' );
	$wp_admin_bar->add_node( $args );

	// Member Forums
	$args = array( 'id' => 'user_forums', 'title' => 'Community Forums', 'href' => 'https://largo.wpbuddy.co/support', 'parent' => 'largo_admin_mega' );
	$wp_admin_bar->add_node( $args );

	// Largo on GitHub
	$args = array( 'id' => 'github', 'title' => 'Largo on GitHub', 'href' => 'https://github.com/WPBuddy/largo', 'parent' => 'largo_admin_mega' );
	$wp_admin_bar->add_node( $args );

	// Largo on Twitter
	$args = array( 'id' => 'twitter', 'title' => '@LargoProject on Twitter', 'href' => 'https://twitter.com/largoproject', 'parent' => 'largo_admin_mega');
	$wp_admin_bar->add_node( $args );

// 	// INN Nerds
// 	$args = array( 'id' => 'inn_nerds', 'title' => 'INN Nerds', 'href' => 'https://labs.inn.org/', 'parent' => 'largo_admin_mega' );
// 	$wp_admin_bar->add_node( $args );
//
// 	// About INN
// 	$args = array( 'id' => 'about_inn', 'title' => 'About INN', 'href' => 'https://inn.org', 'parent' => 'largo_admin_mega' );
// 	$wp_admin_bar->add_node( $args );
//
// 	// Donate
// 	$args = array( 'id' => 'donate_inn', 'title' => 'Donate', 'href' => 'https://inn.org/donate/', 'parent' => 'largo_admin_mega' );
// 	$wp_admin_bar->add_node( $args );

}

// add a credit line to the admin footer
function largo_admin_footer_text( $default_text ) {
	return '<span id="footer-thankyou">This website powered by <a href="https://largo.wpbuddy.co/">Project Largo</a> from <a href="https://wpbuddy.co/">WpBuddy.co</a> and <a href="https://wordpress.org">WordPress</a>.</span>';
}
add_filter( 'admin_footer_text', 'largo_admin_footer_text' );

// remove the links menu item and the media options
function largo_admin_menu() {
	remove_menu_page( 'link-manager.php' );
	remove_menu_page( 'options-media.php' );
}
add_action( 'admin_menu', 'largo_admin_menu' );
