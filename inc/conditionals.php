<?php
/**
 * Various conditional checks for functionality that
 * Largo extends.
 * 
 * @since 0.5
 */

/**
 * Is this a series landing page?
 * 
 * @since 0.5
 * @see inc/wp-taxonomy-landing/*
 * 
 * @param int|WP_Post $post Optional. Post ID or post object. Default is global $post.
 * @return bool Whether the post is a series landing page.
 */
function largo_is_series_landing( $post ) {

	$post = get_post( $post );

	return ( $post->post_type == 'cftl-tax-landing' );

}