<?php

if ( !defined( 'LARGE_WIDTH') ) {
	define( 'LARGE_WIDTH', 1170 );
}

if ( !defined( 'MEDIUM_WIDTH') ) {
	define( 'MEDIUM_WIDTH', 771 );
}

function largo_create_image_sizes() {
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 140, 140, true ); // skybox thumbnail
    add_image_size( '60x60', 60, 60, true ); // in case you missed it thumbnail
    add_image_size( 'half', 336, 9999 ); // topic featured image
    add_image_size( 'medium', MEDIUM_WIDTH, 9999 ); // medium width scaling
    add_image_size( 'large', LARGE_WIDTH, 9999 ); // large width scaling
}
add_action( 'after_setup_theme', 'largo_create_image_sizes' );

/**
 * largo_get_image_tag(): Renders an <img /> tag for attachments, scaling it
 * to $size and guaranteeing that it's not wider than the content well.
 *
 * This is largely taken from get_image_tag() in wp-includes/media.php.
 */
function largo_get_image_tag( $html, $id, $alt, $title, $align, $size ) {
    // Never allow an image wider than the LARGE_WIDTH
    if ( $size == 'full' ) {
        list( $img_src, $width, $height ) = wp_get_attachment_image_src( $id, $size );
        if ( $width > LARGE_WIDTH ) {
            $size = 'large';
        }
    }

    list( $img_src, $width, $height ) = image_downsize( $id, $size );
    $hwstring = image_hwstring( $width, $height );

    // XXX: may not need all these classes
    $class = 'align' . esc_attr( $align ) .' size-' .  esc_attr( $size ) .
             ' wp-image-' . $id;
    $class = apply_filters( 'get_image_tag_class', $class, $id, $align,
                            $size );

    $html = '<img src="' . esc_attr( $img_src ) .
        '" alt="' . esc_attr( $alt ) .
        '" title="' . esc_attr( $title ) . '" ' .
        $hwstring . 'class="' . $class . '" />';

    return $html;
}
add_filter( 'get_image_tag', 'largo_get_image_tag', 10, 6 );

define( 'DEFAULT_ALIGNMENT', 'right' );
function largo_handle_image_layouts( $atts ) {
    // Set the max width
    // XXX: this number should live somewhere else
    // NEW: not sure we still need this?
    if ( ! $atts[ 'width' ] )
        $atts[ 'width' ] = 771;

    if ( $atts[ 'width' ] >= 771 ) {
        $atts[ 'align' ] = 'aligncenter';
    }
    else {
        if ( ! $atts[ 'align' ] )
            $atts[ 'align' ] = DEFAULT_ALIGNMENT;
    }

    return $atts;
}
add_filter( 'largo_image_layout_defaults', 'largo_handle_image_layouts', 10, 1 );

?>