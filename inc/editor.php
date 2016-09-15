<?php

/**
 * Add the tinymce plugin to insert modules into posts
 *
 * @since 0.3
 */
function largo_add_mce_plugin( $plugin_array ) {
	$plugin_array['modulize'] = get_template_directory_uri() . '/js/tinymce/plugins/largo/editor_plugin.js';
	return $plugin_array;
}

function largo_register_mce_buttons( $buttons ) {
	array_push( $buttons, '|', 'modulize' );
	return $buttons;
}

function largo_add_mce_buttons() {
	if ( get_user_option( 'rich_editing' ) == 'true' ) {
		add_filter( 'mce_external_plugins', 'largo_add_mce_plugin', 4 );
		add_filter( 'mce_buttons', 'largo_register_mce_buttons', 4 );
	}
}
add_action( 'init', 'largo_add_mce_buttons' );

/**
 * Add the module shortcode (used for pullquotes and asides within posts)
 * This is no longer used but is included here for backwards compatibility
 *
 * @since 0.3
 */
function largo_module_shortcode( $atts, $content, $code ) {
    extract( shortcode_atts( array(
        'align' => 'left',
        'width' => 'half',
        'type' => 'aside',
    ), $atts ) );

    return sprintf( '<aside class="module %s %s %s">%s</aside>', $type, $align, $width, $content );
}
add_shortcode( 'module', 'largo_module_shortcode' );

/**
 * Modify TinyMCE editor to remove H1.
 *
 * @since 0.5.5
 */
function tiny_mce_remove_unused_formats($init) {
	// Add block format elements you want to show in dropdown
	$init['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Pre=pre';
	return $init;
}
add_filter( 'tiny_mce_before_init', 'tiny_mce_remove_unused_formats' );

/**
 * Remove weird span tags inserted by TinyMCE
 *
 * @since 0.3
 */
function largo_tinymce_config( $init ) {
	if ( isset( $init['extended_valid_elements'] ) ) {
		$init['extended_valid_elements'] .= "span[!class|!style]";
	} else {
		$init['extended_valid_elements'] = "span[!class|!style]";
	}
	return $init;
}
add_filter( 'tiny_mce_before_init', 'largo_tinymce_config' );
