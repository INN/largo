<?php

/**
 * Adds meta boxes to the post edit screen for custom byline and link
 * see: http://wp.tutsplus.com/tutorials/plugins/how-to-create-custom-wordpress-writemeta-boxes/
 *
 * @since 1.0
 */

// Register our custom meta boxes
function largo_meta_box_add() {
	add_meta_box(
		'largo_byline_meta',
		__('Custom Byline Options', 'largo'),
		'largo_byline_meta_box_display',
		'post',
		'side',
		'core'
	);
	$screens = array( 'post', 'page' );
    foreach ( $screens as $screen ) {
		add_meta_box(
			'largo_layout_meta',
			__('Layout Options', 'largo'),
			'largo_layout_meta_box_display',
			$screen,
			'side',
			'core'
		);
	}
}
add_action( 'add_meta_boxes', 'largo_meta_box_add' );

// Save our custom meta box values as custom fields
function largo_meta_box_save( $post_id ) {
	global $post;
	// Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// if our nonce isn't there, or we can't verify it, bail
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'largo_meta_box_nonce' ) ) return;

	// if our current user can't edit this post, bail
	if( !current_user_can( 'edit_post' ) ) return;

	// now we can actually save the data
	$allowed = array(
		'a' => array( // on allow a tags
			'href' => array() // and those anchors can only have href attribute
		)
	);

	$mydata = array(
		'_wp_post_template' => $_POST['_wp_post_template'],
		'custom_sidebar' 	=> $_POST['custom_sidebar'],
		'largo_byline_text' => $_POST['largo_byline_text'],
		'largo_byline_link' => $_POST['largo_byline_link']
	);

	foreach ( $mydata as $key => $value ) {
		if ( get_post_meta( $post->ID, $key, FALSE ) ) {
			update_post_meta( $post->ID, $key, $value ); //if the custom field already has a value, update it
		} else {
			add_post_meta( $post->ID, $key, $value );//if the custom field doesn't have a value, add the data
		}
		if ( !$value ) delete_post_meta( $post->ID, $key ); //and delete if blank
	}

}
add_action( 'save_post', 'largo_meta_box_save' );

// Templates for displaying the custom meta boxes
function largo_byline_meta_box_display() {
	global $post;
	$values = get_post_custom( $post->ID );
	$byline_text = isset( $values['largo_byline_text'] ) ? esc_attr( $values['largo_byline_text'][0] ) : '';
	$byline_link = isset( $values['largo_byline_link'] ) ? esc_url( $values['largo_byline_link'][0] ) : '';
	wp_nonce_field( 'largo_meta_box_nonce', 'meta_box_nonce' );
	?>
	<p>
		<label for="largo_byline_text"><?php _e('Byline Text', 'largo'); ?></label>
		<input type="text" name="largo_byline_text" id="largo_byline_text" value="<?php echo $byline_text; ?>" />
	</p>

	<p>
		<label for="largo_byline_link"><?php _e('Byline Link', 'largo'); ?></label>
		<input type="text" name="largo_byline_link" id="largo_byline_link" value="<?php echo $byline_link; ?>" />
	</p>
	<?php
}

function largo_layout_meta_box_display () {
	global $post;

	wp_nonce_field( 'largo_meta_box_nonce', 'meta_box_nonce' );

	if ( $post->post_type != 'page' ) {
		echo '<p><strong>' . __('Template', 'largo' ) . '</strong><br />';
		echo __('Select the Post Template you wish this post to use.', 'largo' ) . '</p>';
		echo '<label class="hidden" for="post_template">' . __("Post Template", 'largo' ) . '</label>';
		echo '<select name="_wp_post_template" id="post_template" class="dropdown">';
		echo '<option value="">Default</option>';
		post_templates_dropdown(); //get the options
		echo '</select>';
	}

	echo '<p><strong>' . __('Custom Sidebar', 'largo' ) . '</strong><br />';
	echo __('Select a custom sidebar to display.', 'largo' ) . '</p>';
	echo '<label class="hidden" for="custom_sidebar">' . __("Custom Sidebar", 'largo' ) . '</label>';
	echo '<select name="custom_sidebar" id="custom_sidebar" class="dropdown">';
	custom_sidebars_dropdown(); //get the options
	echo '</select>';
}