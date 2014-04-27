<?php
/**
 * Adds ability to select a custom post template for single posts
 * Derived from Single Post Template 1.3 plugin by Nathan Rice ( http://www.nathanrice.net/plugins
 *
 * @since 1.0
 */

//	This function scans the template files of the active theme,
//	and returns an array of [Template Name => {file}.php]
if( !function_exists( 'get_post_templates' ) ) {
	function get_post_templates() {
		$theme = wp_get_theme();
		$templates = $theme->get_files( 'php', 1, true );
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

	}
}

//	build the dropdown items
if( !function_exists( 'post_templates_dropdown' ) ) {
	function post_templates_dropdown() {
		global $post;
		$post_templates = get_post_templates();

		foreach ( $post_templates as $template_name => $template_file ) { //loop through templates, make them options
			if ( $template_file == get_post_meta( $post->ID, '_wp_post_template', true )) { $selected = ' selected="selected"'; } else { $selected = ''; }
			$opt = '<option value="' . $template_file . '"' . $selected . '>' . $template_name . '</option>';
			echo $opt;
		}
	}
}

//	Filter the single template value, and replace it with
//	the template chosen by the user, if they chose one.
add_filter( 'single_template', 'get_post_template' );
if( !function_exists( 'get_post_template' ) ) {
	function get_post_template( $template ) {
		global $post;
		$custom_field = get_post_meta( $post->ID, '_wp_post_template', true );
		//TO DO: This needs to be smarter about parent/child theme stuff with get_template_directory() and get_stylesheet_directory()
		if( !empty( $custom_field ) && file_exists( get_template_directory() . "/{$custom_field}") ) {
			$template = get_template_directory() . "/{$custom_field}"; }
		return $template;
	}
}

/**
 * Modelled on is_page_template, determine if we are in a single post template.
 * You can optionally provide a template name and then the check will be
 * specific to that template.
 *
 * @since 1.0
 * @uses $wp_query
 *
 * @param string $template The specific template name if specific matching is required.
 * @return bool True on success, false on failure.
 */
function is_post_template( $template = '' ) {
	if ( ! is_single() )
		return false;

	$post_template = get_post_meta( get_queried_object_id(), '_wp_post_template', true );

	if ( empty( $template ) )
		return (bool) $post_template;

	if ( $template == $post_template )
		return true;

	if ( 'default' == $template && ! $post_template )
		return true;

	return false;
}