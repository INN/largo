<?php
/**
 * Functions related to pagination of posts and archives
 */

/**
 * Replaces the_content() with paginated content (if <!--nextpage--> is used in the post)
 *
 * @since 0.3
 */
if ( ! function_exists( 'largo_entry_content' ) ) {
	function my_queryvars( $qvars ) {
    	$qvars[] = 'all';
    	return $qvars;
    }
    add_filter( 'query_vars', 'my_queryvars' );

	function largo_entry_content( $post ) {

		global $wp_query, $numpages;
		$no_pagination = false;

		if ( isset( $wp_query->query_vars['all'] ) ) {
		    $no_pagination = $wp_query->query_vars['all'];
		}

		if( $no_pagination ) {
		    echo apply_filters( 'the_content', $post->post_content );
		    $page=$numpages+1;
		} else {
		    the_content();
		    if ( is_singular() && $numpages > 1 )
		    	largo_custom_wp_link_pages('');
		}
	}
}

/**
 * Adds pagination to single posts
 * Based on: http://bavotasan.com/2012/a-better-wp_link_pages-for-wordpress/
 *
 * @params $args same array of arguments as accepted by wp_link_pages
 * See: http://codex.wordpress.org/Function_Reference/wp_link_pages
 * @return formatted output in html (or echo)
 * @since 0.3
 */
if ( ! function_exists( 'largo_custom_wp_link_pages' ) ) {
	function largo_custom_wp_link_pages( $args ) {
		$defaults = array(
			'before' 			=> '<div class="post-pagination">',
			'after' 			=> '</div>',
			'text_before' 		=> '',
			'text_after' 		=> '',
			'nextpagelink' 		=> __( 'Next Page', 'largo' ),
			'previouspagelink' 	=> __( 'Previous Page', 'largo' ),
			'pagelink' 			=> '%',
			'echo' 				=> 1
		);

		$r = wp_parse_args( $args, $defaults );
		$r = apply_filters( 'wp_link_pages_args', $r );
		extract( $r, EXTR_SKIP );

		global $page, $numpages, $multipage, $more, $pagenow;

		$output = '';
		if ( $multipage ) {
			//if ( 'number' == $next_or_number ) {
				$output .= $before;

				//previous page
				$i = $page - 1;
				if ( $i && $more ) {
					$output .= _wp_link_page( $i );
					$output .= $text_before . $previouspagelink . $text_after . '</a>|';
				}

				//list of page #s
				for ( $i = 1; $i < ( $numpages + 1 ); $i = $i + 1 ) {
					$j = str_replace( '%', $i, $pagelink );
					$output .= ' ';
					if ( $i != $page || ( ( ! $more ) && ( $page == 1 ) ) )
						$output .= _wp_link_page( $i );
					else
						$output .= '<span class="current-post-page">';

					$output .= $text_before . $j . $text_after;
					if ( $i != $page || ( ( ! $more ) && ( $page == 1 ) ) )
						$output .= '</a>';
					else
						$output .= '</span>';
				}

				//next page
				$i = $page + 1;
				if ( $i <= $numpages && $more ) {
					$output .= '|' . _wp_link_page( $i );
					$output .= $text_before . $nextpagelink . $text_after . '</a>';
				}

				$output .= '|<a href="' . add_query_arg( array( 'all' => '1'), get_permalink() ) . '" title="View all pages">View As Single Page</a>';

				$output .= $after;
		}

		if ( $echo )
			echo $output;

		return $output;
	}
}

/**
 * Display navigation to next/previous pages when applicable
 *
 * @since 0.3
 */
if ( ! function_exists( 'largo_content_nav' ) ) {
	function largo_content_nav( $nav_id, $in_same_cat = false ) {
		global $wp_query;

		if ( $nav_id === 'single-post-nav-below' ) { ?>

			<nav id="nav-below" class="pager post-nav clearfix">
				<?php
					if ( $prev = get_previous_post( $in_same_cat ) ) {
						if( get_the_post_thumbnail( $prev->ID ) ) {
							$image = wp_get_attachment_image_src( get_post_thumbnail_id( $prev->ID ) );
							printf( __('<div class="previous"><a href="%1$s"><img class="thumb" src="%4$s" /><h5>Previous %2$s</h5><span class="meta-nav">%3$s</span></a></div>', 'largo'),
								get_permalink( $prev->ID ),
								of_get_option( 'posts_term_singular' ),
								$prev->post_title,
								$image[0]
							);
						} else {
							printf( __('<div class="previous"><a href="%1$s"><h5>Previous %2$s</h5><span class="meta-nav">%3$s</span></a></div>', 'largo'),
								get_permalink( $prev->ID ),
								of_get_option( 'posts_term_singular' ),
								$prev->post_title
							);
						}
					}
					if ( $next = get_next_post( $in_same_cat ) ) {
						if( get_the_post_thumbnail( $next->ID ) ) {
							$image = wp_get_attachment_image_src( get_post_thumbnail_id( $next->ID ) );
							printf( __('<div class="next"><a href="%1$s"><img class="thumb" src="%4$s" /><h5>Next %2$s</h5><span class="meta-nav">%3$s</span></a></div>', 'largo'),
								get_permalink( $next->ID ),
								of_get_option( 'posts_term_singular' ),
								$next->post_title,
								$image[0]
							);
						} else {
							printf( __('<div class="next"><a href="%1$s"><h5>Next %2$s</h5><span class="meta-nav">%3$s</span></a></div>', 'largo'),
								get_permalink( $next->ID ),
								of_get_option( 'posts_term_singular' ),
								$next->post_title
							);
						}
					}
					?>
			</nav><!-- #nav-below -->

		<?php } elseif ( $wp_query->max_num_pages > 1 ) {
			$posts_term = of_get_option('posts_term_plural');

			largo_render_template('partials/load-more-posts', array(
				'nav_id' => $nav_id,
				'the_query' => $wp_query,
				'posts_term' => ($posts_term)? $posts_term : 'Posts'
			));
		}
	}
}

