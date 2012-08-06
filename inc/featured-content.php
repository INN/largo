<?php

function largo_tag_featured_posts( $post_id, $post ) {
    $terms = wp_get_post_terms( $post_id, 'prominence', array("fields" => "names") );
    if (in_array('Footer Featured Widget', $terms)) {
        add_post_meta( $post_id, 'footer_featured_widget', 'yes', true ) or
            update_post_meta( $post_id, 'footer_featured_widget', 'yes' );
    }
    else {
        delete_post_meta( $post_id, 'footer_featured_widget' );
    }
}
add_action( 'publish_post', 'largo_tag_featured_posts', 10, 2 );

function largo_get_featured_posts( $args = array() ) {
    $defaults = array(
        'showposts' => 3,
        'offset' => 0,
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_key' => 'featured',
        'meta_value' => 'yes',
        'ignore_sticky_posts' => 1,
    );
    $args = wp_parse_args( $args, $defaults );
    $featured_query = new WP_Query( $args );
    return $featured_query;
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
 */
function largo_scrub_sticky_posts( $after, $before ) {
    $newest_post_id = array_pop( $after );

    return array( $newest_post_id );
}
add_filter( 'pre_update_option_sticky_posts', 'largo_scrub_sticky_posts', 10, 2 );


/**
 * Determines whether a post has a "main" item from the feature custom
 * taxonomy. Expects to be called from within The Loop.
 *
 * @uses global $post
 * @return bool
 */
function largo_post_in_series() {
    global $post;

    $features = get_the_terms( $post->ID, 'series' );

    return ( $features ) ? true : false;
}

/**
 * Provides the "main" feature associated with a post.
 *
 * @uses global $post
 * @return term object|false
 */
function largo_get_the_main_feature() {
    global $post;

    $features = get_the_terms( $post->ID, 'series' );

    if ( ! $features )
        return false;

    return array_shift( $features );
}

// add a link to an archive page for terms within the "featured" taxonomy to post meta.

if ( ! function_exists( 'largo_custom_taxonomy_terms' ) ) :
function largo_custom_taxonomy_terms( $post_id ) {
	$taxonomies = apply_filters( 'largo_custom_taxnomies', array( 'series' ) );

    $post_terms = array();
    foreach ( $taxonomies as $tax ) {
        if ( taxonomy_exists( $tax ) ) {
            $terms = get_the_terms( $post_id, $tax );
            if ( $terms ) {
                $post_terms = array_merge( $post_terms, $terms );
            }
        }
    }

    return $post_terms;
}
endif;

function largo_term_to_label( $term ) {
    return sprintf( '<li> <a href="%1$s">%2$s</a></li>',
                    get_term_link( $term, $term->taxonomy ),
                    strtoupper( $term->name ) );
}

if ( ! function_exists( 'largo_the_post_labels' ) ) :
function largo_the_post_labels( $post_id ) {
    $post_terms = largo_custom_taxonomy_terms( $post_id );
    $all_labels = $post_terms;
    foreach ( $all_labels as $term ) {
        if ( strtolower( $term->name ) == 'featured' ) {
            continue;
        }
        echo argo_term_to_label( $term );
    }
}
endif;

//new to argo parent
if ( ! function_exists( 'largo_has_custom_taxonomy' ) ) :
	function largo_has_custom_taxonomy($post_id) {
		$largo_has_terms = largo_custom_taxonomy_terms( $post_id );
		if ($largo_has_terms) {
        	return true;
		}

		return false;
		}
endif;