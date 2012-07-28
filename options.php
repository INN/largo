<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 */

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet
	$themename = get_option( 'stylesheet' );
	$themename = preg_replace("/\W/", "_", strtolower($themename) );

	$optionsframework_settings = get_option( 'optionsframework' );
	$optionsframework_settings['id'] = $themename;
	update_option( 'optionsframework', $optionsframework_settings );
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'options_framework_theme'
 * with the actual text domain for your theme.  Read more:
 * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 */

function optionsframework_options() {

	$options = array();

	$options[] = array(
		'name' => __('Basic Settings', 'largo'),
		'type' => 'heading');

	$options[] = array(
		'name' => __('Feed URL', 'largo'),
		'desc' => __('Enter the <strong>URL for your primary RSS feed.</strong> You can override the default if you use Feedburner or some other service to generate or track your RSS feed', 'largo'),
		'id' => 'rss_link',
		'std' => get_feed_link(),
		'type' => 'text');

	$options[] = array(
		'name' => __('Donate Button', 'largo'),
		'desc' => __('<strong>Show/Hide</strong> a button in the top header to link to your donation page or form.', 'largo'),
		'id' => 'show_donate_button',
		'type' => 'checkbox');

	$options[] = array(
		'desc' => __('Enter the <strong>link to your donation page</strong> or form (include http://).', 'largo'),
		'id' => 'donate_link',
		'std' => '',
		'class' => 'hidden',
		'type' => 'text');

	$options[] = array(
		'desc' => __('Enter the <strong>text for the donate button</strong> (e.g. - Support Us).', 'largo'),
		'id' => 'donate_button_text',
		'std' => 'Donate Now',
		'class' => 'hidden',
		'type' => 'text');

	$options[] = array(
		'name' => __('Don\'t Miss Menu', 'largo'),
		'desc' => __('<strong>Show/Hide</strong> the "Don\'t Miss" menu under the main site navigation. Add links to this menu under <strong>Appearance > Menus</strong>.', 'largo'),
		'id' => 'show_dont_miss_menu',
		'type' => 'checkbox');

	$options[] = array(
		'desc' => __('Enter the <strong>label that appears in front of the menu links</strong>. You can delete this default and no label will appear.', 'largo'),
		'id' => 'dont_miss_label',
		'std' => 'Don\'t Miss',
		'class' => 'hidden',
		'type' => 'text');

	$options[] = array(
		'name' => __('Footer Nav Menu', 'largo'),
		'desc' => __('Enter the <strong>label that appears before the menu links</strong>. You can delete this default and no label will appear.', 'largo'),
		'id' => 'footer_menu_label',
		'std' => get_bloginfo('name'),
		'type' => 'text');

	$options[] = array(
		'name' => __('Copyright Message', 'largo'),
		'desc' => __('Enter the <strong>copyright and credit information</strong> to display in the footer. You can use <code>%d</code> to output the current year.', 'largo'),
		'id' => 'copyright_msg',
		'std' => '&copy; Copyright %d, '. get_bloginfo('name'),
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Google Analytics', 'largo'),
		'desc' => __('Enter your <strong>Google Analytics ID (UA-XXXXXXXX-X)</strong> and the code will be included in the footer.', 'largo'),
		'id' => 'ga_id',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Social Media Links', 'largo'),
		'desc' => __('Enter the links for your organization\'s primary social media accounts. To change social media settings for a user, view their <strong>edit profile</strong> screen.', 'largo'),
		'type' => 'info');

	$options[] = array(
		'desc' => __('<strong>Link to Facebook Page</strong> (https://www.facebook.com/username)', 'largo'),
		'id' => 'facebook_link',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'desc' => __('<strong>Link to Twitter Profile</strong> (https://twitter.com/username)', 'largo'),
		'id' => 'twitter_link',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'desc' => __('<strong>Link to Google+ Page</strong> (https://plus.google.com/userID/)', 'largo'),
		'id' => 'gplus_link',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'desc' => __('<strong>Link to YouTube Channel</strong> (http://www.youtube.com/user/username)', 'largo'),
		'id' => 'youtube_link',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'desc' => __('<strong>Link to Flickr Photostream</strong> (http://www.flickr.com/photos/username/)', 'largo'),
		'id' => 'flickr_link',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Single Post Options', 'largo'),
		'type' => 'info');

	$options[] = array(
		'name' => __('Show Author Box', 'largo'),
		'desc' => __('<strong>Show/Hide</strong> the author bio at the bottom of single posts.', 'largo'),
		'id' => 'show_author_box',
		'std' => '1',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Show Related Content', 'largo'),
		'desc' => __('<strong>Show/Hide</strong> related posts at the bottom of single posts.', 'largo'),
		'id' => 'show_related_content',
		'std' => '1',
		'type' => 'checkbox');

	return $options;
}

/*
 * This is an example of how to add custom scripts to the options panel.
 * This example shows/hides an option when a checkbox is clicked.
 */

add_action('optionsframework_custom_scripts', 'optionsframework_custom_scripts');

function optionsframework_custom_scripts() { ?>

<script type="text/javascript">
jQuery(document).ready(function($) {

	$('#show_dont_miss_menu').click(function() {
  		$('#section-dont_miss_label').fadeToggle(400);
	});

	if ($('#show_dont_miss_menu:checked').val() !== undefined) {
		$('#section-dont_miss_label').show();
	}

	$('#show_donate_button').click(function() {
  		$('#section-donate_link').fadeToggle(400);
  		$('#section-donate_button_text').fadeToggle(400);
	});

	if ($('#show_donate_button:checked').val() !== undefined) {
		$('#section-donate_link').show();
		$('#section-donate_button_text').show();
	}

});
</script>

<?php
}