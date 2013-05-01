<?php

/**
 * For posts published less than 24 hours ago, show "time ago" instead of date, otherwise just use get_the_date
 *
 * @param $echo bool echo the string or return itv (default: echo)
 * @return string date and time as formatted html
 * @since 1.0
 */
if ( ! function_exists( 'largo_time' ) ) {
	function largo_time( $echo = true ) {
		$time_difference = current_time('timestamp') - get_the_time('U');

		if ( $time_difference < 86400 )
			$output = '<span class="time-ago">' . human_time_diff(get_the_time('U'), current_time('timestamp')) . __(' ago', 'largo') . '</span>';
		else
			$output = get_the_date();

		if ( $echo )
			echo $output;
		return $output;
	}
}

/**
 * Get the author name when custom byline options are set
 *
 * @param $echo bool echo the string or return it (default: echo)
 * @return string author name as formatted html
 * @since 1.0
 */
if ( ! function_exists( 'largo_author' ) ) {
	function largo_author( $echo = true ) {
		$values = get_post_custom( $post->ID );
		$byline_text = isset( $values['largo_byline_text'] ) ? esc_attr( $values['largo_byline_text'][0] ) : '';

		if ( $byline_text == '' )
			$byline_text = esc_html( get_the_author() );

		if ( $echo )
			echo $byline_text;
		return $byline_text;
	}
}

/**
 * Get the author link when custom byline options are set
 *
 * @param $echo bool echo the string or return it (default: echo)
 * @return string author link as formatted html
 * @since 1.0
 */
if ( ! function_exists( 'largo_author_link' ) ) {
	function largo_author_link( $echo = true ) {
		global $post;
		$values = get_post_custom( $post->ID );
		$byline_text = isset( $values['largo_byline_text'] ) ? esc_attr( $values['largo_byline_text'][0] ) : '';
		$byline_link = isset( $values['largo_byline_link'] ) ? esc_url( $values['largo_byline_link'][0] ) : '';
		$byline_title_attr = esc_attr( sprintf( __( 'More from %s','largo' ), $byline_text ) );

		if ( $byline_text == '' )
			$byline_text = esc_html( get_the_author() );
		if ( $byline_link == '' ) :
			$byline_link = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
			$byline_title_attr = esc_attr( sprintf( __( 'View all posts by %s','largo' ), get_the_author() ) );
		endif;

		$output = '<a class="url fn n" href="' . $byline_link . '" title="' . $byline_title_attr . '" rel="author">' . $byline_text . '</a>';

		if ( $echo )
			echo $output;
		return $output;
	}
}

/**
 * Outputs custom byline and link (if set), otherwise outputs author link and post date
 *
 * @param $echo bool echo the string or return it (default: echo)
 * @return string byline as formatted html
 * @since 1.0
 */
if ( ! function_exists( 'largo_byline' ) ) {
	function largo_byline( $echo = true ) {
		global $post;

		$output = sprintf( '<span class="by-author"><span class="sep">By:</span> <span class="author vcard">%1$s</span></span> | <time class="entry-date updated dtstamp pubdate" datetime="%2$s">%3$s</time>',
			largo_author_link( false ),
			esc_attr( get_the_date( 'c' ) ),
			largo_time( false )
		);

		if ( current_user_can( 'edit_post', $post->ID ) )
			$output .=  sprintf( ' | <span class="edit-link"><a href="%1$s">Edit This Post</a></span>', get_edit_post_link() );

 		if ( is_single() && of_get_option( 'clean_read' ) === 'byline' )
 			$output .=	__('<a href="#" class="clean-read">View as "Clean Read"</a>', 'largo');

		if ( $echo )
			echo $output;
		return $output;
	}
}

/**
 * Outputs facebook, twitter, email, share and print utility links on article pages
 *
 * @param $echo bool echo the string or return it (default: echo)
 * @return string social icon area markup as formatted html
 * @since 1.0
 * @todo maybe let people re-arrange the order of the links or have more control over how they appear
 */
if ( ! function_exists( 'largo_post_social_links' ) ) {
	function largo_post_social_links( $echo = true ) {
		$utilities = of_get_option( 'article_utilities' );

		$output = '<div class="post-social clearfix"><div class="left">';

		if ( $utilities['twitter'] === '1' ) {
			$twitter_link = of_get_option( 'twitter_link' ) ? 'data-via="' . twitter_url_to_username( of_get_option( 'twitter_link' ) ) . '"' : '';
			$twitter_related = get_the_author_meta( 'twitter' ) ? get_the_author_meta( 'twitter' ) . ':Follow the author of this article' : '';
			$twitter_count = (of_get_option( 'show_twitter_count' ) == 0) ? 'data-count="none"' : '';

			$output .= sprintf( '<span class="twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-url="%1$s" data-text="%2$s" %3$s %4$s %5$s>Tweet</a></span>',
				get_permalink(),
				get_the_title(),
				$twitter_link,
				$twitter_related,
				$twitter_count
			);
		}

		if ( $utilities['facebook'] === '1' )
			$output .= sprintf( '<span class="facebook"><fb:like href="%1$s" send="false" layout="button_count" show_faces="false" action="%2$s"></fb:like></span>',
				get_permalink(),
				of_get_option( 'fb_verb' )
			);

		$output .= '</div><div class="right">';

		if ( $utilities['sharethis'] === '1' )
			$output .= '<span class="st_sharethis" displayText="Share"></span>';

		if ( $utilities['email'] === '1' )
			$output .= '<span class="st_email" displayText="Email"></span>';

		if ( $utilities['print'] === '1' )
			$output .= '<span class="print"><a href="#" onclick="window.print()" title="print this article" rel="nofollow"><i class="icon-print"></i> Print</a></span>';

		$output .= '</div></div>';

		if ( $echo )
			echo $output;
		return $output;
	}
}

/**
 * Show the author box on single posts when activated in theme options
 * Don't show it on posts with custom bylines or if a user has not filled out their profile
 *
 * @return bool true if the author box should be displayed
 * @since 1.0
 */
function largo_show_author_box() {
	global $post;
	$byline_text = get_post_meta( $post->ID, 'largo_byline_text' ) ? esc_attr( get_post_meta( $post->ID, 'largo_byline_text', true ) ) : '';
	if ( of_get_option( 'show_author_box' ) && get_the_author_meta( 'description' ) && $byline_text == '' )
		return true;
	return false;
}

/**
 * Determine whether or not an author has a valid gravatar image
 * see: http://codex.wordpress.org/Using_Gravatars
 *
 * @param $email string an author's email address
 * @return bool true if a gravatar is available for this user
 * @since 1.0
 */
function largo_has_gravatar( $email ) {
	// Craft a potential url and test its headers
	$hash = md5(strtolower(trim($email)));
	$uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
	$headers = @get_headers($uri);
	if (preg_match("|200|", $headers[0]))
		return true;
	return false;
}

/**
 * Replaces the_content() with paginated content (if <!--nextpage--> is used in the post)
 *
 * @since 1.0
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
		    	largo_custom_wp_link_pages( $args );
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
 * @since 1.0
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
 * Make a nicer-looking excerpt regardless of how an author has been using excerpts in the past
 *
 * @param $post object the post
 * @param $sentence_count int the number of sentences to show
 * @param $use_more bool append read more link to end of output
 * @param $more_link string the text of the read more link
 * @param $echo bool echo the output or return it (default: echo)
 * @uses largo_trim_sentences
 * @since 1.0
 * @todo change $use_more to bool, add echo/return
 */
if ( ! function_exists( 'largo_excerpt' ) ) {
	function largo_excerpt( $post, $sentence_count = 5, $use_more = true, $more_link = '', $echo = true ) {
		if ( is_home() && strpos( $post->post_content, '<!--more-->' ) && ( !$use_more ) ) : // if we're on the homepage and the post has a more tag, use that
			$output = '<p>' . strip_tags( get_the_content( $more_link ) ) . '</p>';
		elseif ( $post->post_excerpt ) : // if it has the optional excerpt set, use THAT
			if ( !$use_more ) :
				$output = '<p>' . get_the_excerpt() . '</p>';
			else :
				$output = '<p>' . strip_tags( get_the_excerpt() ) . ' <a href="' . get_permalink() . '">' . $more_link . '</a></p>';
			endif;
		else : // otherwise we'll just do our best and make the prettiest excerpt we can muster
			$output = largo_trim_sentences( get_the_content(), $sentence_count );
			$output .= '<a href="' . get_permalink() . '">' . $more_link . '</a>';
			$output = str_replace( '(more...)', '', $output );
			$output = apply_filters( 'the_content', $output );
		endif;

		if ( $echo )
			echo $output;

		return $output;
	}
}

/**
 * Attempt to trim input at sentence breaks
 *
 * @param $input string
 * @param $sentences number of sentences to trim to
 * @param $echo echo the string or return it (default: return)
 * @return $output trimmed string
 *
 * @since 1.0
 */
function largo_trim_sentences( $input, $sentences, $echo = false ) {
	$re = '/# Split sentences on whitespace between them.
		(?<=                # Begin positive lookbehind.
			[.!?]           	# Either an end of sentence punct,
			| [.!?][\'"]    	# or end of sentence punct and quote.
		)                   # End positive lookbehind.
		(?<!                # Begin negative lookbehind.
			Mr\.            	# Skip either "Mr."
		    | Mrs\.             # or "Mrs.",
		    | Ms\.              # or "Ms.",
		    | Jr\.              # or "Jr.",
		    | Dr\.              # or "Dr.",
		    | Prof\.            # or "Prof.",
		    | Sr\.              # or "Sr.",
		    | Rep\.             # or "Rep.",
		    | Sen\.             # or "Sen.",
		    | Gov\.             # or "Gov.",
		    | Pres\.            # or "Pres.",
		    | U\.S\.            # or "U.S.",
		    | Rev\.            	# or "Rev.",
		    | Gen\.        		# or "Gen.",
		    | Capt\.            # or "Capt.",
		    | Lt\.            	# or "Lt.",
		    | Cpl\.            	# or "Cpl.",
		    | Inc\.            	# or "Inc.",
		    | \s[A-Z]\.         # or initials ex: "George W. Bush",
		    | [A-Z]\.[A-Z]\.    # or random state abbreviations ex: "O.H.",
		)                   # End negative lookbehind.
		\s+                 # Split on whitespace between sentences.
		/ix';

	$strings = preg_split( $re, strip_tags( strip_shortcodes( $input ) ), -1, PREG_SPLIT_NO_EMPTY);

	$output = '';

	for ( $i = 0; $i < $sentences && $i < count($strings); $i++ ) {
		if ( $strings[$i] != '' )
			$output .= $strings[$i] . ' ';
	}

	if ( $echo )
		echo $output;

	return $output;
}

/**
 * Display navigation to next/previous pages when applicable
 *
 * @since 1.0
 */
if ( ! function_exists( 'largo_content_nav' ) ) {
	function largo_content_nav( $nav_id ) {
		global $wp_query;

		if ( $nav_id === 'single-post-nav-below' ) { ?>

			<nav id="nav-below" class="pager post-nav clearfix">
				<?php
					if ( $prev = get_previous_post() ) {
						printf( '<div class="previous"><a href="%1$s"><h5>Previous Story</h5><span class="meta-nav">%2$s</span></a></div>',
							get_permalink( $prev->ID ),
							$prev->post_title
						);
					}
					if ( $next = get_next_post() ) {
						printf( '<div class="next"><a href="%1$s"><h5>Next Story</h5><span class="meta-nav">%2$s</span></a></div>',
							get_permalink( $next->ID ),
							$next->post_title
						);
					}
				?>
			</nav><!-- #nav-below -->

		<?php } elseif ( $wp_query->max_num_pages > 1 ) { ?>

			<nav id="<?php echo $nav_id; ?>" class="pager post-nav">
				<div class="next"><?php previous_posts_link( __( 'Newer Stories &rarr;', 'largo' ) ); ?></div>
				<div class="previous"><?php next_posts_link( __( '&larr; Older Stories', 'largo' ) ); ?></div>
			</nav><!-- .post-nav -->

		<?php }
	}
}

/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
if ( ! function_exists( 'largo_comment' ) ) {
	function largo_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
		?>
		<li class="post pingback">
			<p>Pingback: <?php comment_author_link(); ?><?php edit_comment_link( 'Edit', '<span class="edit-link">', '</span>' ); ?></p>
		<?php
				break;
			default :
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>" class="comment">
				<footer class="comment-meta">
					<div class="comment-author vcard">
						<?php
							$avatar_size = 68;
							if ( '0' != $comment->comment_parent )
								$avatar_size = 39;

							echo get_avatar( $comment, $avatar_size );

							/* translators: 1: comment author, 2: date and time */
							printf( '%1$s on %2$s <span class="says">said:</span>',
								sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
								sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
									esc_url( get_comment_link( $comment->comment_ID ) ),
									get_comment_time( 'c' ),
									/* translators: 1: date, 2: time */
									sprintf( '%1$s at %2$s', get_comment_date(), get_comment_time() )
								)
							);
						?>

						<?php edit_comment_link( 'Edit', '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .comment-author .vcard -->

					<?php if ( $comment->comment_approved == '0' ) : ?>
						<em class="comment-awaiting-moderation">Your comment is awaiting moderation.</em>
						<br />
					<?php endif; ?>

				</footer>

				<div class="comment-content"><?php comment_text(); ?></div>

				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'reply_text' => 'Reply <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div><!-- .reply -->
			</article><!-- #comment-## -->

		<?php
				break;
		endswitch;
	}
} // ends check for largo_comment()