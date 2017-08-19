<?php

/**
 * Registers all of the standard Largo homepage layout classes
 */
function largo_register_default_homepage_layouts() {
	// Load layouts from `layouts/`
	$layouts = glob(__DIR__ . '/layouts/*.php');
	foreach ($layouts as $layout)
		include_once $layout;

	// Load zone components from `zones/`
	$zones = glob(__DIR__ . '/zones/*.php');
	foreach ($zones as $zone)
		include_once $zone;

	$default_layouts = array(
		'HomepageBlog',
		'HomepageSingle',
		'HomepageSingleWithFeatured',
		'HomepageSingleWithSeriesStories',
		'TopStories',
		'LegacyThreeColumn'
	);

	foreach ($default_layouts as $layout)
		register_homepage_layout($layout);
}
add_action('init', 'largo_register_default_homepage_layouts', 0);

/**
 * Uses `$largo_homepage_factory` to build a list of homepage layouts. This list is used
 * in Theme Options and Customizer to allow the user to choose a Homepage layout.
 *
 * @return array An array of layouts, with friendly names as keys and arrays with 'path' and 'thumb' as values
 */
function largo_get_home_layouts() {
	global $largo_homepage_factory;

	$layouts = array();
	foreach ($largo_homepage_factory->layouts as $className => $layout) {
		$layouts[trim($layout->name)] = array(
			'path' => $className,
			'thumb' => largo_get_home_thumb($className),
			'desc' => $layout->description
		);
	}

	return $layouts;
}

/**
 * Retrieves the thumbnail image for a homepage template, or a default
 *
 * @return string The public url of the image file to use for the given template's screenshot
 */
function largo_get_home_thumb($className) {
	$home_thumb_url = '';
	// Check the child theme for a homepage layout thumbnail
	if (file_exists(get_stylesheet_directory() . '/homepages/assets/img/' . $className . '.png')) {
		$home_thumb_url = get_stylesheet_directory_uri() . '/homepages/assets/img/' . $className . '.png';
	}
	// Check the Largo theme for a homepage layout thumbnail
	elseif (file_exists(get_template_directory() . '/homepages/assets/img/' . $className . '.png')) {
		$home_thumb_url = get_template_directory_uri() . '/homepages/assets/img/' . $className . '.png';
	}
	// Use the Largo theme default homepage layout thumbnail
	else {
		$home_thumb_url = get_template_directory_uri() . '/homepages/assets/img/no-thumb.png';
	}

	return $home_thumb_url;
}

/**
 * Creates instance of a homepage layout class and renders it.
 */
function largo_render_homepage_layout($layout) {
	$hp = new $layout();
	$hp->render();
}

/**
 * Get the class name of the currently-active homepage layout
 */
function largo_get_active_homepage_layout() {
	return of_get_option('home_template', 'HomepageBlog');
}

/**
 * Get the post to display at the top of the home single template
 */
function largo_home_single_top() {
	$big_story = null;

	// Cache the terms
	$homepage_feature_term = get_term_by( 'name', __('Homepage Featured', 'largo'), 'prominence' );
	$top_story_term = get_term_by( 'name', __('Top Story', 'largo'), 'prominence' );

	// Get the posts that are both in 'Homepage Featured' and 'Top Story'
	$top_story_posts = get_posts(array(
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'prominence',
				'field' => 'term_id',
				'terms' => $top_story_term->term_id
			),
		),
		'posts_per_page' => 1
	));

	if ( !empty( $top_story_posts ) ) {
		return $top_story_posts[0];
	}

	// Fallback: get the posts that are in "Homepage Featured" but not "Top Story"
	$homepage_featured_posts = get_posts(array(
		'tax_query' => array(
			array(
				'taxonomy' => 'prominence',
				'field' => 'term_id',
				'terms' => $homepage_feature_term->term_id
			)
		),
		'posts_per_page' => 1
	));

	if ( !empty( $homepage_featured_posts ) ) {
		return $homepage_featured_posts[0];
	}

	// Double fallback: Get the most recent post
	$posts = get_posts( array(
		'orderby' => 'date',
		'order' => 'DESC',
		'posts_per_page' => 1
	) );

	if ( !empty( $posts ) ) {
		return $posts[0];
	}

	return null;
}

/**
 * Returns featured stories for the homepage.
 * 
 * @param int $max. The maximum number of posts to return.
 */
function largo_home_featured_stories($max = 3) {
	$big_story = largo_home_single_top();
	$homepage_feature_term = get_term_by( 'name', __('Homepage Featured', 'largo'), 'prominence' );

	// Get the homepage featured posts
	$featured_stories = get_posts(array(
		'tax_query' => array(
			array(
				'taxonomy' => 'prominence',
				'field' => 'term_id',
				'terms' => $homepage_feature_term->term_id
			)
		),
		'posts_per_page' => $max,
		'post__not_in' => array( $big_story->ID )
	));

	return $featured_stories;
}

/**
 * 1. Gets 3 stories from the same series as the homepage's Big Story.
 * 2. Gets the term that the 3 series stories belong to.
 *
 * @return array An array with `series_stories` and `series_stories_term` keys.
 * @todo is this duplicating the functionality of the Largo_Related class?
 */
function largo_home_series_stories_data() {
	$big_story = largo_home_single_top();

	$cache_key = 'largo_home_series_stories_data_' . $big_story->ID;
	if (false !== ($cached_data = get_transient($cache_key)))
		return $cached_data;

	$series_stories = array();

	$uncategorized_term = get_term_by('name', __('Uncategorized', 'largo'), 'category');
	$series_terms = wp_get_post_terms($big_story->ID, 'series');
	$category_terms = wp_list_filter(
		wp_get_post_terms($big_story->ID, 'category'),
		array('name' => $uncategorized_term->name), 'NOT'
	);
	$tags_terms = wp_get_post_terms($big_story->ID, 'post_tag');

	$terms = array_merge( $series_terms, $category_terms, $tags_terms );

	foreach ($terms as $term) {
		$posts = get_posts(array(
			'posts_per_page' => 3,
			'tax_query' => array(
				array(
					'taxonomy' => $term->taxonomy,
					'field' => 'term_id',
					'terms' => $term->term_id
				)
			)
		));

		// If we got more series posts than there is currently, use the more complete set
		if (count($posts) > count($series_stories)) {
			$series_stories = $posts;
			$series_stories_term = $term;

			// If we got three, then we can stop
			if ( count($posts) == 3 ) {
				break;
			}
		}
	}

	$ret = array(
		'series_stories' => $series_stories,
		'series_stories_term' => $series_stories_term
	);
	set_transient($cache_key, $ret, HOUR_IN_SECONDS);
	return $ret;
}

/**
 * Gets the homepage's Big Story series data and returns only the series stories' term.
 */
function largo_home_series_stories_term() {
	$ret = largo_home_series_stories_data();
	return $ret['series_stories_term'];
}

/**
 * Gets the homepages Big Story series data and returns only the series stories.
 */
function largo_home_series_stories() {
	$ret = largo_home_series_stories_data();
	return $ret['series_stories'];
}

/**
 * Returns the various posts for the homepage two and three panel layouts
 */
function largo_home_get_single_featured_and_series() {
	$big_story = largo_home_single_top();
	$featured_stories = largo_home_featured_stories();
	$series_stories = largo_home_series_stories();
	$series_stories_term = largo_home_series_stories_term();

	if ( empty( $big_story ) ) {
		// Something bad has happened!
		return array();
	}

	return array(
		'big_story' => $big_story,
		'series_stories' => $series_stories,
		'featured_stories' => $featured_stories,
		'series_stories_term' => $series_stories_term,
	);
}
