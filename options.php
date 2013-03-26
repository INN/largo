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

	$imagepath =  get_template_directory_uri() . '/inc/options-framework/images/';

	$display_options = array(
		'top' 	=> __('Top', 'largo'),
		'btm' 	=> __('Bottom', 'largo'),
		'both' 	=> __('Both', 'largo'),
		'none' 	=> __('None', 'largo')
	);

	$tag_display_options = array(
		'top' 	=> __('Single Tag Above', 'largo'),
		'btm' 	=> __('List Below', 'largo'),
		'none' 	=> __('None', 'largo')
	);

	$article_utility_buttons = array(
		'facebook' 	=> __('Facebook', 'largo'),
		'twitter' 	=> __('Twitter', 'largo'),
		'sharethis' => __('ShareThis', 'largo'),
		'email' 	=> __('Email', 'largo'),
		'print' 	=> __('Print', 'largo')
	);

	$article_utility_buttons_defaults = array(
		'facebook' 	=> '1',
		'twitter' 	=> '1',
		'sharethis' => '1',
		'email' 	=> '1',
		'print' 	=> '1'
	);

	$fb_verbs = array(
		'like' 		=> __('Like', 'largo'),
		'recommend' => __('Recommend', 'largo')
	);

	$options = array();

	$options[] = array(
		'name' 	=> __('Basic Settings', 'largo'),
		'type' 	=> 'heading');

	$options[] = array(
		'name' 	=> __('Site Description', 'largo'),
		'desc' 	=> __('Enter a <strong>short blurb about your site</strong>. This is used in a sidebar widget', 'largo'),
		'id' 	=> 'site_blurb',
		'std' 	=> '',
		'type' 	=> 'textarea');

	$options[] = array(
		'name' 	=> __('Feed URL', 'largo'),
		'desc' 	=> __('Enter the <strong>URL for your primary RSS feed.</strong> You can override the default if you use Feedburner or some other service to generate or track your RSS feed', 'largo'),
		'id' 	=> 'rss_link',
		'std' 	=> get_feed_link(),
		'type' 	=> 'text');

	$options[] = array(
		'name' 	=> __('Donate Button', 'largo'),
		'desc' 	=> __('<strong>Show/Hide</strong> a button in the top header to link to your donation page or form.', 'largo'),
		'id' 	=> 'show_donate_button',
		'type' 	=> 'checkbox');

	$options[] = array(
		'desc' 	=> __('Enter the <strong>link to your donation page</strong> or form (include http://).', 'largo'),
		'id' 	=> 'donate_link',
		'std' 	=> '',
		'class' => 'hidden',
		'type' 	=> 'text');

	$options[] = array(
		'desc' 	=> __('Enter the <strong>text for the donate button</strong> (e.g. - Support Us).', 'largo'),
		'id' 	=> 'donate_button_text',
		'std' 	=> __('Donate Now', 'largo'),
		'class' => 'hidden',
		'type' 	=> 'text');

	$options[] = array(
		'name' 	=> __('Don\'t Miss Menu', 'largo'),
		'desc' 	=> __('<strong>Show/Hide</strong> the "Don\'t Miss" menu under the main site navigation. Add links to this menu under <strong>Appearance > Menus</strong>.', 'largo'),
		'id' 	=> 'show_dont_miss_menu',
		'type' 	=> 'checkbox');

	$options[] = array(
		'desc' 	=> __('Enter the <strong>label that appears in front of the menu links</strong>. You can delete this default and no label will appear.', 'largo'),
		'id' 	=> 'dont_miss_label',
		'std' 	=> __('Don\'t Miss', 'largo'),
		'class' => 'hidden',
		'type' 	=> 'text');

	$options[] = array(
		'name' 	=> __('Footer Nav Menu', 'largo'),
		'desc' 	=> __('Enter the <strong>label that appears before the menu links</strong>. You can delete this default and no label will appear.', 'largo'),
		'id' 	=> 'footer_menu_label',
		'std' 	=> get_bloginfo('name'),
		'type' 	=> 'text');

	$options[] = array(
		'name' 	=> __('Copyright Message', 'largo'),
		'desc' 	=> __('Enter the <strong>copyright and credit information</strong> to display in the footer. You can use <code>%d</code> to output the current year.', 'largo'),
		'id' 	=> 'copyright_msg',
		'std' 	=> __('&copy; Copyright %d, '. get_bloginfo('name'), 'largo'),
		'type' 	=> 'textarea');

	$options[] = array(
		'name' 	=> __('Google Analytics', 'largo'),
		'desc' 	=> __('Enter your <strong>Google Analytics ID (UA-XXXXXXXX-X)</strong> and the code will be included in the footer.', 'largo'),
		'id' 	=> 'ga_id',
		'std' 	=> '',
		'type' 	=> 'text');

	$options[] = array(
		'name' 	=> __('Social Media Links', 'largo'),
		'desc' 	=> __('Enter the links for your organization\'s primary social media accounts. To change social media settings for a user, view their <strong>edit profile</strong> screen.', 'largo'),
		'type' 	=> 'info');

	$options[] = array(
		'desc' 	=> __('<strong>Link to Facebook Page</strong> (https://www.facebook.com/username)', 'largo'),
		'id' 	=> 'facebook_link',
		'std' 	=> '',
		'type' 	=> 'text');

	$options[] = array(
		'desc' 	=> __('<strong>Link to Twitter Profile</strong> (https://twitter.com/username)', 'largo'),
		'id' 	=> 'twitter_link',
		'std' 	=> '',
		'type' 	=> 'text');

	$options[] = array(
		'desc' 	=> __('<strong>Link to Google+ Page</strong> (https://plus.google.com/userID/)', 'largo'),
		'id' 	=> 'gplus_link',
		'std' 	=> '',
		'type' 	=> 'text');

	$options[] = array(
		'desc' 	=> __('<strong>Link to YouTube Channel</strong> (http://www.youtube.com/user/username)', 'largo'),
		'id' 	=> 'youtube_link',
		'std' 	=> '',
		'type' 	=> 'text');

	$options[] = array(
		'desc' 	=> __('<strong>Link to Flickr Photostream</strong> (http://www.flickr.com/photos/username/)', 'largo'),
		'id' 	=> 'flickr_link',
		'std' 	=> '',
		'type' 	=> 'text');

	$options[] = array(
		'desc' 	=> __('<strong>Link to LinkedIn Group or Profile</strong> (http://www.linkedin.com/in/username/)', 'largo'),
		'id' 	=> 'linkedin_link',
		'std' 	=> '',
		'type' 	=> 'text');

	$options[] = array(
		'desc' 	=> __('By default, a row of social media icons is shown in the site footer. <strong>Check this box is you want to show them in the header as well</strong>. Note that they will only display on desktops and larger tablets.', 'largo'),
		'id' 	=> 'show_header_social',
		'type' 	=> 'checkbox');

	$options[] = array(
		'name' 	=> __('Single Post Options', 'largo'),
		'type' 	=> 'info');

	$options[] = array(
		'desc' 	=> __('<strong>Show/Hide list of tags</strong> at the bottom of single posts.', 'largo'),
		'id' 	=> 'show_tags',
		'std' 	=> '1',
		'type' 	=> 'checkbox');

	$options[] = array(
		'desc' 	=> __('Enter the <strong>maximum number of tags to show</strong>.', 'largo'),
		'id' 	=> 'tag_limit',
		'std' 	=> 20,
		'class' => 'hidden',
		'type' 	=> 'text');

	$options[] = array(
		'desc' 	=> __('<strong>Show/Hide the author bio</strong> at the bottom of single posts.', 'largo'),
		'id' 	=> 'show_author_box',
		'std' 	=> '1',
		'type' 	=> 'checkbox');

	$options[] = array(
		'desc' 	=> __('<strong>Show/Hide related posts</strong> at the bottom of single posts.', 'largo'),
		'id' 	=> 'show_related_content',
		'std' 	=> '1',
		'type' 	=> 'checkbox');

	$options[] = array(
		'desc' 	=> __('<strong>Show/Hide next/prev post navigation</strong> at the bottom of single posts.', 'largo'),
		'id' 	=> 'show_next_prev_nav_single',
		'std' 	=> '1',
		'type' 	=> 'checkbox');

	$options[] = array(
		'desc' 		=> __('<strong>Where would you like to display share icons on single posts?</strong> By default social icons appear at both the top and the bottom of single posts but you can choose to show them in only one or the other or to not show them at all.', 'largo'),
		'id' 		=> 'social_icons_display',
		'std' 		=> 'both',
		'type' 		=> 'select',
		'class'		=> 'mini',
		'options' 	=> $display_options);

	$options[] = array(
		'desc' 		=> __('Select the <strong>share icons</strong> to display on single posts.', 'largo'),
		'id' 		=> 'article_utilities',
		'std' 		=> $article_utility_buttons_defaults,
		'type' 		=> 'multicheck',
		'options' 	=> $article_utility_buttons);

	$options[] = array(
		'desc' 		=> __('<strong>Use "like" or "recommend"</strong> for Facebook buttons?', 'largo'),
		'id' 		=> 'fb_verb',
		'std' 		=> 'like',
		'type'		=> 'select',
		'class'		=> 'mini',
		'options' 	=> $fb_verbs);

	$options[] = array(
		'desc' 		=> __('Location of <strong>"Clean Read"</strong> link', 'largo'),
		'id' 		=> 'clean_read',
		'std' 		=> 'none',
		'type'		=> 'select',
		'class'		=> 'mini',
		'options' 	=> 	array(
			'none' 		=> __('Nowhere (disabled)', 'largo'),
			'byline' => __('In Byline', 'largo'),
			'footer' => __('Below Tags', 'largo')
		));

	$options[] = array(
		'desc' 	=> __('<strong>Show/Hide share count</strong> with Twitter buttons.', 'largo'),
		'id' 	=> 'show_twitter_count',
		'std' 	=> '1',
		'type' 	=> 'checkbox');

	$options[] = array(
		'name' 	=> __('SEO Options', 'largo'),
		'type'	=> 'info');

	$options[] = array(
		'desc' 	=> __('Use noindex for all archive pages (default is to use noindex for just date archives).', 'largo'),
		'id' 	=> 'noindex_archives',
		'std' 	=> '0',
		'type' 	=> 'checkbox');

	$options[] = array(
		'name' 	=> __('Theme Images', 'largo'),
		'type' 	=> 'heading');

	$options[] = array(
		'name' 	=> __('Upload a Square Thumbnail Image (200x200px minimum)', 'largo'),
		'desc' 	=> __('This is a default image used for Facebook posts when you do not set a featured image for your posts. We also use it as a bookmark icon for Apple devices.', 'largo'),
		'id' 	=> 'logo_thumbnail_sq',
		'type' 	=> 'upload');

	$options[] = array(
		'name' 	=> __('Upload a Favicon', 'largo'),
		'desc' 	=> __('This is the small icon that appears in browser tabs and in some feed readers and other applications. Favicons must be an .ico file and are typically 16x16px square.', 'largo'),
		'id' 	=> 'favicon',
		'type' 	=> 'upload');

	$options[] = array(
		'name' 	=> __('Header Image', 'largo'),
		'type' 	=> 'info');

	$options[] = array(
		'desc' 	=> __('<strong>Use only text</strong> in the place of a banner image (uses site title and description).', 'largo'),
		'id' 	=> 'no_header_image',
		'std' 	=> '0',
		'type' 	=> 'checkbox');

	$options[] = array(
		'name' 	=> __('Small Banner Image (768px wide)', 'largo'),
		'desc' 	=> __('Used for viewports below 768px wide (mostly phones and some tablets). Recommended height: 240px.', 'largo'),
		'id' 	=> 'banner_image_sm',
		'type' 	=> 'upload');

	$options[] = array(
		'name' 	=> __('Medium Banner Image (980px wide)', 'largo'),
		'desc' 	=> __('Used for viewports between 768px and 980 px (mostly tablets). Recommended height: 180px.', 'largo'),
		'id' 	=> 'banner_image_med',
		'type' 	=> 'upload');

	$options[] = array(
		'name' 	=> __('Large Banner Image (1170px wide)', 'largo'),
		'desc' 	=> __('Used for viewports above 980 px (landscape tablets and desktops). Recommended height: 150px.', 'largo'),
		'id' 	=> 'banner_image_lg',
		'type' 	=> 'upload');

	$options[] = array(
		'name' 	=> __('Layout Options', 'largo'),
		'type' 	=> 'heading');

	$options[] = array(
		'name' 	=> __('Overall Homepage Layout', 'largo'),
		'desc' 	=> __('<strong>Select the overall layout you would like to use for your site\'s homepage.</strong> By default, Largo has a two column layout with a main content area on the left and a configurable sidebar on the right, but you can add a skinny side rail (configurable under the appearance > widgets tab) to left of the main content area by selecting the three-column option.', 'largo'),
		'id' 	=> 'homepage_layout',
		'std' 	=> '2col',
		'type' 	=> 'images',
		'options' 	=> array(
			'2col' => $imagepath . '2col.png',
			'3col' => $imagepath . '3col.png')
	);

	$options[] = array(
		'name' 	=> __('Homepage Top', 'largo'),
		'desc' 	=> __('<strong>Select the layout to use for the top of the homepage.</strong> Largo currently supports three homepage options: a blog-like list of posts with the ability to stick a post to the op of the homepage, a newspaper-like layout highlighting featured stories and an animated carousel of featured stories with large images.', 'largo'),
		'id' 	=> 'homepage_top',
		'std' 	=> 'blog',
		'type' 	=> 'images',
		'options' 	=> array(
			'blog' 			=> $imagepath . 'blog.png',
			'topstories' 	=> $imagepath . 'newsy.png',
			'slider' 		=> $imagepath . 'slider.png')
	);

	$options[] = array(
		'name' 	=> __('Sticky Posts', 'largo'),
		'type' 	=> 'info');

	$options[] = array(
		'desc' 	=> __('Show sticky posts box on homepage? If checked, you will need to set at least one post as sticky for this box to appear.', 'largo'),
		'id' 	=> 'show_sticky_posts',
		'std' 	=> '1',
		'type' 	=> 'checkbox');

	$options[] = array(
		'name' 	=> __('Homepage Bottom', 'largo'),
		'desc' 	=> __('<strong>Select the layout to use for the bottom of the homepage.</strong> Largo currently supports two options: a single column list of recent posts with photos and excerpts or a two column widget area', 'largo'),
		'id' 	=> 'homepage_bottom',
		'std' 	=> 'list',
		'type' 	=> 'images',
		'options' 	=> array(
			'list' 		=> $imagepath . 'list.png',
			'widgets' 	=> $imagepath . 'widgets.png')
	);

	$options[] = array(
		'name' 	=> __('Other Homepage Display Options', 'largo'),
		'type' 	=> 'info');

	$options[] = array(
		'desc' 		=> __('<strong>How would you like to display categories and tags for posts on the homepage?</strong> Largo can display a single category or tag above the headline for each story, a list of tags below the story\'s excerpt or nothing at all.', 'largo'),
		'id' 		=> 'tag_display',
		'std' 		=> 'top',
		'type' 		=> 'select',
		'class'		=> 'mini',
		'options' 	=> $tag_display_options);

	$options[] = array(
		'desc' 	=> __('<strong>Number of posts</strong> to display in the main loop on the homepage', 'largo'),
		'id' 	=> 'num_posts_home',
		'std' 	=> 10,
		'type' 	=> 'text');

	$options[] = array(
		'desc' 	=> __('<strong>Categories to include or exclude</strong> in the main loop on the homepage (comma-separated list of values, see: http://bit.ly/XmDGgd for correct format).', 'largo'),
		'id' 	=> 'cats_home',
		'std' 	=> '',
		'type' 	=> 'text');

	$options[] = array(
		'name' 	=> __('Sidebar Options', 'largo'),
		'type' 	=> 'info');

	$options[] = array(
		'desc' 	=> __('By default Largo has two sidebars. One is used for single pages and posts and the other is used for everything else (including the homepage). Check this box if you would like to have a third sidebar to be used in place of the main sidebar on archive pages (category, tag, author and series pages).', 'largo'),
		'id' 	=> 'use_topic_sidebar',
		'std' 	=> '0',
		'type' 	=> 'checkbox');

	$options[] = array(
		'desc' 	=> __('Check this box if you want to fade the sidebar out on single story pages as a reader scrolls.', 'largo'),
		'id' 	=> 'showey_hidey',
		'std' 	=> '0',
		'type' 	=> 'checkbox');

	$options[] = array(
		'desc' 	=> __('Enter names of <strong>additional sidebar regions</strong> (one per line) you\'d like post authors to be able to choose to display on their posts.', 'largo'),
		'id' 	=> 'custom_sidebars',
		'std' 	=> '',
		'type' 	=> 'textarea');

	$options[] = array(
		'name' 	=> __('Footer Layout', 'largo'),
		'desc' 	=> __('<strong>Select the layout to use for the footer.</strong> The default is a 3 column footer with a wide center column. Alternatively you can choose to have 3 or 4 equal columns. Each column is a widget area that can be configured under the Appearance > Widgets menu.', 'largo'),
		'id' 	=> 'footer_layout',
		'std' 	=> '3col-default',
		'type' 	=> 'images',
		'options' 	=> array(
			'3col-default'	=> $imagepath . 'footer-3col-lg-center.png',
			'3col-equal' 	=> $imagepath . 'footer-3col-equal.png',
			'4col' 			=> $imagepath . 'footer-4col.png')
	);


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

	$('#show_tags').click(function() {
  		$('#section-tag_limit').fadeToggle(400);
	});

	if ($('#show_tags:checked').val() !== undefined) {
		$('#section-tag_limit').show();
	}

});
</script>

<?php
}