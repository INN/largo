<?php

/** Adds meta boxes to the post edit screen for custom byline and link
 * based on: http://wp.tutsplus.com/tutorials/plugins/how-to-create-custom-wordpress-writemeta-boxes/
 */

function largo_meta_box_add() {
	add_meta_box( 'largo_byline_meta', 'Custom Byline Options', 'largo_meta_box_display', 'post', 'side', 'core' );
}
add_action( 'add_meta_boxes', 'largo_meta_box_add' );

function largo_meta_box_save( $post_id ) {
	// Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// if our nonce isn't there, or we can't verify it, bail
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

	// if our current user can't edit this post, bail
	if( !current_user_can( 'edit_post' ) ) return;

	// now we can actually save the data
	$allowed = array(
		'a' => array( // on allow a tags
			'href' => array() // and those anchors can only have href attribute
		)
	);

	// make sure data is set, if author has removed the field or not populated it, delete it
	if( isset( $_POST['largo_byline_text'] ) && $_POST['largo_byline_text'] != '' ) {
		update_post_meta( $post_id, 'largo_byline_text', wp_kses( $_POST['largo_byline_text'], $allowed ) );
	} else if ( isset( $_POST['largo_byline_text'] ) && $_POST['largo_byline_text'] == '' ) {
		delete_post_meta($post_id, 'largo_byline_text');
	};
	if( isset( $_POST['largo_byline_link'] ) && $_POST['largo_byline_link'] != '' ) {
		update_post_meta( $post_id, 'largo_byline_link', wp_kses( $_POST['largo_byline_link'], $allowed ) );
	} else if ( isset( $_POST['largo_byline_link'] ) && $_POST['largo_byline_link'] == '' ) {
		delete_post_meta($post_id, 'largo_byline_link');
	};
}
add_action( 'save_post', 'largo_meta_box_save' );

function largo_meta_box_display( $post ) {
	$values = get_post_custom( $post->ID );
	$byline_text = isset( $values['largo_byline_text'] ) ? esc_attr( $values['largo_byline_text'][0] ) : '';
	$byline_link = isset( $values['largo_byline_link'] ) ? esc_url( $values['largo_byline_link'][0] ) : '';
	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	?>
	<p>
		<label for="largo_byline_text">Byline Text</label>
		<input type="text" name="largo_byline_text" id="largo_byline_text" value="<?php echo $byline_text; ?>" />
	</p>

	<p>
		<label for="largo_byline_link">Byline Link</label>
		<input type="text" name="largo_byline_link" id="largo_byline_link" value="<?php echo $byline_link; ?>" />
	</p>
	<?php
}

if ( ! function_exists( 'largo_time' ) ) {
/**
 * For posts published less than 24 hours ago, show "time ago" instead of date
 */
	function largo_time() {
		$time_difference = current_time('timestamp') - get_the_time('U');
		if($time_difference < 86400) {
			return '<span class="time-ago">' .human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago</span>';
		} else {
			return get_the_date();
		};
	}
}

if ( ! function_exists( 'largo_byline' ) ) {
/**
 * Outputs custom byline and link (if set), otherwise outputs author link and post date
 */
	function largo_byline() {
		// get post custom fields and use the custom byline if set, if not use default author valuess
		$values = get_post_custom( $post->ID );
		$byline_text = isset( $values['largo_byline_text'] ) ? esc_attr( $values['largo_byline_text'][0] ) : '';
		$byline_link = isset( $values['largo_byline_link'] ) ? esc_url( $values['largo_byline_link'][0] ) : '';
		$byline_title_attr = esc_attr( sprintf( 'More from %s', $byline_text ) );

		if ( $byline_text == '' )
			$byline_text = esc_html( get_the_author() );
		if ( $byline_link == '' ) :
			$byline_link = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
			$byline_title_attr = esc_attr( sprintf( 'View all posts by %s', get_the_author() ) );
		endif;

		// print the byline
		printf( '<span class="by-author"><span class="sep">By:</span> <span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span></span> | <time class="entry-date" datetime="%4$s" pubdate>%5$s</time>',
			$byline_link,
			$byline_title_attr,
			$byline_text,
			esc_attr( get_the_date( 'c' ) ),
			largo_time()
		);
	}
}

function largo_show_author_box($post_id) {
	$byline_text = get_post_meta( $post_id, 'largo_byline_text' ) ? esc_attr(get_post_meta( $post_id, 'largo_byline_text', true )) : '';
	if ( of_get_option( 'show_author_box' ) && get_the_author_meta( 'description' ) && $byline_text == '' ) {
		return true;
	} else {
		return false;
	}
}

//detemine whether or not an author has a valid gravatar image, see: http://codex.wordpress.org/Using_Gravatars
function has_gravatar($email) {
	// Craft a potential url and test its headers
	$hash = md5(strtolower(trim($email)));
	$uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
	$headers = @get_headers($uri);
	if (!preg_match("|200|", $headers[0])) {
		$has_valid_avatar = FALSE;
	} else {
		$has_valid_avatar = TRUE;
	}
	return $has_valid_avatar;
}

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

if ( ! function_exists( 'largo_custom_wp_link_pages' ) ) {
/**
 * Improved wp_link_pages functionality
 * Based on: http://bavotasan.com/2012/a-better-wp_link_pages-for-wordpress/
 */
	function largo_custom_wp_link_pages( $args ) {
		$defaults = array(
			'before' => '<div class="post-pagination">',
			'after' => '</div>',
			'text_before' => '',
			'text_after' => '',
			'nextpagelink' => __( 'Next Page' ),
			'previouspagelink' => __( 'Previous Page' ),
			'pagelink' => '%',
			'echo' => 1
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

?>