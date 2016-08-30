<?php

/**
 * Shorthand for querying posts from a custom taxonomy
 * Used in homepage templates and sidebar widgets
 *
 * @param array $args query args
 * @return array of featured post objects
 * @since 0.3
 */
function largo_get_featured_posts( $args = array() ) {
    $defaults = array(
        'showposts' => 3,
        'offset' 	=> 0,
        'orderby' 	=> 'date',
        'order' 	=> 'DESC',
        'tax_query' => array(
			array(
				'taxonomy' 	=> 'prominence',
				'field' 	=> 'slug',
				'terms' 	=> 'footer-featured'
			)
		),
        'ignore_sticky_posts' => 1,
    );
    $args = wp_parse_args( $args, $defaults );
    $featured_query = new WP_Query( $args );
    wp_reset_postdata();
    return $featured_query;
}

/**
 * Provides the "main" feature associated with a post.
 * Expects to be called from within The Loop.
 *
 * @uses global $post
 * @return term object|false
 * @since 0.3
 */
function largo_get_the_main_feature( $featured = null ) {
	if ( $featured == null ) {
		global $post;
		$featured = $post;
	}
	$features = get_the_terms( $featured->ID, 'series' );
	if ( ! $features )
		return false;
	return array_shift( $features );
}

/**
 * If a post is marked as sticky, this unsticks any other posts on the blog
 * so that we only have one sticky post at a time.
 *
 * If this ever breaks, #blamenacin.
 *
 * @param array $after new list of sticky posts
 * @param array $before original list of sticky posts
 * @return array
 * @since 1.0
 */
function largo_scrub_sticky_posts( $after, $before ) {
    $newest_post_id = array_pop( $after );

    return array( $newest_post_id );
}
add_filter( 'pre_update_option_sticky_posts', 'largo_scrub_sticky_posts', 10, 2 );


/**
 * Determine if we have any 'featured' posts on archive pages
 */
function largo_have_featured_posts() {

	if ( is_category() || is_tax() || is_tag() ) {
		$obj = get_queried_object();

		$featured_query = array(
			'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy' => $obj->taxonomy,
					'field' => 'slug',
					'terms' => $obj->slug,
				),
				array(
					'taxonomy' => 'prominence',
					'field' => 'slug',
					'terms' => array( 'taxonomy-featured', 'taxonomy-secondary-featured' ),
				)
			)
		);
		$featured_query = new WP_Query( $featured_query );
		return $featured_query->have_posts();
	}

	return false;

}

/**
 * Determine if we have any 'featured' posts on homepage
 */
function largo_have_homepage_featured_posts() {

	$featured_query = array(
		'tax_query' => array(
			array(
				'taxonomy' => 'prominence',
				'field' => 'slug',
				'terms' => array( 'taxonomy-featured', 'homepage-featured' ),
			)
		)
	);
	$featured_query = new WP_Query( $featured_query );
	return $featured_query->have_posts();

}

/**
 * Get posts marked as "Featured in category" for a given category name.
 *
 * @param string $category_name the category to retrieve featured posts for.
 * @param integer $number total number of posts to return, backfilling with regular posts as necessary.
 * @since 0.5
 */
function largo_get_featured_posts_in_category( $category_name, $number = 5 ) {
	$args = array(
		'category_name' => $category_name,
		'numberposts' => $number,
		'post_status' => 'publish',
	);

	$tax_query = array(
		'tax_query' => array(
			array(
				'taxonomy' => 'prominence',
				'field' => 'slug',
				'terms' => 'category-featured',
			)
		)
	);

	// Get the featured posts
	$featured_posts = get_posts( array_merge( $args, $tax_query ) );

	// Backfill with regular posts if necessary
	if ( count( $featured_posts ) < (int) $number ) {
		$needed = (int) $number - count( $featured_posts );
		$regular_posts = get_posts( array_merge( $args, array(
			'numberposts' => $needed,
			'post__not_in' => array_map( function( $x ) { return $x->ID; }, $featured_posts )
		)));
		$featured_posts = array_merge( $featured_posts, $regular_posts );
	}

	return $featured_posts;
}

/**
 * Helper for getting posts in a category archive, excluding featured posts.
 * 
 * @param WP_Query $query
 * @uses largo_get_featured_posts_in_category
 * @since 0.4
 */
function largo_category_archive_posts( $query ) {
	//don't muck with admin, non-categories, etc
	if ( ! $query->is_category() || ! $query->is_main_query() || is_admin() ) return;

	// If this has been disabled by an option, do nothing
	if ( of_get_option( 'hide_category_featured' ) == true ) return;

	// get the featured posts
	$featured_posts = largo_get_featured_posts_in_category( $query->get( 'category_name' ) );

	// get the IDs from the featured posts
	$featured_post_ids = array();
	foreach ( $featured_posts as $fpost )
		$featured_post_ids[] = $fpost->ID;

	$query->set( 'post__not_in', $featured_post_ids );
}
add_action( 'pre_get_posts', 'largo_category_archive_posts', 15 );