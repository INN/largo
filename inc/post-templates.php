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

/**
 * Filter the single template value, and replace it with
 * the template chosen by the user, if they chose one.
 */
if( !function_exists( 'get_post_template' ) ) {
	function get_post_template( $template ) {
		global $post;
		$custom_field = get_post_meta( $post->ID, '_wp_post_template', true );
		if ( !empty( $custom_field ) ) {
			if ( file_exists( get_stylesheet_directory() . "/{$custom_field}") ) {
				$template = get_stylesheet_directory() . "/{$custom_field}";
			} else if ( file_exists( get_template_directory() . "/{$custom_field}") ) {
				$template = get_template_directory() . "/{$custom_field}";
			}
		}
		return $template;
	}
	add_filter( 'single_template', 'get_post_template' );
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


/**
 * Remove potentially duplicated hero image after upgrade to v0.4
 * 
 * The changes to the content in this function should eventually be made
 * perminant in the database. (@see https://github.com/INN/Largo/issues/354)
 * 
 * @since v0.4
 * 
 * @param String $content the post content passed in by WordPress filter
 * @return String filtered post content.
 */
function largo_remove_hero($content) {

	global $post;

	// 1: Only worry about this if it's a single template, there's a feature image,
	// we haven't overridden the post display and we're not using the classic layout.

	if( !is_single() ) 
		return $content;

	if( !has_post_thumbnail() ) 
		return $content;

	$options = get_post_custom($post->ID);

	if( isset($options['featured-image-display'][0]) )
		return $content;

	if( of_get_option( 'single_template' ) == 'classic' )
		return $content;

	$p = explode("\n",$content);
	
	// 2: Find an image (regex)
	//
	// Creates the array:
	// 		$matches[0] = <img src="..." class="..." id="..." />
	//		$matches[1] = value of src.

	$pattern = '/<img\s+[^>]*src="([^"]*)"[^>]*>/';
	$hasImg = preg_match($pattern,$p[0],$matches);

	// 3: if there's no image, there's nothing to worry about.

	if( !$hasImg )
		return $content;

	$imgDom = $matches[0];
	$src = $matches[1];

	// 4: Compare the src url to the feature image url.
	// If they're the same, remove the top image.

	$featureImgId = get_post_thumbnail_id();
	$pImgId = largo_url_to_attachmentid($matches[1]);

	// Try a second way to get the attachment id

	$pattern = '/class="([^"]+)"/';
	preg_match($pattern,$imgDom,$classes);

	$classes = $classes[1];

	if( !$pImgId ) {
		$pattern = '/wp-image-(\d+)/';
		preg_match($pattern,$classes,$imgId);
		$pImgId = $imgId[1];
	}

	if( !($pImgId == $featureImgId) ) 
		return $content;
	
	// 5: Check if it's a full width image, or if the image is not large enough to be a hero.

	if( strpos($classes,'size-small') || strpos($classes,'size-medium') ) 
		return $content;
	
	// 6: Else, shift the first paragraph off the content and return.

	array_shift($p);
	$content = implode("\n",$p);
	
	return $content;

}
add_filter('the_content','largo_remove_hero',1);


/**
 * Retrieves the attachment ID from the file URL
 * (or that of any thumbnail image)
 * 
 * @since v0.4
 * @see https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
 * 
 * @return Int ID of post attachment (or false if not found)
 */ 
function largo_url_to_attachmentid($url) {

	global $wpdb;
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url )); 
    
    if( !empty( $attachment ) ) 
    	return $attachment[0];

    // Check if there's a size in the url and remove it.

    $url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $url );
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url )); 

    if( !empty( $attachment ) ) 
    	return $attachment[0];
    else
    	return false;

}

/**
 * Given a post type and an optional context, return the partial that should be loaded for that sort of post.
 *
 * The default context is search, and the context isn't actually used by this function,
 * but it is passed to the filter this function runs, largo_partial_by_post_type.
 *
 * @link https://github.com/INN/Largo/issues/1023
 * @param string $partial Required, the default partial in this context.
 * @param string $post_type Required, the given post's post type
 * @param string $context Required, the context of this partial.
 * @return string The partial that should be loaded. This defaults to 'search'.
 * @filter largo_partial_by_post_type
 * @since 0.5.4
 */
function largo_get_partial_by_post_type($partial, $post_type, $context) {
	// Remove this conditional in #926.
	if ($post_type == 'argolinks') {
		$partial = 'argolinks';
	}

	/**
	 * Filter the output of largo_get_partial_by_post_type
	 *
	 * When building your own filter, you must set the fourth parameter of add_filter to 3:
	 *
	 *     function your_filter_name($partial, $post_type, $context) {
	 *         // things
	 *         return $partial;
	 *     }
	 *     add_filter('largo_partial_by_post_type', 'your_filter_name', 10, 3);
	 *                                                                      ^
	 * Without setting '3', your filter will not be passed the $post_type or $context arguments.
	 * In order to set '3', you must set the third parameter of add_filter, which defaults to 10. it is safe to leave that at 10.
	 *
	 * @since 0.5.4
	 * @param string $partial The string representing the template partial to use for the current post
	 * @param string $post_type The current post's post_type
	 * @param string $context The context in which this filter is being called, defaulting to search.
	 */
	$partial = apply_filters('largo_partial_by_post_type', $partial, $post_type, $context);

	return $partial;
}
