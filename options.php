<?php
/**
 * Largo's Options Framework configuration file
 *
 * Defines all of the default options and available values for Largo.
 *
 * @package Largo
 */

//=//

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
	$imagepath =  get_template_directory_uri() . '/lib/options-framework/images/';
	$home_templates = array();
	$home_templates_data = largo_get_home_layouts();
	if ( count($home_templates_data) ) {
		foreach ($home_templates_data as $name => $data) {
			$home_templates[ $data['path'] ] = '<img src="'.$data['thumb'].'" style="float: left; margin-right: 8px; max-width: 120px; height: auto; border: 1px solid #ddd;"><strong>'.$name.'</strong> '.$data['desc'];
		}
	}

	$tag_display_options = array(
		'top' 	=> __('Single Tag Above', 'largo'),
		'btm' 	=> __('List Below', 'largo'),
		'none' 	=> __('None', 'largo')
	);

	$article_utility_buttons = array(
		'facebook' 	=> __('Facebook', 'largo'),
		'twitter' 	=> __('Twitter', 'largo'),
		'print' 	=> __('Print', 'largo'),
		'email' 	=> __('Email', 'largo')
	);

	$article_utility_buttons_defaults = array(
		'facebook' => '1',
		'twitter'  => '1',
		'email'    => '1',
		'print'    => '1'
	);

	$footer_utility_buttons = array(
		'ffacebook' => __('Facebook', 'largo'),
		'ftwitter'  => __('Twitter', 'largo'),
		'femail'    => __('Email', 'largo')
	);

	$footer_utility_buttons_defaults = array(
		'ffacebook' => '1',
		'ftwitter'  => '1',
		'femail'    => '1'
	);

	$fb_verbs = array(
		'like' 		=> __('Like', 'largo'),
		'recommend' => __('Recommend', 'largo')
	);

	$region_options = array();
	global $wp_registered_sidebars;
	$excluded = array(
		'Footer 1', 'Footer 2', 'Footer 3', 'Article Bottom', 'Header Ad Zone'
	);
	// Let others change the list
	$excluded = apply_filters( 'largo_excluded_sidebars', $excluded );
	foreach( $wp_registered_sidebars as $sidebar_id => $sidebar ) {
		//check if excluded
		if ( in_array( $sidebar_id, $excluded ) || in_array( $sidebar['name'], $excluded ) ) continue;
		$region_options[$sidebar_id] = $sidebar['name'];
	}

	$options = array();
	$widget_options = array();

	/**
	 * Basic Options
	 */
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
		'desc' 	=> __('<strong>Show</strong> a button in the top header to link to your donation page or form.', 'largo'),
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
		'name' 	=> __('Menu Options', 'largo'),
		'desc' 	=> __('<strong>Show</strong> the "Don\'t Miss" menu under the main site navigation. Add links to this menu under <strong>Appearance > Menus</strong>.', 'largo'),
		'id' 	=> 'show_dont_miss_menu',
		'type' 	=> 'checkbox');

	$options[] = array(
		'desc' 	=> __('Enter the <strong>label that appears in front of the menu links in the "Don\'t Miss" menu</strong>. You can delete this default and no label will appear.', 'largo'),
		'id' 	=> 'dont_miss_label',
		'std' 	=> __('Don\'t Miss', 'largo'),
		'class' => 'hidden',
		'type' 	=> 'text');

	$options[] = array(
		'desc' 	=> __('Enter the <strong>label that appears before the menu links in the Footer Nav Menu</strong>. You can delete this default and no label will appear.', 'largo'),
		'id' 	=> 'footer_menu_label',
		'std' 	=> get_bloginfo('name'),
		'type' 	=> 'text');

	$options[] = array(
		'desc'  => __('Show the <strong>sticky nav</strong>? Default is to show, but in some cases you may want to hide it.'),
		'id'    => 'show_sticky_nav',
		'std' 	=> '1',
		'type' 	=> 'checkbox');

	$options[] = array(
		'desc' 	=> __('Show the <strong>site name in the sticky nav</strong>? Default is to show, but in some cases you might want to hide it to save space or if your logo is clear enough to not need it.', 'largo'),
		'id' 	=> 'show_sitename_in_sticky_nav',
		'std' 	=> '1',
		'class' => 'hidden',
		'type' 	=> 'checkbox');

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
		'name' 	=> __('Word to use for "Posts"', 'largo'),
		'desc' 	=> __('WordPress calls single article pages "posts" but you might prefer to use another name. <strong>Enter the singular and plural forms</strong> of the word you want to use here.', 'largo'),
		'type' 	=> 'info');

	$options[] = array(
		'desc' 	=> __('<strong>Singular Form</strong> (e.g. - post, story, article)', 'largo'),
		'id' 	=> 'posts_term_singular',
		'std' 	=> 'Post',
		'type' 	=> 'text');

	$options[] = array(
		'desc' 	=> __('<strong>Plural form</strong> (e.g. - posts, stories, articles)', 'largo'),
		'id' 	=> 'posts_term_plural',
		'std' 	=> 'Posts',
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
		'desc' 	=> __('<strong>Link to Tumblr</strong> (http://yoursite.tumblr.com)', 'largo'),
		'id' 	=> 'tumblr_link',
		'std' 	=> '',
		'type' 	=> 'text');

	$options[] = array(
		'desc' 	=> __('<strong>Link to LinkedIn Group or Profile</strong> (http://www.linkedin.com/in/username/)', 'largo'),
		'id' 	=> 'linkedin_link',
		'std' 	=> '',
		'type' 	=> 'text');

	$options[] = array(
		'desc' 	=> __('<strong>Link to Github Page</strong> (http://github.com/username)', 'largo'),
		'id' 	=> 'github_link',
		'std' 	=> '',
		'type' 	=> 'text');

	$options[] = array(
		'desc' 	=> __('By default, a row of social media icons is shown in the site footer. <strong>Check this box if you want to show them in the header as well</strong>. Note that they will only display on desktops and larger tablets.', 'largo'),
		'id' 	=> 'show_header_social',
		'type' 	=> 'checkbox');

	$options[] = array(
		'name' 	=> __('Single Post Options', 'largo'),
		'type' 	=> 'info');

	$options[] = array(
		'desc' 		=> __('<strong>Would you like to display share icons at the top of single posts?</strong> By default social icons appear at the top of single posts but you can choose to not show them at all.', 'largo'),
		'id' 		=> 'single_social_icons',
		'std' 		=> '1',
		'type' 		=> 'checkbox',);

	$options[] = array(
		'desc' 		=> __('Select the <strong>share icons</strong> to display at the top of single posts.', 'largo'),
		'id' 		=> 'article_utilities',
		'std' 		=> $article_utility_buttons_defaults,
		'type' 		=> 'multicheck',
		'options' 	=> $article_utility_buttons);

	$options[] = array(
		'desc' 		=> __('<strong>Would you like to display share icons in the footer of single posts?</strong> By default social icons appear in the sticky footer of single posts but you can choose to not show them at all.', 'largo'),
		'id' 		=> 'single_social_icons_footer',
		'std' 		=> '1',
		'type' 		=> 'checkbox',);

	$options[] = array(
		'desc' 		=> __('Select the <strong>share icons</strong> to display in the single post sticky footer.', 'largo'),
		'id' 		=> 'footer_utilities',
		'std' 		=> $footer_utility_buttons_defaults,
		'type' 		=> 'multicheck',
		'options' 	=> $footer_utility_buttons);

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
		'desc' 	=> __('<strong>Show share count</strong> with Twitter buttons.', 'largo'),
		'id' 	=> 'show_twitter_count',
		'std' 	=> '1',
		'type' 	=> 'checkbox');

	/**
	 * Images Options
	 */
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
		'name' 	=> __('Sticky Header Logo', 'largo'),
		'desc' 	=> __('Used in the sticky navigation. This image should be 100px tall and at least 100px wide. This logo will be hidden on mobile sites if the <b>Show Site Name in Sticky Nav</b> option is checked under <b>Basic Settings</b>', 'largo'),
		'id' 	=> 'sticky_header_logo',
		'type' 	=> 'upload');

	/**
	 * Layout Options
	 */
	$options[] = array(
		'name' => __('Layout Options', 'largo'),
		'type' => 'heading');

	if (count($home_templates)) {
		$home_std= 'HomepageBlog';

		$home_template = of_get_option('home_template');
		if (empty($home_template)) {
			if (of_get_option('homepage_layout') == '3col')
				$home_std= 'LegacyThreeColumn';
		}

		$options[] = array(
			'name' => __('Home Template', 'largo'),
			'desc' => __('<strong>Select the layout to use for the top of the homepage.</strong> These are Home Templates, defined much like post/page templates.', 'largo'),
			'id' => 'home_template',
			'std' => $home_std,
			'type' => 'radio',
			'options' => $home_templates
		);
	}

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
		'desc' 	=> __('<strong>Select the layout to use for the bottom of the homepage.</strong> Largo supports three options: a single column list of recent posts with photos and excerpts, a two column widget area, or nothing whatsoever', 'largo'),
		'id' 		=> 'homepage_bottom',
		'std' 	=> 'list',
		'type' 	=> 'images',
		'options' => array(
			'list' 		=> $imagepath . 'list.png',
			'widgets' => $imagepath . 'widgets.png',
			'none'		=> $imagepath . 'none.png')
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
		'name' 	=> __('Single Article Template', 'largo'),
		'type' 	=> 'info');

	$options[] = array(
		'desc' 	=> __('Starting with version 0.4, Largo introduced a new single-post template that more prominently highlights article content, which is the default. For backward compatibility, the pre-0.3 version is also available.', 'largo'),
		'id' 	=> 'single_template',
		'std' 	=> 'normal',
		'type' 	=> 'select',
		'options' => array(
			'normal' => 'One Column (Standard Layout)',
			'classic' => 'Two Column (Classic Layout)'
			)
		);

	$widget_options[] = $options[] = array(
		'name' 	=> __('Sidebar Options', 'largo'),
		'type' 	=> 'info');

	$widget_options[] = $options[] = array(
		'desc' 	=> __('By default Largo has two sidebars. One is used for single pages and posts and the other is used for everything else (including the homepage). Check this box if you would like to have a third sidebar to be used in place of the main sidebar on archive pages (category, tag, author and series pages).', 'largo'),
		'id' 	=> 'use_topic_sidebar',
		'std' 	=> '0',
		'type' 	=> 'checkbox');

	$widget_options[] = $options[] = array(
		'desc' 	=> __('Include an additional widget region ("sidebar") just above the site footer region', 'largo'),
		'id' 	=> 'use_before_footer_sidebar',
		'std' 	=> '0',
		'type' 	=> 'checkbox');

	$widget_options[] = $options[] = array(
		'desc' 	=> __('Check this box if you want to fade the sidebar out on single story pages as a reader scrolls.', 'largo'),
		'id' 	=> 'showey_hidey',
		'std' 	=> '0',
		'type' 	=> 'checkbox');

	$widget_options[] = $options[] = array(
		'desc' 	=> __('Enter names of <strong>additional sidebar regions</strong> (one per line) you\'d like post authors to be able to choose to display on their posts.', 'largo'),
		'id' 	=> 'custom_sidebars',
		'std' 	=> '',
		'type' 	=> 'textarea');

	$widget_options[] = $options[] = array(
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

	/*
	 * Advanced
	 */

	$options[] = array(
		'name' 	=> __('Advanced', 'largo'),
		'type' 	=> 'heading');

	$options[] = array(
		'desc' 	=> __('Enable Custom LESS to CSS For Theme Customization.', 'largo'),
		'id' 	=> 'less_enabled',
		'std' 	=> '0',
		'type' 	=> 'checkbox');

	$options[] = array(
		'desc' 	=> __('Enable "Series" taxonomy.', 'largo'),
		'id' 	=> 'series_enabled',
		'std' 	=> '0',
		'type' 	=> 'checkbox');

	$options[] = array(
		'desc' 	=> __('Enable Custom Landing Pages for Series/Project Pages. Requires "Series" taxonomy to be enabled.', 'largo'),
		'id' 	=> 'custom_landing_enabled',
		'std' 	=> '0',
		'class' => 'hidden',
		'type' 	=> 'checkbox');

	$options[] = array(
		'desc' 	=> __('Enable Optional Leaderboard Ad Zone.', 'largo'),
		'id' 	=> 'leaderboard_enabled',
		'std' 	=> '0',
		'type' 	=> 'checkbox');

	$options[] = array(
		'desc' 	=> __('Enable "Post Types" taxonomy.', 'largo'),
		'id' 	=> 'post_types_enabled',
		'std' 	=> '0',
		'type' 	=> 'checkbox');

	$options[] = array(
		'desc' 	=> __('Default region in lefthand column of Landing Pages', 'largo'),
		'id' 	=> 'landing_left_region_default',
		'std' 	=> 'sidebar-main',
		'type' 	=> 'select',
		'options' => $region_options);

	$options[] = array(
		'desc' 	=> __('Default region in righthand column of Landing Pages', 'largo'),
		'id' 	=> 'landing_right_region_default',
		'std' 	=> 'sidebar-main',
		'type' 	=> 'select',
		'options' => $region_options);

	// hidden field logs largo version to facilitate tracking which set of options are stored
	$largo = wp_get_theme('largo');
	$options[] = array(
		'id' 	=> 'largo_version',
		'std' 	=> $largo->get('Version'),
		'type' 	=> 'text',
		'class' => 'hidden');


	$screen = get_current_screen();
	if ( is_object( $screen ) && $screen->base == 'widgets' ) {
		return $widget_options;
	}


	/* Disclaimer */

	$options[] = array(
		'name'	=> __('Disclaimer', 'largo'),
		'desc' 	=> __('Enable Disclaimer Widget.', 'largo'),
		'id' 	=> 'disclaimer_enabled',
		'std' 	=> '0',
		'type' 	=> 'checkbox');

	$options[] = array(
		'desc' 	=> __('Enter a default disclaimer', 'largo'),
		'id' 	=> 'default_disclaimer',
		'std' 	=> '',
		'type' 	=> 'textarea');


	/* Search Options */

	$options[] = array(
		'name' => __('Search options', 'largo'),
		'desc' 	=> __('Replace WordPress search with Google Custom Search', 'largo'),
		'id' 	=> 'use_gcs',
		'std' 	=> '0',
		'type' 	=> 'checkbox');

	$options[] = array(
		'desc' 	=> __('Search engine ID (something like 012174647732932797336:f2lixuynrs0) ', 'largo'),
		'id' 	=> 'gcs_id',
		'std' 	=> '',
		'type' 	=> 'text');

	$options[] = array(
		'desc' 	=> __('Google Custom Search typically returns better results than the search engine built into WordPress. You can get your ID and configure it at <a href="https://www.google.com/cse/create/new">https://www.google.com/cse/create/new</a>.', 'largo'),
		'id' 	=> 'gcs_help',
		'std' 	=> '',
		'type' 	=> 'info');

	$options[] = array(
		'name' => __('Site verification', 'largo'),
		'desc' 	=> __('<strong>Twitter Account ID.</strong> This is used for verifying your site for Twitter Analytics. This is NOT your username, it will be a 9 digit number.', 'largo'),
		'id' 	=> 'twitter_acct_id',
		'std' 	=> '',
		'type' 	=> 'text');

	$options[] = array(
		'desc' 	=> __('<strong>Google site verification meta tag.</strong> Used to verify a site in Google Webmaster Tools. This will be a long string of numbers and letters.', 'largo'),
		'id' 	=> 'google_site_verification',
		'std' 	=> '',
		'type' 	=> 'text');

	$options[] = array(
		'desc' 	=> __('<strong>Facebook admins meta tag.</strong> This is a comma-separated list of numerical FB user IDs you want to allow to access Facebook insights for your site.', 'largo'),
		'id' 	=> 'fb_admins',
		'std' 	=> '',
		'type' 	=> 'text');

	$options[] = array(
		'desc' 	=> __('<strong>Facebook app ID meta tag.</strong> This is a numerical app ID that will allow Facebook to capture insights for any social plugins active on your site and display them in your Facebook app/page insights.', 'largo'),
		'id' 	=> 'fb_app_id',
		'std' 	=> '',
		'type' 	=> 'text');

	$options[] = array(
		'desc' 	=> __('<strong>Bitly site verification.</strong> This is a string of numbers and letters used to verify your site with bitly analytics.', 'largo'),
		'id' 	=> 'bitly_verification',
		'std' 	=> '',
		'type' 	=> 'text');

	$options[] = array(
		'name' 	=> __('SEO Options', 'largo'),
		'type'	=> 'info');

	$options[] = array(
		'desc' 	=> __('Use noindex for all archive pages (default is to use noindex for just date archives).', 'largo'),
		'id' 	=> 'noindex_archives',
		'std' 	=> '0',
		'type' 	=> 'checkbox');

	if ( INN_MEMBER ) { // only relevant in this case, options affecting the logo display
		$options[] = array(
			'name' 	=> __('INN Membership Options', 'largo'),
			'type'	=> 'info');

		$options[] = array(
			'desc' 	=> __('<b>When did your organization join INN?</b> Leave this field blank if you do not want to display a membership year on your site.', 'largo'),
			'id' 	=> 'inn_member_since',
			'std' 	=> '',
			'type' 	=> 'text');
	}

	return apply_filters('largo_options', $options);
}

/*
 * This is an example of how to add custom scripts to the options panel.
 * This example shows/hides an option when a checkbox is clicked.
 */
add_action('optionsframework_custom_scripts', 'optionsframework_custom_scripts');

/**
 * This function prints Javascript on the Theme Options admin page to control the behavior
 * of certain options that depend or require other options.
 *
 * For example, you can not use Custom Landing Pages unless Series taxonomy is enabled. So,
 * this script will hide the Custom Landing Pages option until the Series taxonomy checkbox
 * is enabled.
 */
function optionsframework_custom_scripts() { ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	// show/hide don't miss.
	$('#show_dont_miss_menu').click(function() {
		$('#section-dont_miss_label').fadeToggle(400);
	});

	if ($('#show_dont_miss_menu:checked').val() !== undefined) {
		$('#section-dont_miss_label').show();
	}

	// show/hide sticky nav.
	$('#show_sticky_nav').click(function() {
		$('#section-show_sitename_in_sticky_nav').fadeToggle(400);
	});

	if ($('#show_sticky_nav:checked').val() !== undefined) {
		$('#section-show_sitename_in_sticky_nav').show();
	}

	// show/hide donate button.
	$('#show_donate_button').click(function() {
		$('#section-donate_link').fadeToggle(400);
		$('#section-donate_button_text').fadeToggle(400);
	});

	if ($('#show_donate_button:checked').val() !== undefined) {
		$('#section-donate_link').show();
		$('#section-donate_button_text').show();
	}

	// show/hide disclaimer.
	$('#disclaimer_enabled').click(function() {
		$('#section-default_disclaimer').fadeToggle(400);
	});

	if ($('#disclaimer_enabled:checked').val() == undefined) {
		$('#section-default_disclaimer').hide();
	}

	// show/hide show tags.
	$('#show_tags').click(function() {
		$('#section-tag_limit').fadeToggle(400);
	});

	if ($('#show_tags:checked').val() !== undefined) {
		$('#section-tag_limit').show();
	}

	// show/hide custom series landing pages.
	$('#series_enabled').click(function() {
		$('#section-custom_landing_enabled').fadeToggle(400);
		$('#section-custom_landing_enabled input').removeAttr('checked');
	});

	if ($('#series_enabled:checked').val() !== undefined) {
		$('#section-custom_landing_enabled').show();
	}
});
</script>
<?php
}
