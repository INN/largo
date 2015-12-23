<?php

/**
 * Adds custom meta fields functionality to terms
 * Uses a custom post type as a proxy to bridge between a term_id and a post_meta field
 */

/**
 * Register the proxy post type
 */
function largo_register_term_meta_post_type() {
	register_post_type( '_term_meta', array(
		'public'     => false,
		'query_var'  => false,
		'rewrite'    => false,
		'supports'   => false,
	));
}
add_action( 'init', 'largo_register_term_meta_post_type' );

/**
 * Get the proxy post for a term
 *
 * @param string $taxnomy
 * @param int $term_id
 *
 * @return int $post_id
 */
function largo_get_term_meta_post( $taxonomy, $term_id ) {
	$query = new WP_Query( array(
		'post_type'      => '_term_meta',
		'posts_per_page' => 1,
		'post_status' => 'any',
		'tax_query'      => array(
			array(
				'taxonomy'         => $taxonomy,
				'field'            => 'id',
				'terms'            => $term_id,
				'include_children' => false
			)
		)
	));

	if ( $query->found_posts ) {
		return $query->posts[0]->ID;
	} else {
		$tax_input = array();
		$post_id = wp_insert_post( array( 'post_type' => '_term_meta', 'post_title' => "{$taxonomy}:${term_id}" ) );
		wp_set_post_terms( $post_id, array( (int) $term_id ), $taxonomy );
		return $post_id;
	}
}

/**
 * Add the "Set Featured Media" button in the term edit page
 *
 * @since 0.5.4
 * @see largo_term_featured_media_enqueue_post_editor
 */
function largo_add_term_featured_media_button( $context = '' ) {
	// Post ID here is the id of the post that Largo uses to keep track of the term's metadata. See largo_get_term_meta_post.
	$post_id = largo_get_term_meta_post( $context->taxonomy, $context->term_id );

	$has_featured_media = largo_has_featured_media($post_id);
	$language = (!empty($has_featured_media))? 'Edit' : 'Set';
	$featured = largo_get_featured_media($post_id);

	?>
	<tr class="form-field">
		<th scope="row" valign="top"><?php _e('Term banner image', 'largo'); ?></th>
		<td>
			<p><a href="#" id="set-featured-media-button" class="button set-featured-media add_media" data-editor="content" title="<?php echo $language; ?> Featured Media"><span class="dashicons dashicons-admin-generic"></span> <?php echo $language; ?> Featured Media</a> <span class="spinner" style="display: none;"></span></p>
			<p class="description">This image will be displayed on the top of the term's archive page.</p>
			<input type="hidden" id="post_ID" value="<?php echo $post_id ?>" />
			<input type="hidden" id="featured_image_id" value="<?php echo $featured['attachment'] ;?>" />

			<?php # echo get_the_post_thumbnail($post_id); ?>
		</td>
	</tr>
	<?php
}
add_action( 'edit_category_form_fields', 'largo_add_term_featured_media_button');
add_action( 'edit_tag_form_fields', 'largo_add_term_featured_media_button');

/**
 * Enqueue wordpress post editor on term edit page
 *
 * @param string $hook the page this is being called upon.
 * @since 0.5.4
 * @see largo_term_featured_media_button
 */
function largo_term_featured_media_enqueue_post_editor($hook) {
	if (!in_array($hook, array('edit.php', 'edit-tags.php')))
		return;

	wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'largo_term_featured_media_enqueue_post_editor', 1);

/**
 * Removes the embed-code, video and gallery media types from the term featured media editor
 *
 * @param array $types array of media types that can be used with the featured media editor
 * @since 0.5.4
 * @global $post Used to determine whether or not this button is being called on a post or on something else.
 */
function largo_term_featured_media_types($types) {
	global $post;
	if ( isset( $types['image'] ) && is_object($post) && $post->post_type == '_term_meta' ) {
		$ret =  array('image' => $types['image']);
		return $ret;
	}
	return $types;
}
add_filter('largo_default_featured_media_types', 'largo_term_featured_media_types', 10, 1);

/**
 * Add meta data to a term
 *
 * @param string $taxonomy
 * @param int $term_id
 * @param string $meta_key
 * @param mixed $meta_value
 * @param bool $unique
 */
function largo_add_term_meta( $taxonomy, $term_id, $meta_key, $meta_value, $unique=false ) {
	$post_id = largo_get_term_meta_post( $taxonomy, $term_id );
	return add_post_meta( $post_id, $meta_key, $meta_value, $unique );
}

/**
 * Delete meta data to a term
 *
 * @param string $taxonomy
 * @param int $term_id
 * @param string $meta_key
 * @param mixed $meta_value
 */
function largo_delete_term_meta( $taxonomy, $term_id, $meta_key, $meta_value='' ) {
	$post_id = largo_get_term_meta_post( $taxonomy, $term_id );
	return delete_post_meta( $post_id, $meta_key, $meta_value );
}

/**
 * Get meta data to a term
 *
 * @param string $taxonomy
 * @param int $term_id
 * @param string $meta_key
 * @param bool $single
 */
function largo_get_term_meta( $taxonomy, $term_id, $meta_key, $single=false ) {
	$post_id = largo_get_term_meta_post( $taxonomy, $term_id );
	return get_post_meta( $post_id, $meta_key, $single );
}

/**
 * Update meta data to a term
 *
 * @param string $taxonomy
 * @param int $term_id
 * @param string $meta_key
 * @param mixed $meta_value
 * @param mixed $prev_value
 */
function largo_update_term_meta( $taxonomy, $term_id, $meta_key, $meta_value, $prev_value='' ) {
	$post_id = largo_get_term_meta_post( $taxonomy, $term_id );
	return update_post_meta( $post_id, $meta_key, $meta_value, $prev_value );
}
