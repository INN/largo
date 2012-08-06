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
?>