<?php

/**
 * Adds custom meta fields functionality to terms
 * Uses a custom post type as a proxy to bridge between a term_id and a post_meta field
 */

/**
 * Register the proxy post type
 */
function largo_register_term_meta_post_type() {
	register_post_type( '_term_meta', array(
		'public'     => false,
		'query_var'  => false,
		'rewrite'    => false,
		'supports'   => false,
	));
}
add_action( 'init', 'largo_register_term_meta_post_type' );

/**
 * Get the proxy post for a term
 *
 * @param string $taxnomy
 * @param int $term_id
 *
 * @return int $post_id
 */
function largo_get_term_meta_post( $taxonomy, $term_id ) {
	$query = new WP_Query( array(
		'post_type'      => '_term_meta',
		'posts_per_page' => 1,
		'post_status' => 'any',
		'tax_query'      => array(
			array(
				'taxonomy'         => $taxonomy,
				'field'            => 'id',
				'terms'            => $term_id,
				'include_children' => false
			)
		)
	));

	if ( $query->found_posts ) {
		return $query->posts[0]->ID;
	} else {
		$tax_input = array();
		$post_id = wp_insert_post( array( 'post_type' => '_term_meta', 'post_title' => "{$taxonomy}:${term_id}" ) );
		wp_set_post_terms( $post_id, array( (int) $term_id ), $taxonomy );
		return $post_id;
	}
}


/**
 * Add meta data to a term
 *
 * @param string $taxonomy
 * @param int $term_id
 * @param string $meta_key
 * @param mixed $meta_value
 * @param bool $unique
 */
function largo_add_term_meta( $taxonomy, $term_id, $meta_key, $meta_value, $unique=false ) {
	$post_id = largo_get_term_meta_post( $taxonomy, $term_id );
	return add_post_meta( $post_id, $meta_key, $meta_value, $unique );
}

/**
 * Delete meta data to a term
 *
 * @param string $taxonomy
 * @param int $term_id
 * @param string $meta_key
 * @param mixed $meta_value
 */
function largo_delete_term_meta( $taxonomy, $term_id, $meta_key, $meta_value='' ) {
	$post_id = largo_get_term_meta_post( $taxonomy, $term_id );
	return delete_post_meta( $post_id, $meta_key, $meta_value );
}

/**
 * Get meta data to a term
 *
 * @param string $taxonomy
 * @param int $term_id
 * @param string $meta_key
 * @param bool $single
 */
function largo_get_term_meta( $taxonomy, $term_id, $meta_key, $single=false ) {
	$post_id = largo_get_term_meta_post( $taxonomy, $term_id );
	return get_post_meta( $post_id, $meta_key, $single );
}

/**
 * Update meta data to a term
 *
 * @param string $taxonomy
 * @param int $term_id
 * @param string $meta_key
 * @param mixed $meta_value
 * @param mixed $prev_value
 */
function largo_update_term_meta( $taxonomy, $term_id, $meta_key, $meta_value, $prev_value='' ) {
	$post_id = largo_get_term_meta_post( $taxonomy, $term_id );
	return update_post_meta( $post_id, $meta_key, $meta_value, $prev_value );
}