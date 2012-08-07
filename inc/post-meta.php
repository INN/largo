<?php

/* Adds meta boxes to the post edit screen for custom byline and link
based on: http://wp.tutsplus.com/tutorials/plugins/how-to-create-custom-wordpress-writemeta-boxes/
*/

add_action( 'add_meta_boxes', 'largo_meta_box_add' );

function largo_meta_box_add()
{
	add_meta_box( 'largo_byline_meta', 'Custom Byline Options', 'largo_meta_box_display', 'post', 'side', 'core' );
}

function largo_meta_box_display( $post )
{
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

add_action( 'save_post', 'largo_meta_box_save' );

function largo_meta_box_save( $post_id )
{
	// Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// if our nonce isn't there, or we can't verify it, bail
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

	// if our current user can't edit this post, bail
	if( !current_user_can( 'edit_post' ) ) return;

	// now we can actually save the data
	$allowed = array(
		'a' => array( // on allow a tags
			'href' => array() // and those anchords can only have href attribute
		)
	);

	// Probably a good idea to make sure your data is set
	if( isset( $_POST['largo_byline_text'] ) )
		update_post_meta( $post_id, 'largo_byline_text', wp_kses( $_POST['largo_byline_text'], $allowed ) );
	if( isset( $_POST['largo_byline_link'] ) )
		update_post_meta( $post_id, 'largo_byline_link', wp_kses( $_POST['largo_byline_link'], $allowed ) );

}

function largo_time() {
	// Change to the date after a certain time
	$time_difference = current_time('timestamp') - get_the_time('U');
	if($time_difference < 86400) {
		return '<span class="time-ago">' .human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago</span>';
	} else {
		return get_the_date();
	};
}

function largo_byline() {
	// get post custom fields and use the custom byline if set, if not use default author valuess
	$values = get_post_custom( $post->ID );
	$byline_text = isset( $values['largo_byline_text'] ) ? esc_attr( $values['largo_byline_text'][0] ) : '';
	$byline_link = isset( $values['largo_byline_link'] ) ? esc_url( $values['largo_byline_link'][0] ) : '';
	$byline_title_attr = esc_attr( sprintf( 'More from %s', $byline_text ) );

	if ( $byline_text == '' ) :
		$byline_text = esc_html( get_the_author() );
	endif;
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

function largo_show_author_box() {
	$values = get_post_custom( $post->ID );
	$byline_text = '';
	if ($values['largo_byline_text'])
		$byline_text = esc_attr( $values['largo_byline_text'][0] );

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
?>