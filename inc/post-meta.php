<?php

/**
 * Move the author dropdown to the publish metabox so it's easier to find
 *
 * @since 1.0
 */
//
if ( ! Largo()->is_plugin_active( 'co-authors-plus' ) ) {
	function largo_move_author_to_publish_metabox() {
		global $post_ID;
		$post = get_post( $post_ID );
		printf( '<div id="author" class="misc-pub-section" style="padding: 8px 10px;">%s: ', __( 'Author', 'largo' ) );
		post_author_meta_box( $post );
		echo '</div>';
	}
	add_action( 'post_submitbox_misc_actions', 'largo_move_author_to_publish_metabox' );
}

/**
 * Hide some of the less commonly used metaboxes to cleanup the post and page edit screens
 *
 * @since 1.0
 */
function largo_remove_default_post_screen_metaboxes() {
	remove_meta_box( 'trackbacksdiv','post','normal' ); // trackbacks
	remove_meta_box( 'slugdiv','post','normal' ); // slug
	remove_meta_box( 'revisionsdiv','post','normal' ); // revisions
	remove_meta_box( 'authordiv', 'post', 'normal' ); // author
	remove_meta_box( 'commentsdiv','post','normal' ); // comments
}
add_action('admin_menu','largo_remove_default_post_screen_metaboxes');

/**
 * Show all of the other metaboxes by default (particularly to show the excerpt)
 *
 * @since 1.0
 */
function largo_change_default_hidden_metaboxes( $hidden, $screen ) {
    if ( 'post' == $screen->base ) {
        $hidden = array();
    }
    return $hidden;
}
add_filter( 'default_hidden_meta_boxes', 'largo_change_default_hidden_metaboxes', 10, 2 );

/**
 * Creates custom meta boxes to the post edit screens using the Largo Metabox API
 * Which lives in inc/metabox-api.php
 */

// Related posts controls
largo_add_meta_box(
	'largo_additional_options',
	__( 'Additional Options', 'largo' ),
	'largo_custom_related_meta_box_display', //could also be added with largo_add_meta_content('largo_custom_related_meta_box_display', 'largo_additional_options')
	'post',
	'side',
	'core'
);

// Related posts controls
largo_add_meta_box(
	'largo_byline_meta',
	__( 'Custom Byline Options', 'largo' ),
	'largo_byline_meta_box_display',
	( of_get_option( 'custom_landing_enabled' ) ) ? array('post', 'cftl-tax-landing') : 'post',
	'side',
	'core'
);

// Layout options for post templates, custom sidebars
largo_add_meta_box(
	'largo_layout_meta',
	__( 'Layout Options', 'largo' ),
	'largo_layout_meta_box_display',
	array('post', 'page'),
	'side',
	'core'
);

// Featured video instead of featured image
largo_add_meta_box(
	'largo_featured_video',
	__( 'Featured Video', 'largo' ),
	'largo_featured_video_meta_box_display',
	'post',
	'side',
	'low'
);

// Disclaimer
largo_add_meta_box(
	'largo_custom_disclaimer',
	__( 'Disclaimer', 'largo' ),
	'largo_custom_disclaimer_meta_box_display', //could also be added with largo_add_meta_content('largo_custom_related_meta_box_display', 'largo_additional_options')
	'post',
	'normal',
	'core'
);


/**
 * Contents for the 'byline' metabox
 */
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
	largo_register_meta_input( array('largo_byline_text', 'largo_byline_link') );
}

/**
 * Contents for the Layout Options metabox
 */
function largo_layout_meta_box_display () {
	global $post;

	wp_nonce_field( 'largo_meta_box_nonce', 'meta_box_nonce' );

	if ( $post->post_type != 'page' ) {
		echo '<p><strong>' . __('Template', 'largo' ) . '</strong><br />';
		echo __('Select the Post Template you wish this post to use.', 'largo' ) . '</p>';
		echo '<label class="hidden" for="post_template">' . __("Post Template", 'largo' ) . '</label>';
		echo '<select name="_wp_post_template" id="post_template" class="dropdown">';
		echo '<option value="">' . __( 'Default', 'largo' ) . '</option>';
		post_templates_dropdown(); //get the options
		echo '</select>';
		largo_register_meta_input('_wp_post_template');
	}

	echo '<p><strong>' . __('Custom Sidebar', 'largo' ) . '</strong><br />';
	echo __('Select a custom sidebar to display.', 'largo' ) . '</p>';
	echo '<label class="hidden" for="custom_sidebar">' . __("Custom Sidebar", 'largo' ) . '</label>';
	echo '<select name="custom_sidebar" id="custom_sidebar" class="dropdown">';
	largo_custom_sidebars_dropdown(); //get the options
	echo '</select>';
	largo_register_meta_input('custom_sidebar');
}

/**
 * Content for the Featured Videometabox
 */
function largo_featured_video_meta_box_display() {
  global $post;
  $values = get_post_custom( $post->ID );
  $youtube_url = isset( $values['youtube_url'] ) ? $values['youtube_url'][0] : '';
  wp_nonce_field( 'largo_meta_box_nonce', 'meta_box_nonce' );

  echo __('<p>In some cases you might want to use a video in the place of the featured image. If you would prefer to use a video, enter the URL for the video (YouTube only) here:</p>', 'largo');
  echo '<input type="text" name="youtube_url" id="youtube_url" value="' . esc_url( $youtube_url ) . '" />';
  echo __('<p class="small">Note that at the moment this is only used for the top story on the homepage but future versions of Largo might enable this functionality elsewhere in the theme.</p>', 'largo');

	largo_register_meta_input('youtube_url');

}

/**
 * Content for the Additional Options metabox
 */
function largo_custom_related_meta_box_display() {
	global $post;

	$value = get_post_meta( $post->ID, '_largo_custom_related_posts', true );

	echo '<p><strong>' . __('Related Posts', 'largo') . '</strong><br />';
	echo __('To override the default related posts functionality enter specific related post IDs separated by commas.') . '</p>';
	echo '<input type="text" name="largo_custom_related_posts" value="', esc_attr($value),'" />';
	largo_register_meta_input('largo_custom_related_posts');
}

/**
 * Content for the Additional Options metabox
 */
function largo_custom_disclaimer_meta_box_display() {
	global $post;

	$value = get_post_meta( $post->ID, 'disclaimer', true );

	if ( empty( $value ) ) {
		$value = of_get_option( 'default_disclaimer' );
	}

	echo '<p><strong>' . __('Disclaimer', 'largo') . '</strong><br />';
	echo '<textarea name="disclaimer" style="width: 98%;">' . esc_textarea( $value ) . '</textarea>';

	largo_register_meta_input('disclaimer', '_largo_custom_disclaimer_value' );
}

function _largo_custom_disclaimer_value( $value ) {
	$value = trim( $value );

	if ( of_get_option( 'default_disclaimer' ) == $value ) {
		return null;
	}

	return $value;
}


/**
 * Additional content for the Additional Options metabox
 */
function largo_top_tag_display() {
	global $post;

	$top_term = get_post_meta( $post->ID, 'top_term', TRUE );
	$terms = get_the_terms( $post->ID, array( 'series', 'category', 'post_tag' ) );

	if ( ! $terms ) return; //no post terms yet? Disregard this then

	echo '<p><strong>' . __('Top Term', 'largo') . '</strong><br />';
	echo __('Identify which of this posts\'s terms is primary.') . '</p>';
	echo '<select name="top_term" id="top_term" class="dropdown">';

	foreach( $terms as $term ) {
		echo '<option value="' . (int) $term->term_id . '"' . selected( $term->term_id, $top_term, FALSE ) . ">" . $term->name . '</option>';
	}

	echo '</select>';

	largo_register_meta_input('top_term');
}
largo_add_meta_content('largo_top_tag_display', 'largo_additional_options');

/**
 * Load JS for our top-terms select
 */
function largo_top_terms_js() {
	global $typenow;
  if( $typenow == 'post' ) {
  	wp_enqueue_script( 'top-terms', get_template_directory_uri() . '/js/top-terms.js', array( 'jquery' ) );
  }
}
add_action( 'admin_enqueue_scripts', 'largo_top_terms_js' );