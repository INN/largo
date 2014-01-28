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

		$prominence_terms = array(
			array(
				'name' 			=> __('Homepage Featured', 'largo'),
				'description' 	=> __('If you are using the Newspaper or Carousel optional homepage layout, add this label to posts to display them in the featured area on the homepage.', 'largo'),
				'slug' 			=> 'homepage-featured'
			),
			array(
				'name' 			=> __('Sidebar Featured Widget', 'largo'),
				'description' 	=> __('If you are using the Sidebar Featured Posts widget, add this label to posts to determine which to display in the widget.', 'largo'),
				'slug' 			=> 'sidebar-featured'
			),
			array(
				'name' 			=> __('Footer Featured Widget', 'largo'),
				'description' 	=> __('If you are using the Footer Featured Posts widget, add this label to posts to determine which to display in the widget.', 'largo'),
				'slug' 			=> 'footer-featured'
			),
			array(
				'name' 			=> __('Featured in Series', 'largo'),
				'description' 	=> __('Select this option to allow this post to float to the top of any/all series landing pages sorting by Featured first.', 'largo'),
				'slug' 			=> 'series-featured'
			),
			array(
				'name' 			=> __('Featured in Category', 'largo'),
				'description' 	=> __('Not yet implemented, in the future this will allow you to designate a story (or stories) to appear more prominently on category archive pages.', 'largo'),
				'slug' 			=> 'category-featured'
			)
		);
		foreach ( $prominence_terms as $term ) {
			if ( ! term_exists( $term['name'], 'prominence' ) ) {
				wp_insert_term(
					$term['name'], 'prominence',
					array(
						'description' 	=> $term['description'],
						'slug' 			=> $term['slug']
					)
				);
			}
		}

        if ( ! term_exists('Top Story', 'prominence') ) {
		    $parent_term = term_exists( 'Homepage Featured', 'prominence' );
		    $parent_term_id = $parent_term['term_id'];
		    wp_insert_term(
		    	__('Top Story', 'largo'), 'prominence',
		    	array(
		    		'parent'		=> $parent_term_id,
		    		'description' 	=> __('If you are using the Newspaper or Carousel optional homepage layout, add this label to a post to make it the top story on the homepage', 'largo'),
		    		'slug' 			=> 'top-story' )
		    	);
		}

		delete_option( 'prominence_children' );
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

/**
 * Helper function for getting posts in proper landing-page order for a series
 * @param integer series term id
 & @param inteter number of posts to fetch, defaults to all
 */
function largo_get_series_posts( $series_id, $number = -1 ) {

	// get the cf-tax-landing
	$args = array(
		'post_type' => 'cftl-tax-landing',
		'posts_per_page' => 1,
		'tax_query' => array( array(
			'taxonomy' => 'series',
			'field' => 'id',
			'terms' => $series_id
		)),
	);
	$landing = new WP_Query( $args );

	$series_args = array(
		'post_type' 		=> 'post',
		'tax_query' => array(
			array(
				'taxonomy' => 'series',
				'field' => 'id',
				'terms' => $series_id
			)
		),
		'order' 			=> 'DESC',
		'orderby' 		=> 'date',
		'posts_per_page' 	=> $number
	);

	if ( $landing->found_posts ) {
		$landing->next_post();
		$order = get_post_meta( $landing->post->ID, 'post_order', TRUE );
		switch ( $order ) {
			case 'ASC':
				$series_args['order'] = 'ASC';
				break;
			case 'custom':
				$series_args['orderby'] = 'series_custom';
				break;
			case 'featured, DESC':
			case 'featured, ASC':
				$series_args['orderby'] = $order;
				break;
		}
	}

	$series_posts = new WP_Query( $series_args );

	if ( $series_posts->found_posts ) return $series_posts;

	return false;

}