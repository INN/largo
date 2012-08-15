<?php

/**
 *
 * Show related tags and subcategories for each main category
 *

 if (isset($tags[$tag->term_id])) {
                	$tags[ $tag->term_id ]++;
                } else {
                	$tags[ $tag->term_id ] = 0;
                }
                $tag_objs[ $tag->term_id ] = $tag;
endif;

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
        'posts_per_page' => -1,
        'cat' => $cat_id,
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
 * Builds links to the latest posts for a given category.
 * @param   object  $cat    Term object
 * @return  string
 */
function largo_get_latest_posts_for_category( $cat ) {
    $query = new WP_Query( array(
        'showposts' => 4,
        'orderby' => 'date',
        'order' => 'DESC',
        'cat' => $cat->object_id,
        'ignore_sticky_posts' => 1,
    ) );

    $output = '';
    foreach ( $query->posts as $post ) {
        $output .= '<h4><a href="' . get_permalink( $post->ID ) . '">' . $post->post_title . '</a></h4>';
    }

    return $output;
}

/**
 * Provides topics (categories and tags) related to the current post in The
 * Loop.
 *
 * @param int $max The maximum number of topics to return.
 * @return array of term objects.
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

    return array_slice( $topics, 0, $max );
}

/**
 * Provides the recent posts for a term object (category, post_tag, etc).
 * @uses global $post
 * @param object    $term   A term object.
 * @param int       $max    Maximum number of posts to return.
 * @param int       $min    Minimum number of posts. If not met, returns false.
 * @return array|false of post objects.
 */
function largo_get_recent_posts_for_term( $term, $max = 5, $min = 1 ) {
    global $post;

    $query_args = array(
        'showposts' => $max,
        'orderby' => 'date',
        'order' => 'DESC',
        'ignore_sticky_posts' => 1,
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

    $query = new WP_Query( $query_args );

    if ( count( $query->posts ) < $min ) {
        return false;
    }

    return $query->posts;
}

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
if ( ! function_exists( 'largo_the_categories_and_tags' ) ) {
	function largo_the_categories_and_tags() {
	    $cats = get_the_category();
	    $tags = get_the_tags();

	    $links = array();
	    if ( $cats ) {
	        foreach ( $cats as $cat ) {
	            if ( $cat->name == 'Uncategorized' ) {
	                continue;
	            }
	            $links[] = sprintf(
	                '<li class="post-category-link"><i class="icon-white icon-tag"></i><a href="%s" title="%s">%s</a></li>',
	                get_category_link( $cat->term_id ), $cat->name,
	                $cat->name
	            );
	        }
	    }
	    if ( $tags ) {
	        foreach ( $tags as $tag ) {
	            $links[] = sprintf(
	                '<li class="post-tag-link"><i class="icon-white icon-tag"></i><a href="%s" title="%s">%s</a></li>',
	                get_tag_link( $tag->term_id ), $tag->name, $tag->name
	            );
	        }
	    }
	    echo implode( '', $links );
	}
}

if ( ! function_exists( 'largo_homepage_categories_and_tags' ) ) {
	function largo_homepage_categories_and_tags() {
	    $cats = get_the_category();
	    $tags = get_the_tags();

	    $links = array();
	    if ( $cats ) {
	        foreach ( $cats as $cat ) {
	            if ( $cat->name == 'Uncategorized' ) {
	                continue;
	            }
	            $links[] = sprintf(
	                '<span class="post-category-link"><a href="%s" title="%s">%s</a></span>',
	                get_category_link( $cat->term_id ), $cat->name,
	                $cat->name
	            );
	        }
	    }
	    if ( $tags ) {
	        foreach ( $tags as $tag ) {
	            $links[] = sprintf(
	                '<span class="post-tag-link"><a href="%s" title="%s">%s</a></span>',
	                get_tag_link( $tag->term_id ), $tag->name, $tag->name
	            );
	        }
	    }
	    echo implode( ', ', $links );
	}
}

/**
 * RELATED POSTS
*/

 /*
 * XXX: this may not be necessary the_post_thumbnail takes sizes. -- ML
 */
function largo_get_post_thumbnail_src( $post, $size = '60x60' ) {
    if ( has_post_thumbnail( $post->ID ) ) {
        $thumb = get_post_thumbnail_id( $post->ID );
        $image = wp_get_attachment_image_src( $thumb, $size );
        return $image[ 0 ]; // src
    }
}

/* Retrieves the excerpt of any post.
 *
 * @param   object  $post       Post object
 * @param   int     $word_count Number of words (default 40)
 * @return  String
 */
function largo_split_words( $text, $split_limit = -1 ) {
    // XXX: deal with the way largo_get_excerpt uses this limit to
    // determine whether to cut off remaining text.
    if ( $split_limit > -1 )
        $split_limit += 1;

    $words = preg_split( "/[\n\r\t ]+/", $text, $split_limit,
                         PREG_SPLIT_NO_EMPTY );
    return $words;
}

function largo_get_excerpt( $post, $word_count = 40 ) {
    $text = $post->post_content;

    // HACK: This is ripped from wp_trim_excerpt() in
    // wp-includes/formatting.php because there's seemingly no way to
    // use it outside of The Loop
    // A solution to this was filed as ticket #16372 in WP Trac, and
    // should land in WP 3.2.
    $text = strip_shortcodes( $text );

    $text = apply_filters( 'the_content', $text );
    $text = str_replace( ']]>', ']]&gt;', $text );
    $text = strip_tags( $text );
    $excerpt_length = apply_filters( 'excerpt_length', $word_count );
    $excerpt_more = apply_filters( 'excerpt_more', ' ' . '[...]' );
    $words = largo_split_words( $text, $excerpt_length );

    if ( count( $words ) > $excerpt_length ) {
        array_pop( $words );
        $text = implode( ' ', $words );
        $text = $text . $excerpt_more;
    } else {
        $text = implode( ' ', $words );
    }

    return $text;
}

?>