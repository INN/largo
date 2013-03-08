<?php
/**
 *Derived from Single Post Template 1.3 plugin by Nathan Rice ( http://www.nathanrice.net/plugins
 */

//	This function scans the template files of the active theme,
//	and returns an array of [Template Name => {file}.php]
if(!function_exists('get_post_templates')) {
function get_post_templates() {
	$themes = get_themes();
	$theme = get_current_theme();
	$templates = $themes[$theme]['Template Files'];
	$post_templates = array();

	$base = array(trailingslashit(get_template_directory()), trailingslashit(get_stylesheet_directory()));

	foreach ((array)$templates as $template) {
		$template = WP_CONTENT_DIR . str_replace(WP_CONTENT_DIR, '', $template);
		$basename = str_replace($base, '', $template);

		// don't allow template files in subdirectories
		// if (false !== strpos($basename, '/'))
		//	continue;

		$template_data = implode('', file( $template ));

		$name = '';
		if (preg_match( '|Single Post Template:(.*)$|mi', $template_data, $name))
			$name = _cleanup_header_comment($name[1]);

		if (!empty($name)) {
			if(basename($template) != basename(__FILE__))
				$post_templates[trim($name)] = $basename;
		}
	}

	return $post_templates;

}}

//	build the dropdown items
if(!function_exists('post_templates_dropdown')) {
function post_templates_dropdown() {
	global $post;
	$post_templates = get_post_templates();

	foreach ($post_templates as $template_name => $template_file) { //loop through templates, make them options
		if ($template_file == get_post_meta($post->ID, '_wp_post_template', true)) { $selected = ' selected="selected"'; } else { $selected = ''; }
		$opt = '<option value="' . $template_file . '"' . $selected . '>' . $template_name . '</option>';
		echo $opt;
	}
}}

//	Filter the single template value, and replace it with
//	the template chosen by the user, if they chose one.
add_filter('single_template', 'get_post_template');
if(!function_exists('get_post_template')) {
function get_post_template($template) {
	global $post;
	$custom_field = get_post_meta($post->ID, '_wp_post_template', true);
	if(!empty($custom_field) && file_exists(TEMPLATEPATH . "/{$custom_field}")) {
		$template = TEMPLATEPATH . "/{$custom_field}"; }
	return $template;
}}

//	Everything below this is for adding the extra box
//	to the post edit screen so the user can choose a template

//	Adds a custom section to the Post edit screen
add_action('admin_menu', 'largo_pt_add_custom_box');
function largo_pt_add_custom_box() {
	if(get_post_templates() && function_exists( 'add_meta_box' )) {
		add_meta_box( 'pt_post_templates', __( 'Post Attributes', 'largo' ),
			'largo_pt_inner_custom_box', 'post', 'side', 'default' ); //add the box in the righthand column
	}
}

//	Prints the inner fields for the custom post/page section
function largo_pt_inner_custom_box() {
	global $post;
	echo '<p><strong>Template</strong></p>';
	// Use nonce for verification
	wp_nonce_field('largo_post_template', 'pt_noncename' );
	// The actual fields for data entry
	echo '<label class="hidden" for="post_template">' . __("Post Template", 'largo' ) . '</label>';
	echo '<select name="_wp_post_template" id="post_template" class="dropdown">';
	echo '<option value="">Default</option>';
	post_templates_dropdown(); //get the options
	echo '</select>';
	echo '<p>' . __("Select the Post Template you wish this post to use.", 'largo' ) . '</p>';
}

//	When the post is saved, saves our custom data
add_action('save_post', 'largo_pt_save_postdata', 1, 2); // save the custom fields
function largo_pt_save_postdata($post_id, $post) {

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !wp_verify_nonce( $_POST['pt_noncename'], 'largo_post_template' )) {
		return $post->ID;
	}

	// Is the user allowed to edit the post?
	if ( 'post' !== $_POST['post_type'] || !current_user_can( 'edit_post', $post->ID )) {
		//not a post, or no privs. Bail!
		return $post->ID;
	}

	// OK, we're authenticated: we need to find and save the data

	// We'll put the data into an array to make it easier to loop though and save
	$mydata['_wp_post_template'] = $_POST['_wp_post_template'];
	// Add values of $mydata as custom fields
	foreach ($mydata as $key => $value) {
		if( $post->post_type == 'revision' ) return; //don't store custom data twice
		$value = implode(',', (array)$value); //if $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) {
			update_post_meta($post->ID, $key, $value); //if the custom field already has a value, update it
		} else {
			add_post_meta($post->ID, $key, $value);//if the custom field doesn't have a value, add the data
		}
		if(!$value) delete_post_meta($post->ID, $key); //and delete if blank
	}
}