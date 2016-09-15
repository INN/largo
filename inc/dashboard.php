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

	wp_add_dashboard_widget( 'dashboard_member_news', __( 'Recent Stories from INN Members', 'largo' ), 'largo_dashboard_member_news' );
	$my_widget = $wp_meta_boxes['dashboard']['normal']['core']['dashboard_member_news'];
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_member_news']);
	$wp_meta_boxes['dashboard']['side']['core']['dashboard_member_news'] = $my_widget;

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
		'url' => 'http://feeds.feedburner.com/INNArticles',
		'title' => __( 'INN Network News', 'largo' ),
		'items' => 1,
		'show_summary' => 1,
		'show_author' => 0,
		'show_date' => 1
	));
	echo '</div>';
}
function largo_dashboard_member_news() {
	echo '<div class="rss-widget">';
	wp_widget_rss_output(array(
		'url' => 'http://feeds.feedburner.com/INNMemberInvestigations',
		'title' => __( 'Recent Stories from INN Members', 'largo' ),
		'items' => 3,
		'show_summary' => 1,
		'show_author' => 1,
		'show_date' => 1
	));
	echo "</div>";
}
function largo_dashboard_quick_links() {
	echo '
		<div class="list-widget">
			<p>If you\'re having trouble with your site, want to request a new feature or are just interested in learning more about Project Largo, here are a few helpful links:</p>
			<ul>
				<li><a href="http://largoproject.org/">Largo Project Website</a></li>
				<li><a href="http://largo.readthedocs.io/">Largo Documentation</a></li>
				<li><a href="http://support.largoproject.org">Help Desk</a></li>
				<li><a href="http://support.largoproject.org/support/solutions">Knowledge Base</a></li>
				<li><a href="mailto:support@largoproject.org">Contact Us</a></li>
			</ul>
			<p>Developers can also log issues on <a href="https://github.com/INN/Largo">our public github repository</a> and if you would like to be included in our Largo users\' group, <a href="http://inn.us1.list-manage1.com/subscribe?u=81670c9d1b5fbeba1c29f2865&id=913028b23c">sign up here</a>.</p>
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
	$args = array( 'id' => 'website', 'title' => 'Main Website', 'href' => 'http://largoproject.org/', 'parent' => 'largo_admin_mega' ); 
	$wp_admin_bar->add_node( $args );
	
	// Documentation
	$args = array( 'id' => 'largo_docs', 'title' => 'Documentation', 'href' => 'http://largo.readthedocs.io/', 'parent' => 'largo_admin_mega' ); 
	$wp_admin_bar->add_node( $args );
	
	// Knowledge Base
	$args = array( 'id' => 'knowledge_base', 'title' => 'Knowledge Base', 'href' => 'http://support.largoproject.org/support/solutions', 'parent' => 'largo_admin_mega' ); 
	$wp_admin_bar->add_node( $args );
	
	// Member Help Desk
	$args = array( 'id' => 'support', 'title' => 'Help Desk', 'href' => 'http://support.largoproject.org', 'parent' => 'largo_admin_mega' ); 
	$wp_admin_bar->add_node( $args );
	
	// Member Forums
	$args = array( 'id' => 'user_forums', 'title' => 'Community Forums', 'href' => 'http://support.largoproject.org/support/discussions', 'parent' => 'largo_admin_mega' ); 
	$wp_admin_bar->add_node( $args );
	
	// Largo on GitHub
	$args = array( 'id' => 'github', 'title' => 'Largo on GitHub', 'href' => 'https://github.com/inn/largo', 'parent' => 'largo_admin_mega' ); 
	$wp_admin_bar->add_node( $args );
	
	// Largo on Twitter
	$args = array( 'id' => 'twitter', 'title' => '@LargoProject on Twitter', 'href' => 'https://twitter.com/largoproject', 'parent' => 'largo_admin_mega'); 
	$wp_admin_bar->add_node( $args );
	
	// INN Nerds
	$args = array(' id' => 'inn_nerds', 'title' => 'INN Nerds', 'href' => 'http://nerds.inn.org', 'parent' => 'largo_admin_mega' ); 
	$wp_admin_bar->add_node( $args );
	
	// About INN	
	$args = array( 'id' => 'about_inn', 'title' => 'About INN', 'href' => 'http://inn.org', 'parent' => 'largo_admin_mega' ); 
	$wp_admin_bar->add_node( $args );
	
	// Donate
	$args = array( 'id' => 'donate_inn', 'title' => 'Donate', 'href' => 'https://inn.org/donate', 'parent' => 'largo_admin_mega' ); 
	$wp_admin_bar->add_node( $args );

}

// add a credit line to the admin footer
function largo_admin_footer_text( $default_text ) {
	return '<span id="footer-thankyou">This website powered by <a href="http://largoproject.org">Project Largo</a> from <a href="http://inn.org">INN</a> and <a href="http://wordpress.org">WordPress</a>.</span>';
}
add_filter( 'admin_footer_text', 'largo_admin_footer_text' );

// remove the links menu item and the media options
function largo_admin_menu() {
	remove_menu_page( 'link-manager.php' );
	remove_menu_page( 'options-media.php' );
}
add_action( 'admin_menu', 'largo_admin_menu' );
