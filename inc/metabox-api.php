<?php
/**
 * Contains function definitions for hooking fields and boxes into Largo
 * Relies on the global variable $largo, which feels hack-ish but creates the most convenience
 */

/**
 * First things first: check if $largo['meta'] exists
 * If it does, then this file has already been included (likely by a child theme) and should stop.
 * Otherwise we get function redeclarations.
 * Since we're using include_once() this is unlikely, but possible and worth checking.
 */
if ( isset($largo) && array_key_exists('meta', $largo) ) return;

$largo['meta'] = array(
	'boxes' => array(),		// the metaboxes to generate, including callbacks for the content
	'inputs' => array(),	// input names to process with largo_meta_box_save()
);

/**
 * Call this function to define a metabox container
 *
 * @since 0.2
 *
 * @param string $id Required. HTML 'id' attribute of edit screen section. Corresponds to first argument of add_meta_box()
 * @param string $title Required. Title of the metabox, visible to user
 * @param array|string $screens Optional. Name of post type(s) this box should appear on. Values correspond to $post_type argument of add_meta_box(). Defaults to 'post'
 * @param string $context Optional. The context within the page where the boxes should show ('normal', 'advanced', 'side'). Defaults to 'advanced'
 * @param string $priority Optional. The priority within the context where the boxes should show ('high', 'low', 'core', 'default'). Defaults to 'default'
 *
 */
function largo_add_meta_box( $id, $title, $callbacks = array(), $post_types = 'post', $context = 'advanced', $priority = 'default' ) {
	global $largo;

	if ( is_string($post_types) ) $post_types = array($post_types);
	if ( is_string($callbacks) ) $callbacks = array($callbacks);
	if ( is_null($callbacks) ) $callbacks = array();

	$largo['meta']['boxes'][$id] = array(
		'title' => __( $title, 'largo'),
		'callbacks' => $callbacks,
		'screens' => $post_types,
		'context' => $context,
		'priority' => $priority
	);
}

/**
 * Call this function to add a field to an (existing) metabox container
 *
 * @since 0.2
 *
 * @param string $callback Required. Function that outputs the markup for this field
 * @param string $box_id Required. HTML 'id' attribute of the box this field goes into
 *
 * TODO: Implement some sort of weighting protocol to control ordering of fields within a metabox (right now it's just FIFO)
 */
function largo_add_meta_content( $callback, $box_id ) {
	global $largo;

	// Create this metabox if one hasn't been defined... assumes just 'post'
	if ( !array_key_exists( $box_id, $largo['meta']['boxes'] ) ){
		largo_add_meta_box( $box_id, 'Meta Information' );
	}

	// Add this field to the array
	$largo['meta']['boxes'][$box_id]['callbacks'][] = $callback;
}

/**
 * Call this function from within a largo_add_meta_field callback to register an input as a post meta field
 *
 * @since 0.2
 *
 * @param string|array $input_names Name of a single input or array of input names to add as meta fields
 *
 * TODO: Include a validation parameter so meta fields can be validated easily.
 */
function largo_register_meta_input( $input_names, $presave_fn=null ) {
	global $largo;
	$largo_metas = get_option('largo_meta_inputs');
	if ( is_string( $input_names ) ) $input_names = array($input_names);

	foreach( $input_names as $name ) {
		if (! in_array($name, $largo_metas))
		$largo_metas[] = array( 'name' => $name, 'presave_fn' => $presave_fn );
	}

	update_option('largo_meta_inputs', $largo_metas);
}

/**
 * Private function to actually generate the metaboxes
 */
function _largo_metaboxes_generate() {
	global $largo;

	foreach( $largo['meta']['boxes'] as $box_id => $settings ) {
		foreach( $settings['screens'] as $screen ) {
			add_meta_box(
				$box_id,
				$settings['title'],
				'_largo_metaboxes_content',
				$screen,
				$settings['context'],
				$settings['priority'],
				$settings['callbacks']
			);
		}
	}
}
add_action( 'add_meta_boxes', '_largo_metaboxes_generate', 12 ); // give everything time to do its thing before we run


/**
 * Private function to generate fields/markup within largo metaboxes
 *
 */
function _largo_metaboxes_content( $post, $callbacks = array() ) {
	// loop thru the $callbacks array and execute each of them
	// $callbacks is not actually just $settings['callbacks']
	// instead it has array('id' => $id, 'title' => $title, 'callback' => $callback, 'args' => $callback_args);
	foreach( $callbacks['args'] as $callback ) {
		$callback( $post );
	}
}


/**
 * Private function to handle saving inputs registered with largo_register_meta_input()
 *
 * TODO: Implement validation for inputs
 */
 // Save our custom meta box values as custom fields
function _largo_meta_box_save( $post_id ) {
	global $post, $largo;

	// Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// if our nonce isn't there, or we can't verify it, bail
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'largo_meta_box_nonce' ) ) return;

	// if our current user can't edit this post, bail
	if( !current_user_can( 'edit_post' ) ) return;

	// set up our array of data
	$mydata = array();
	$registered_inputs = get_option('largo_meta_inputs', array());
	foreach ( $registered_inputs as $input_name ) {

		if ( is_array( $input_name ) ) {
			$mydata[ $input_name['name'] ] = $_POST[ $input_name['name'] ];

			if ( is_callable( $input_name['presave_fn'] ) ) {
				$mydata[ $input_name['name'] ] = call_user_func( $input_name['presave_fn'], $mydata[ $input_name['name'] ], $input_name['name'] );
			}
		} else {
			$mydata[ $input_name ] = $_POST[ $input_name ];
		}
	}

	// process our posts
	foreach ( $mydata as $key => $value ) {
		if ( get_post_meta( $post->ID, $key, FALSE ) ) {
			update_post_meta( $post->ID, $key, $value ); //if the custom field already has a value, update it
		} else {
			add_post_meta( $post->ID, $key, $value );//if the custom field doesn't have a value, add the data
		}
		if ( !$value ) delete_post_meta( $post->ID, $key ); //and delete if blank
	}

}
add_action( 'save_post', '_largo_meta_box_save' );
