<?php
/**
 * Get the data for the home-series template 
 *
 *	Big_Story is the bigest post on the left
 *	Side_Stories are the smaller three posts on the right
 *	Side_Stories_Display is how we should display the Side_Stories
 *
 *	IF there are more than 1 "Homepage Featured" post
 *	THEN
 *		IF there is a post that is both "Homepage Featured" AND "Top Story"
 *		THEN
 *			Big_Stories := the most recent post in "Homepage Featured" AND "Top Story"
 *		ELSE
 *			Big_Stories := the most recent post in "Homepage Featured"
 *		ENDIF
 *		
 *		Side_Stories := Up to 3 "Homepage Featured" posts excluding Big_Story
 *		Side_Stories_Display := Display as separate articles
 *
 *	ELSE we have 1 or 0 "Homepage Featured" posts
 *		IF there is a post that is "Top Story"
 *		THEN
 *			Big_Story := the most recent "Top Story"
 *		ELSE IF there is a post that is "Homepage Featured"
 *		THEN
 *			Big_Story := the most recent "Homepage Featured"
 *		ELSE
 *			Big_Story := the most recent post
 *		ENDIF
 *		
 *		IF Big_Story is part of a series
 *		THEN
 *			Side_Stories := the 3 most recent posts in the Big_Story series excluding Big_Story
 *		ELSE IF Big_Story has a category
 *		THEN
 *			Side_Stories := the 3 most recent posts that share the category excluding Big_Story
 *		ELSE IF Big_Story has a tag
 *		THEN
 *			Side_Stories := the 3 most recent posts that share the tag excluding Big_Story
 *		ELSE
 *			Side_Stories_Display := Don't display
 *		ENDIF
 *	ENDIF
 *
 *	Display the Big_Story on the left
 *
 *	IF Side_Stories is not empty
 *	THEN
 *		Display the Side_Stories on the right side
 *	ENDIF
 */
function largo_home_series_states() {
	$big_story = null;
	$side_stories = array();
	$side_stories_display = 'hide';
	$side_stories_term = null;

	// Cache the terms
	$homepage_feature_term = get_term_by( 'name', __('Homepage Featured', 'largo'), 'prominence' );
	$top_story_term = get_term_by( 'name', __('Top Story', 'largo'), 'prominence' );
	$uncategorized_term = get_term_by( 'name', __('Uncategorized'), 'category' );

	// Get the posts that are both in 'Homepage Featured' and 'Top Story'
	$homepage_featured_and_top_story_posts = get_posts(array(
		'tax_query' => array(
			'relation' => 'AND',
			// array(
			// 	'taxonomy' => 'prominence',
			// 	'field' => 'term_id',
			// 	'terms' => $homepage_feature_term->term_id
			// ),
			array(
				'taxonomy' => 'prominence',
				'field' => 'term_id',
				'terms' => $top_story_term->term_id
			),
		),
		'posts_per_page' => 4
	));

	// Get the IDs from the previous step
	$homepage_featured_and_top_story_posts_ids = array();
	foreach( $homepage_featured_and_top_story_posts as $post ) {
		$homepage_featured_and_top_story_posts_ids[] = $post->ID;
	}

	// Get the posts that are in "Homepage Featured" but not "Top Story"
	$homepage_featured_posts = get_posts(array(
		'tax_query' => array(
			array(
				'taxonomy' => 'prominence',
				'field' => 'term_id',
				'terms' => $homepage_feature_term->term_id
			)
		),
		'posts_per_page' => 4,
		'post__not_in' => $homepage_featured_and_top_story_posts_ids
	));

	if ( count($homepage_featured_and_top_story_posts) + count($homepage_featured_posts) > 1 ) {
		$posts = array_merge( $homepage_featured_and_top_story_posts, $homepage_featured_posts );
		$big_story = array_shift( $posts );
		$side_stories = array_splice( $posts, 0, 3 );
		$side_stories_display = 'articles';
	} else {
		$top_story_posts = get_posts(array(
			'tax_query' => array(
				array(
					'taxonomy' => 'prominence',
					'field' => 'term_id',
					'terms' => $top_story_term->term_id
				),
			),
			'posts_per_page' => 4
		));

		if ( count($top_story_posts) > 0 ) {
			$big_story = $top_story_posts[0];
		} elseif ( count($homepage_featured_posts) > 0 ) {
			$big_story = $homepage_featured_posts[0];
		} else {
			// the most recent
			$posts = get_posts( array(
				'orderby' => 'date',
				'order' => 'DESC',
				'posts_per_page' => 1
			) );

			if ( count($posts) > 0 ) {
				$big_story = $posts[0];
			}
		}

		if ( !empty( $big_story ) ) {
			$series_terms = wp_get_post_terms( $big_story->ID, 'series' );
			$category_terms = wp_list_filter( wp_get_post_terms( $big_story->ID, 'category' ), array( 'name' => $uncategorized_term->name ), 'NOT' );
			$tags_terms = wp_get_post_terms( $big_story->ID, 'post_tag' );

			$terms = array_merge( $series_terms, $category_terms, $tags_terms );
			$terms = array_reverse( $terms );

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

				if ( count($posts) >= count($side_stories) ) {
					$side_stories = $posts;
					$side_stories_display = 'series';
					$side_stories_term = $term;
				}
			}
		}
	}

	return array(
		'big_story' => $big_story,
		'side_stories' => $side_stories,
		'side_stories_display' => $side_stories_display,
		'side_stories_term' => $side_stories_term,
	);
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
			// array(
			// 	'taxonomy' => 'prominence',
			// 	'field' => 'term_id',
			// 	'terms' => $homepage_feature_term->term_id
			// ),
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


	// Get the posts that are in "Homepage Featured" but not "Top Story"
	$homepage_featured_posts = get_posts(array(
		'tax_query' => array(
			array(
				'taxonomy' => 'prominence',
				'field' => 'term_id',
				'terms' => $homepage_feature_term->term_id
			)
		),
		'posts_per_page' => 1,
		'post__not_in' => $homepage_featured_and_top_story_posts_ids
	));

	if ( !empty( $homepage_featured_posts ) ) {
		return $homepage_featured_posts[0];
	}


	// Get the most recent post
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