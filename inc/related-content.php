<?php

/**
 * Show related tags and subcategories for each main category
 * Used on category.php to display a list of related terms
 *
 * @since 1.0
 */

function largo_get_related_topics_for_category( $obj ) {
    $MAX_RELATED_TOPICS = 5;

    if (!isset($obj->post_type)) {
    	$obj->post_type = 0;
    }

    if ( $obj->post_type ) {
        if ( $obj->post_type == 'nav_menu_item' ) {
            $cat_id = $obj->object_id;
        }

    }else {
    $cat_id = $obj->cat_ID;
    }

    $out = "<ul>";

    // spit out the subcategories
    $cats = _subcategories_for_category( $cat_id );

    foreach ( $cats as $c ) {
        $out .= sprintf( '<li><a href="%s">%s</a></li>',
            get_category_link( $c->term_id ), $c->name
        );
    }

    if ( count( $cats ) < $MAX_RELATED_TOPICS ) {
        $tags = _tags_associated_with_category( $cat_id,
            $MAX_RELATED_TOPICS - count( $cats ) );

        foreach ( $tags as $t ) {
            $out .= sprintf( '<li><a href="%s">%s</a></li>',
                get_tag_link( $t->term_id ), $t->name
            );
        }
    }

    $out .= "</ul>";
    return $out;
}

function _tags_associated_with_category( $cat_id, $max = 5 ) {
    $query = new WP_Query( array(
        'posts_per_page'         => 100,
        'cat'                    => $cat_id,
        'update_post_meta_cache' => false,
        'no_found_rows'          => true,
    ) );

    // Get a list of the tags used in posts in this category.
    $tags = array();
    $tag_objs = array();

    foreach ( $query->posts as $post ) {
        $ptags = get_the_tags( $post->ID );
        if ( $ptags ) {
            foreach ( $ptags as $tag ) {
                if (isset($tags[$tag->term_id])) {
                	$tags[ $tag->term_id ]++;
                } else {
                	$tags[ $tag->term_id ] = 0;
                }
                $tag_objs[ $tag->term_id ] = $tag;
            }
        }
    }

    // Sort the most popular and get the $max results, or all results
    // if max is -1
    arsort( $tags, SORT_NUMERIC );
    if ( $max == -1 ) {
        $tag_keys = array_keys( $tags );
    }
    else {
        $tag_keys = array_splice( array_keys( $tags ), 0, $max );
    }

    // Create an array of the selected tag objects
    $return_tags = array();
    foreach ( $tag_keys as $tk ) {
        array_push( $return_tags, $tag_objs[ $tk ] );
    }

    return $return_tags;
}

function _subcategories_for_category( $cat_id ) {
    // XXX: could also use get_term_children().  not sure which is better.
    $cats = get_categories( array(
        'child_of' => $cat_id,
    ) );

    return $cats;
}

/**
 * Provides topics (categories and tags) related to the current post in The
 * Loop.
 *
 * @param int $max The maximum number of topics to return.
 * @return array of term objects.
 * @since 1.0
 */
function largo_get_post_related_topics( $max = 5 ) {
    $cats = get_the_category();
    $tags = get_the_tags();

    $topics = array();
    if ( $cats ) {
        foreach ( $cats as $cat ) {
            if ( $cat->name == 'Uncategorized' ) {
                continue;
            }
            $posts = largo_get_recent_posts_for_term( $cat, 3, 2 );
            if ( $posts ) {
                $topics[] = $cat;
            }
        }
    }

    if ( $tags ) {
        foreach ( $tags as $tag ) {
            $posts = largo_get_recent_posts_for_term( $tag, 3, 2 );
            if ( $posts ) {
                $topics[] = $tag;
            }
        }
    }

    $topics = apply_filters( 'largo_get_post_related_topics', $topics, $max );

    return array_slice( $topics, 0, $max );
}

/**
 * Provides the recent posts for a term object (category, post_tag, etc).
 * @uses global $post
 * @param object    $term   A term object.
 * @param int       $max    Maximum number of posts to return.
 * @param int       $min    Minimum number of posts. If not met, returns false.
 * @return array|false of post objects.
 * @since 1.0
 */
function largo_get_recent_posts_for_term( $term, $max = 5, $min = 1 ) {
    global $post;

    $query_args = array(
        'showposts' 			=> $max,
        'orderby' 				=> 'date',
        'order' 				=> 'DESC',
        'ignore_sticky_posts' 	=> 1,
    );

    // Exclude the current post if we're inside The Loop
    if ( $post ) {
        $query_args[ 'post__not_in' ] = array( $post->ID );
    }

    if ( $term->taxonomy == 'post_tag' ) {
        // have to use tag__in because tag_id doesn't seem to work.
        $query_args[ 'tag__in' ] = array( $term->term_id );
    }
    elseif ( $term->taxonomy == 'category' ) {
        $query_args[ 'cat' ] = $term->term_id;
    }
    elseif ( $term->taxonomy == 'series' ) {
        $query_args[ 'series' ] = $term->slug;
    }

		//if this is a fake term, just grab post ids
		if ( $term->term_id == -90 && $post ) {
			$post_ids = preg_split( '#\s*,\s*#', get_post_meta( $post->ID, 'largo_custom_related_posts', true ) );
			$query_args[ 'post__in' ] = $post_ids;
			$query_args[ 'orderby' ] = 'post__in';
			$query_args['showposts'] = count($post_ids);
		}

    $query_args = apply_filters( 'largo_get_recent_posts_for_term_query_args', $query_args, $term, $max, $min, $post );

    $query = new WP_Query( $query_args );

    if ( count( $query->posts ) < $min ) {
        return false;
    }

    return $query->posts;
}

/**
 * Determine if a post has either categories or tags
 *
 * @return bool true is a post has categories or tags
 * @since 1.0
 */
function largo_has_categories_or_tags() {
    if ( get_the_tags() ) {
        return true;
    }

    $cats = get_the_category();
    if ( $cats ) {
        foreach ( $cats as $cat ) {
            if ( $cat->name != 'Uncategorized' ) {
                return true;
            }
        }
    }

    return false;
}

/**
 * Return (or echo) a list of categories and tags
 *
 * @param $max int number of categories and tags to return
 * @param $echo bool echo the output or return it (default: echo)
 * @param $link bool return the tags and category links or just the terms themselves
 * @param $use_icon bool include the tag icon or not (used on single.php)
 * @param $separator string to use as a separator between list items
 * @param $item_wrapper string html tag to use as a wrapper for elements in the output
 * @param $exclude array of term ids to exclude
 * @return array of category and tag links
 * @since 1.0
 * @todo consider prioritizing tags by popularity?
 */
if ( ! function_exists( 'largo_categories_and_tags' ) ) {
	function largo_categories_and_tags( $max = 5, $echo = true, $link = true, $use_icon = false, $separator = ', ', $item_wrapper = 'span', $exclude = array(), $rss = false ) {
	    $cats = get_the_category();
	    $tags = get_the_tags();
	    $icon = '';
	    $output = array();

	    // if $use_icon is true, include the markup for the tag icon
	    if ( $use_icon === true )
	    	$icon = '<i class="icon-white icon-tag"></i>';
        elseif ( $use_icon )
            $icon = '<i class="icon-white icon-'.esc_attr($use_icon).'"></i>';

	    if ( $cats ) {
	        foreach ( $cats as $cat ) {

	            // skip uncategorized and any others in the array of terms to exclude
	            if ( $cat->name == 'Uncategorized' || in_array( $cat->term_id, $exclude ) )
	                continue;

	            if ( $link ) {
		            $output[] = sprintf(
		                __('<%1$s class="post-category-link"><a href="%2$s" title="Read %3$s in the %4$s category">%5$s%4$s</a></%1$s>', 'largo'),
			                $item_wrapper,
			                ( $rss ? get_category_feed_link( $cat->term_id ) : get_category_link( $cat->term_id ) ),
			                of_get_option( 'posts_term_plural' ),
			                $cat->name,
			                $icon
		            );
		       } else {
			       $output[] = $cat->name;
		       }
	        }
	    }

	    if ( $tags ) {
	        foreach ( $tags as $tag ) {

	        	if ( in_array( $tag->term_id, $exclude ) )
	                continue;

	        	if ( $link ) {
		            $output[] = sprintf(
		                __('<%1$s class="post-tag-link"><a href="%2$s" title="Read %3$s tagged with: %4$s">%5$s%4$s</a></%1$s>', 'largo'),
		                	$item_wrapper,
		                	( $rss ?  get_tag_feed_link( $tag->term_id ) : get_tag_link( $tag->term_id ) ),
		                	of_get_option( 'posts_term_plural' ),
		                	$tag->name,
		                	$icon
		            );
		         } else {
		         	 $output[] = $tag->name;
		       }
	        }
	    }

	    if ( $echo )
			echo implode( $separator, array_slice( $output, 0, $max ) );

		return $output;
	}
}

/**
 * Returns (and optionally echoes) the 'top term' for a post, falling back to a category if one wasn't specified
 *
 * @param array|string $options Settings for post id, echo, link, use icon, wrapper and exclude
 */
function largo_top_term( $options = array() ) {

	global $wpdb;

	$defaults = array(
		'post' => get_the_ID(),
		'echo' => TRUE,
		'link' => TRUE,
		'use_icon' => FALSE,
		'wrapper' => 'span',
		'exclude' => array(),	//only for compatibility with largo_categories_and_tags
	);

	$args = wp_parse_args( $options, $defaults );

	$term_id = get_post_meta( $args['post'], 'top_term', TRUE );
	//get the taxonomy slug
	$taxonomy = $wpdb->get_var( $wpdb->prepare( "SELECT taxonomy FROM $wpdb->term_taxonomy WHERE term_id = %d LIMIT 1", $term_id) );

	if ( empty( $term_id ) || empty($taxonomy) ) {	// if no top_term specified, fall back to the first category
		$term_id = get_the_category( $args['post'] );
		if ( !is_array( $term_id ) || !count($term_id) ) return;	//no categories OR top term? Do nothing
		$term_id = $term_id[0]->term_id;
	}

	if ( $term_id ) {
		$icon = ( $args['use_icon'] ) ?  '<i class="icon-white icon-tag"></i>' : '' ;	//this will probably change to a callback largo_term_icon() someday
		$link = ( $args['link'] ) ? array('<a href="%2$s" title="Read %3$s in the %4$s category">','</a>') : array('', '') ;
		// get the term object
		$term = get_term( $term_id, $taxonomy );
		if (is_wp_error($term)) return;
		$output = sprintf(
			'<%1$s class="post-category-link">'.$link[0].'%5$s%4$s'.$link[1].'</%1$s>',
			$args['wrapper'],
			get_term_link( $term ),
			of_get_option( 'posts_term_plural' ),
			$term->name,
			$icon
		);
	} else {
		$output = largo_categories_and_tags( 1, false, $args['link'], $args['use_icon'], '', $args['wrapper'], $args['exclude']);
		$output = ( is_array($output) ) ? $output[0] : '';
	}
	if ( $args['echo'] ) echo $output;
	return $output;
}

/**
 *
 */
function largo_filter_get_post_related_topics( $topics, $max ) {
    $post = get_post();
    if ( $post ) {
        $posts = preg_split( '#\s*,\s*#', get_post_meta( $post->ID, 'largo_custom_related_posts', true ) );
        if ( !empty( $posts[0] ) ) {
            // Add a fake term with the ID of -90
            $top_posts = new stdClass();
            $top_posts->term_id = -90;
            $top_posts->name = __( 'Top Posts', 'largo' );
            array_unshift( $topics, $top_posts );
        }
    }

    return $topics;
}
add_filter( 'largo_get_post_related_topics', 'largo_filter_get_post_related_topics', 10, 2 );


/**
 *
 */
function largo_filter_get_recent_posts_for_term_query_args( $query_args, $term, $max, $min, $post ) {

    if ( $term->term_id == -90 ) {
        $posts = preg_split( '#\s*,\s*#', get_post_meta( $post->ID, 'largo_custom_related_posts', true ) );
        $query_args = array(
            'showposts'             => $max,
            'orderby'               => 'post__in',
            'order'                 => 'ASC',
            'ignore_sticky_posts'   => 1,
            'post__in'              => $posts,
        );
    }

    return $query_args;
}
add_filter( 'largo_get_recent_posts_for_term_query_args', 'largo_filter_get_recent_posts_for_term_query_args', 10, 5 );


/**
 * The Largo Related class.
 * Used to dig through posts to find IDs related to the current post
 */
class Largo_Related {

	var $number;
	var $post_id;
	var $post_ids = array();
	var $post;

	/**
	 * Constructor.
	 * Sets up essential parameters for retrieving related posts
	 *
	 * @access public
	 *
	 * @param integer $number optional The number of post IDs to fetch. Defaults to 1
	 * @param integer $post_id optional The ID of the post to get related posts about. If not provided, defaults to global $post
	 * @return null
	 */
	function __construct( $number = 1, $post_id = '' ) {

		if ( ! empty( $number ) ) {
			$this->number = $number;
		}

		if ( ! empty( $post_id ) ) {
			$this->post_id = $post_id;
		} else {
			$this->post_id = get_the_ID();
		}

		$this->post = get_post($this->post_id);
	}

	/**
	 * Array sorter for organizing terms by # of posts they have
	 *
	 * @param object $a First WP term object
	 * @param object $b Second WP term object
	 * @return integer
	 */
	function popularity_sort( $a, $b ) {
		if ( $a->count == $b->count ) return 0;
		return ( $a->count < $b->count ) ? -1 : 1;
	}

	/**
	 * Performs cleanup of IDs list prior to returning it. Also applies a filter.
	 *
	 * @access protected
	 *
	 * @return array The final array of related post IDs
	 */
	protected function cleanup_ids() {
		//make things unique just to be safe
		$ids = array_unique( $this->post_ids );

		//truncate to desired length
		$ids = array_slice( $ids, 0, $this->number );

		//run filters
		return apply_filters( 'largo_related_posts', $ids );
	}

	/**
	 * Fetches posts contained within the series(es) this post resides in. Feeds them into $this->post_ids array
	 *
	 * @access protected
	 */
	protected function get_series_posts() {

		//try to get posts by series, if this post is in a series
		$series = get_the_terms( $this->post_id, 'series' );

		if ( is_array($series) ) {

			//loop thru all the series this post belongs to
			foreach ( $series as $term ) {
				//start to build our query of posts in this series
				// get the posts in this series, ordered by rank or (if missing?) date
				$args = array(
					'post_type' => 'post',
					'posts_per_page' => $this->number,
					'taxonomy' => 'series',
					'term' => $term->slug,
					'orderby' => 'date',
					'order' => 'ASC',
					'ignore_sticky_posts' => 1,
					'date_query' => array(
						'after' => $this->post->post_date,
					),
				);

				// see if there's a post that has the sort order info for this series
				$cftl_query = new WP_Query( array(
					'post_type' => 'cftl-tax-landing',
					'series' => $term->slug,
					'posts_per_page' => 1
				));

				if ( $cftl_query->have_posts() ) {
					$cftl_query->next_post();
					$has_order = get_post_meta( $cftl_query->post->ID, 'post_order', TRUE );
					if ( !empty($has_order) ) {
						switch ( $has_order ) {
							case 'ASC':
								$args['order'] = 'ASC';
								break;
							case 'custom':
								$args['orderby'] = 'series_custom';
								break;
							case 'featured, DESC':
							case 'featured, ASC':
								$args['orderby'] = $opt['post_order'];
								break;
						}
					}
				}

				// build the query with the sort defined
				$series_query = new WP_Query( $args );

				if ( $series_query->have_posts() ) {
					$this->add_from_query( $series_query );
					if ( $this->have_enough_posts() ) {
						break;
					}
				}
			}
		}
	}

	/**
	 * Fetches posts contained within the categories and tags this post has. Feeds them into $this->post_ids array
	 *
	 * @access protected
	 */
	protected function get_term_posts() {

		//we've gone back and forth through all the post's series, now let's try traditional taxonomies
		$taxonomies = get_the_terms( $this->post_id, array('category', 'post_tag') );

		//loop thru taxonomies, much like series, and get posts
		if ( count($taxonomies) ) {
			//sort by popularity
			usort( $taxonomies, array(__CLASS__, 'popularity_sort' ) );

			foreach ( $taxonomies as $term ) {
				$args = array(
					'post_type' => 'post',
					'posts_per_page' => $this->number,
					'taxonomy' => $term->taxonomy,
					'term' => $term->slug,
					'orderby' => 'date',
					'order' => 'ASC',
					'ignore_sticky_posts' => 1,
					'date_query' => array(
						'after' => $this->post->post_date,
					),
				);

				// run the query
				$term_query = new WP_Query( $args );

				if ( $term_query->have_posts() ) {
					$this->add_from_query( $term_query );
					if ( $this->have_enough_posts() ) {
						break;
					}
				}
			}
		}
	}

	/**
	 * Fetches recent posts. Used as a fallback when other methods have failed to fill post_ids to requested length
	 *
	 * @access protected
	 */
	protected function get_recent_posts() {

		$args = array(
			'post_type' => 'post',
			'posts_per_page' => $this->number,
			'post__not_in' => array( $this->post_id ),
		);

		$posts_query = new WP_Query( $args );

		if ( $posts_query->have_posts() ) {
			$this->add_from_query($posts_query);
		}
	}

	/**
	 * Loops through series, terms and recent to fill array of related post IDs. Primary means of using this class.
	 *
	 * @access public
	 *
	 * @return array An array of post ids related to the given post
	 */
	public function ids() {

		//see if this post has manually set related posts
		$post_ids = get_post_meta( $this->post_id, 'largo_custom_related_posts', true );
		if ( ! empty( $post_ids ) ) {
			$this->post_ids = explode( ",", str_replace(' ', '', $post_ids) );
			if ( $this->have_enough_posts() ) {
				return $this->cleanup_ids();
			}
		}

		$this->get_series_posts();
		//are we done yet?
		if ( $this->have_enough_posts() ) return $this->cleanup_ids();

		$this->get_term_posts();
		//are we done yet?
		if ( $this->have_enough_posts() ) return $this->cleanup_ids();

		$this->get_recent_posts();
		return $this->cleanup_ids();
	}

	/**
	 * Takes a WP_Query result and adds the IDs to $this->post_ids
	 *
	 * @access protected
	 *
	 * @param object a WP_Query object
	 * @param boolean optional whether the query post order has been reversed yet. If not, this will loop through in both directions.
	 */
	protected function add_from_query( $q, $reversed = FALSE ) {
		// don't pick up anything until we're past our own post
		$found_ours = FALSE;

		while ( $q->have_posts() ) {
			$q->the_post();
			// add this post if it's new
			if ( ! in_array( $q->post->ID, $this->post_ids ) ) {	// only add it if it wasn't already there
				$this->post_ids[] = (int) trim($q->post->ID);
				// stop if we have enough
				if ( $this->have_enough_posts() ) return;
			}
		}
	}

	/**
	 * Counts to see if enough posts have been found
	 */
	protected function have_enough_posts() {
		if ( count( $this->post_ids ) >= $this->number )
			return true;

		return false;
	}
}
