<?php

/**
 * Shorthand for querying posts from a custom taxonomy
 * Used in homepage templates and sidebar widgets
 *
 * @param array $args query args
 * @return array of featured post objects
 * @since 1.0
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
 * @since 1.0
 */
function largo_get_the_main_feature() {
  global $post;
  $features = get_the_terms( $post->ID, 'series' );
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