<?php
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
 * Get the various posts for the homepage hero-series-side template
 */
function largo_home_hero_side_series() {
	$big_story = largo_home_single_top();
	$series_stories = array();
	$featured_stories = array();
	$series_stories_term = null;

	if ( empty( $big_story ) ) {
		// Something bad has happened!
		return array();
	}

	// Cache the terms
	$homepage_feature_term = get_term_by( 'name', __('Homepage Featured', 'largo'), 'prominence' );
	// $top_story_term = get_term_by( 'name', __('Top Story', 'largo'), 'prominence' );
	$uncategorized_term = get_term_by( 'name', __('Uncategorized'), 'category' );


	// Get the homepage featured posts
	$featured_stories = get_posts(array(
		'tax_query' => array(
			array(
				'taxonomy' => 'prominence',
				'field' => 'term_id',
				'terms' => $homepage_feature_term->term_id
			)
		),
		'posts_per_page' => 3,
		'post__not_in' => array( $big_story->ID )
	));

	// Get the series posts
	$series_terms = wp_get_post_terms( $big_story->ID, 'series' );
	$category_terms = wp_list_filter( wp_get_post_terms( $big_story->ID, 'category' ), array( 'name' => $uncategorized_term->name ), 'NOT' );
	$tags_terms = wp_get_post_terms( $big_story->ID, 'post_tag' );

	$terms = array_merge( $series_terms, $category_terms, $tags_terms );

	foreach ( $terms as $term ) {
		$posts = get_posts( array(
			'posts_per_page' => 3,
			'tax_query' => array(
				array(
					'taxonomy' => $term->taxonomy,
					'field' => 'term_id',
					'terms' => $term->term_id
				)
			)
		) );

		// If we got more series posts than there is currently, use the more complete set
		if ( count($posts) > count($series_stories) ) {
			$series_stories = $posts;
			$series_stories_term = $term;

			// If we got three, then we can stop
			if ( count($posts) == 3 ) {
				break;
			}
		}
	}

	return array(
		'big_story' => $big_story,
		'series_stories' => $series_stories,
		'featured_stories' => $featured_stories,
		'series_stories_term' => $series_stories_term,
	);
}