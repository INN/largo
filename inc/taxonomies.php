<?php

/**
 * Register the prominence and series custom taxonomies
 * Insert the default terms
 *
 * @since 1.0
 */
function largo_custom_taxonomies() {
    // PROMINENCE
    if ( ! taxonomy_exists( 'prominence' ) ) {
        register_taxonomy( 'prominence', 'post', array(
            'hierarchical' 	=> true,
            'label' 		=> __('Post Prominence', 'largo'),
            'query_var' 	=> true,
            'rewrite' 		=> true,
        ) );

        wp_insert_term(
        	__('Homepage Featured', 'largo'), 'prominence',
        	array(
        		'description' 	=> __('If you are using the Newspaper or Carousel optional homepage layout, add this label to posts to display them in the featured area on the homepage', 'largo'),
        		'slug' 			=> 'homepage-featured'
        	)
        );

        wp_insert_term(
        	__('Sidebar Featured Widget', 'largo'),	'prominence',
        	array(
        		'description' 	=> __('If you are using the Sidebar Featured Posts widget, add this label to posts to determine which to display in the widget', 'largo'),
        		'slug' 			=> 'sidebar-featured'
        	)
        );

        wp_insert_term(
        	__('Footer Featured Widget', 'largo'), 'prominence',
        	array(
        		'description' 	=> __('If you are using the Footer Featured Posts widget, add this label to posts to determine which to display in the widget', 'largo'),
        		'slug' 			=> 'footer-featured'
        	)
        );

        //check to make sure top story doesn't exist yet (for some reason it sometimes creates a duplicate term without this check) and then insert it
        $term = term_exists('Top Story', 'prominence');
        if ( $term == 0 || $term == null ) {
		    $parent_term = term_exists( 'Homepage Featured', 'prominence' );
		    $parent_term_id = $parent_term['term_id'];
		    wp_insert_term(
		    	__('Top Story', 'largo'), 'prominence',
		    	array(
		    		'parent'		=> $parent_term_id,
		    		'description' 	=> __('If you are using the Newspaper or Carousel optional homepage layout, add this label to a post to make it the top story on the homepage', 'largo'),
		    		'slug' 			=> 'top-story' ) );
		}
		//fixes a WP bug where the child terms won't display in the admin even though they exist
		delete_option("prominence_children");
    }

    // SERIES
    if ( ! taxonomy_exists( 'series' ) ) {
        register_taxonomy( 'series', 'post', array(
            'hierarchical' 	=> true,
            'label' 		=> __('Series', 'largo'),
            'query_var' 	=> true,
            'rewrite' 		=> true,
        ) );
    }
}
add_action( 'init', 'largo_custom_taxonomies' );

/**
 * Determines whether a post is in a series
 * Expects to be called from within The Loop.
 *
 * @uses global $post
 * @return bool
 * @since 1.0
 */
function largo_post_in_series() {
    global $post;
    $features = get_the_terms( $post->ID, 'series' );
    return ( $features ) ? true : false;
}

/**
 * Outputs a list of series a post is in
 * Called in content-single.php
 *
 * @since 1.0
 */
if ( ! function_exists( 'largo_the_series_list' ) ) {
	function largo_the_series_list() {
		global $post;
		$post_terms = largo_custom_taxonomy_terms( $post->ID );
		foreach ( $post_terms as $term ) {
			if ( strtolower( $term->name ) == 'series' )
				continue;
			echo largo_term_to_label( $term );
		}
	}
}

/**
 * Outputs custom taxonomy terms attached to a post
 *
 * @return array of terms
 * @since 1.0
 */
function largo_custom_taxonomy_terms( $post_id ) {
	$taxonomies = apply_filters( 'largo_custom_taxonomies', array( 'series' ) );
	$post_terms = array();
	foreach ( $taxonomies as $tax ) {
		if ( taxonomy_exists( $tax ) ) {
			$terms = get_the_terms( $post_id, $tax );
			if ( $terms )
				$post_terms = array_merge( $post_terms, $terms );
		}
	}
	return $post_terms;
}

/**
 * Output format for the series custom taxonomy at the bottom of single posts
 *
 * @param $term array the term we want to output
 * @since 1.0
 */
if ( ! function_exists( 'largo_term_to_label' ) ) {
	function largo_term_to_label( $term ) {
	    return sprintf( '<div class="series-label"><h5><a href="%1$s">%2$s</a><a class="rss-link" href="%3$s"></a></h5><p>%4$s</p></div>',
	    	get_term_link( $term, $term->taxonomy ),
	    	esc_attr( $term->name ),
	    	get_term_feed_link( $term->term_id, $term->taxonomy ),
	    	esc_attr( strip_tags ( $term->description )
	    ));
	}
}