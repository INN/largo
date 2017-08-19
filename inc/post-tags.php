<?php

/**
 * For posts published less than 24 hours ago, show "time ago" instead of date, otherwise just use the published date
 *
 * @param $echo bool echo the string or return itv (default: echo)
 * @return string date and time as formatted html
 * @since 0.3
 */
if ( ! function_exists( 'largo_time' ) ) {
	function largo_time( $echo=true, $post=null ) {
		$post = get_post( $post );

		$pubdate = get_the_time( 'U', $post );
		$output = largo_time_diff( $pubdate );

		if ( $echo )
			echo $output;
		return $output;
	}
}

/**
 * For posts modified less than 24 hours ago, show "time ago" instead of date, otherwise just use the modified date
 *
 * @param $echo bool echo the string or return itv (default: echo)
 * @return string date and time as formatted html
 * @since 0.5.5
 */
if ( ! function_exists( 'largo_modified_time' ) ) {
	function largo_modified_time( $echo=true, $post=null ) {
		$post = get_post( $post );

		$updated = get_the_modified_time( 'U', $post );
		$output = largo_time_diff( $updated );

		if ( $echo )
			echo $output;
		return $output;
	}
}

/**
 * Given a time, if it was less than 24 hours ago return how many hours ago that was, otherwise return the 'F j, Y' formatted date
 * @param int $time the Unix timestamp for the modified date
 * @return string HTML for the either "x hours ago" or the submitted date, formatted
 * @since 0.5.5
 * @see https://secure.php.net/manual/en/function.date.php
 * @see https://github.com/INN/Largo/pull/1265
 */
function largo_time_diff( $time ) {
	$time_difference = current_time( 'timestamp' ) - $time;

	$time = $time_difference < 86400
        ? human_time_diff( $time, current_time( 'timestamp' ) )
        : date( 'F j, Y', $time );

	return sprintf( __( '<span class="time-ago">%s ago</span>', 'largo' ), $time );

}

/**
 * Get the author name when custom byline options are set
 *
 * @param $echo bool echo the string or return it (default: echo)
 * @return string author name as formatted html
 * @since 0.3
 */
if ( ! function_exists( 'largo_author' ) ) {
	function largo_author( $echo = true ) {
		global $post;
		$values = get_post_custom( $post->ID );
		$byline_text = isset( $values['largo_byline_text'] ) ? $values['largo_byline_text'][0] : get_the_author();

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
 * @since 0.3
 */
if ( ! function_exists( 'largo_author_link' ) ) {
	function largo_author_link( $echo = true, $post=null ) {
		$post = get_post( $post );
		$values = get_post_custom( $post->ID );
		$author_id = ( $post ) ? $post->post_author : get_the_author_meta( 'ID' );

		$byline_text = isset( $values['largo_byline_text'] ) ? $values['largo_byline_text'][0] : get_the_author_meta('display_name', $author_id);

		// if it's a custom byline but there's no link, just output the byline text
		if ( isset( $values['largo_byline_text'] ) && !isset( $values['largo_byline_link'] ) ) {
			$output = esc_html( $byline_text );
		} else {
			$byline_link = isset( $values['largo_byline_link'] ) ? $values['largo_byline_link'][0] : get_author_posts_url( get_the_author_meta( 'ID', $author_id ) );
			$byline_title_attr = sprintf( __( 'More from %s','largo' ), $byline_text );
			$output = '<a class="url fn n" href="' . esc_url( $byline_link ) . '" title="' . esc_attr( $byline_title_attr ) . '" rel="author">' . esc_html( $byline_text ) . '</a>';
		}

		if ( $echo )
			echo $output;
		return $output;
	}
}

/**
 * Outputs custom byline and link (if set), otherwise outputs author link and post date
 *
 * @param Boolean $echo Echo the string or return it (default: echo)
 * @param Boolean $exclude_date Whether to exclude the date from byline (default: false)
 * @param WP_Post|Integer $post The post object or ID to get the byline for. Defaults to current post.
 * @return String Byline as formatted html
 * @since 0.3
 */
if ( ! function_exists( 'largo_byline' ) ) {
	function largo_byline( $echo = true, $exclude_date = false, $post = null ) {

		// Get the post ID
		if (!empty($post)) {
			if (is_object($post))
				$post_id = $post->ID;
			else if (is_numeric($post))
				$post_id = $post;
		} else {
			$post_id = get_the_ID();
		}

		// Set us up the options
		// This is an array of things to allow us to easily add options in the future
		$options = array(
			'post_id' => $post_id,
			'values' => get_post_custom( $post_id ),
			'exclude_date' => $exclude_date,
		);

		if ( isset( $options['values']['largo_byline_text'] ) && !empty( $options['values']['largo_byline_text'] ) ) {
			// Temporary placeholder for largo custom byline option
			$byline = new Largo_Custom_Byline( $options );
		} else if ( function_exists( 'get_coauthors' ) ) {
			// If Co-Authors Plus is enabled and there is not a custom byline
			$byline = new Largo_CoAuthors_Byline( $options );
		} else {
			// no custom byline, no coauthors: let's do the default
			$byline = new Largo_Byline( $options );
		}

		/**
		 * Filter the largo_byline output text to allow adding items at the beginning or the end of the text.
		 *
		 * @since 0.5.4
		 * @param string $partial The HTML of the output of largo_byline(), before the edit link is added.
		 * @link https://github.com/INN/Largo/issues/1070
		 */
		$byline = apply_filters( 'largo_byline', $byline );

		if ( $echo ) {
			echo $byline;
		}
		return $byline;
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
 * @param $strip_tags|$strip_shortcodes bool
 * @uses largo_trim_sentences
 * @package largo
 * @since 0.3
 */
if ( ! function_exists( 'largo_excerpt' ) ) {
	function largo_excerpt( $the_post=null, $sentence_count = 5, $use_more = null, $more_link = null, $echo = true, $strip_tags = true, $strip_shortcodes = true ) {
		if (!empty($use_more))
			_deprecated_argument(__FUNCTION__, '0.5.1', 'Parameter $use_more is deprecated. Please use null as the argument.');
		if (!empty($more_link))
			_deprecated_argument(__FUNCTION__, '0.5.1', 'Parameter $more_link is deprecated. Please use null as the argument.');

		$the_post = get_post($the_post); // Normalize it into a post object

		if (!empty($the_post->post_excerpt)) {
			// if a post has a custom excerpt set, we'll use that
			$content = apply_filters('get_the_excerpt', $the_post->post_excerpt);
		} else if (is_home() && preg_match('/<!--more(.*?)?-->/', $the_post->post_content, $matches) > 0) {
			// if we're on the homepage and the post has a more tag, use that
			$parts = explode($matches[0], $the_post->post_content, 2);
			$content = $parts[0];
		} else {
			// otherwise we'll just do our best and make the prettiest excerpt we can muster
			$content = largo_trim_sentences($the_post->post_content, $sentence_count);
		}

		// optionally strip shortcodes and html
		$output = '';
		if ( $strip_tags && $strip_shortcodes )
			$output .= strip_tags( strip_shortcodes ( $content ) );
		else if ( $strip_tags )
			$output .= strip_tags( $content );
		else if ( $strip_shortcodes )
			$output .= strip_shortcodes( $content );
		else
			$output .= $content;

		$output = apply_filters('the_content', $output);

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
 * @since 0.3
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
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 * @since 0.3
 */
if ( ! function_exists( 'largo_comment' ) ) {
	function largo_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
		?>
		<li class="post pingback">
			<p><?php _e( 'Pingback', 'largo' ); ?>: <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'largo' ), '<span class="edit-link">', '</span>' ); ?></p>
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

						<?php edit_comment_link( __( 'Edit', 'largo' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .comment-author .vcard -->

					<?php if ( $comment->comment_approved == '0' ) : ?>
						<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'largo' ); ?></em>
						<br />
					<?php endif; ?>

				</footer>

				<div class="comment-content"><?php comment_text(); ?></div>

				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'reply_text' => sprintf( '%s <span>&darr;</span>', __( 'Reply', 'largo' ) ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div><!-- .reply -->
			</article><!-- #comment-## -->

		<?php
				break;
		endswitch;
	}
}

/**
 * If largo_top_term() would output a term, wrap that in an h5.top-term and output it to the page.
 *
 * Takes the same argument array as largo_top_term(), and passes that argument array to
 * largo_top_term() with 'echo' => False. largo_maybe_top_term() handles the echo decision.
 *
 * @since 0.5.5
 * @uses largo_top_term
 * @param Array $args the same argument array that would be passed to largo_top_term()
 */
function largo_maybe_top_term( $args = array() ) {
	$args = array_merge( $args, array( 'echo' => False ) );
	$top_term = largo_top_term( $args );

	if ( $top_term ) { ?>
		<h5 class="top-tag"><?php echo $top_term; ?></h5>
	<?php }
}

/**
 * Display the last-edited date for this post
 * @since 0.5.5
 * @link https://github.com/INN/Largo/issues/1341
 */
function largo_edited_date( $post = null ) {
	$post = get_post( $post );

	echo sprintf(
		' <time class="entry-date updated dtstamp" datetime="%1$s"><span class="last-modified">%2$s %3$s</span></time> ',
		esc_attr( get_the_modified_date( 'c', $post ) ),
		__( 'Updated', 'largo' ),
		largo_modified_time( false, $post )
	);
}

/**
 * Output largo_edited_date() on the single post template
 *
 * This is disabled in Largo by default. To add it to your child theme, paste in this:
 *
 *     #add_action( 'largo_after_hero', 'largo_after_hero_largo_edited_date', 5 );
 *
 * @since 0.5.5
 * @action largo_after_hero
 * @uses largo_edited_date
 * @link https://github.com/INN/Largo/issues/1341
 */
function largo_after_hero_largo_edited_date() {
	if ( largo_post_was_updated() ) {
		echo '<div class="entry-content clearfix">';
		largo_edited_date();
		echo '</div>';
	}
}
