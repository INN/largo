<?php
function argo_register_custom_menus() {
    $menus = array(
        'global-nav'         => 'Global Navigation',
        'categories'      => 'Categories List',
        'dont-miss'       => "Don't Miss",
        'footer'          => 'Footer Navigation',
    );
    register_nav_menus( $menus );

    /*
     * Try to automatically link menus to each of the locations.
     */
    foreach ( $menus as $location => $label ) {
        // if a location isn't wired up...
        if ( ! has_nav_menu( $location ) ) {

            // get or create the nav menu
            $nav_menu = wp_get_nav_menu_object( $label );
            if ( ! $nav_menu ) {
                $new_menu_id = wp_create_nav_menu( $label );
                $nav_menu = wp_get_nav_menu_object( $new_menu_id );
            }

            // wire it up to the location
            $locations = get_theme_mod( 'nav_menu_locations' );
            $locations[ $location ] = $nav_menu->term_id;
            set_theme_mod( 'nav_menu_locations', $locations );
        } 
    }
}

add_action( 'after_setup_theme', 'argo_register_custom_menus' );

function argo_add_dont_miss_label( $items, $args ) {
    // XXX: make this label configurable via a setting
    return "<li><h4>Don't miss</h4></li>" . $items;
}
add_filter( 'wp_nav_menu_dont-miss_items', 'argo_add_dont_miss_label', 10, 2 );


class Argo_Categories_Walker extends Walker {
    /*
     * Custom menu walker to create mega menus for Category nav.
     */
    var $tree_type = array( 'post_type', 'taxonomy', 'custom' );
    var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

    function start_el( &$output, $item, $depth, $args ) {
        global $wp_query;
        $li_class = ( $output ) ? '' : ' class="first"';
        $output .= '<li' . $li_class . '><a href="' . $item->url . '">' . 
             $item->title  . '</a>';
        $output .= '<div class="sub container_12"><ul><li><div class="inner-menu">';

        $output .= '<div class="mega-feature grid_4">';
        $cat = get_category( $item->object_id );
        $output .= '<h2>' . $cat->description . '</h2>';
        $output .= '</div>';

        $output .= '<div class="category-topics grid_4">';
        $output .= '<h3>RELATED TOPICS</h3>';
        $output .= argo_get_related_topics_for_category( $item );
        $output .= '</div>';

        $output .= '<div class="category-articles grid_4">';
        $output .= '<h3>LATEST POSTS</h3>';
        $output .= argo_get_latest_posts_for_category( $item );
        $output .= '</div>';

        $output .= '</div></li></ul></div>';
    }

    function end_el( &$output, $item, $depth ) {
        $output .= "</li>\n";
    }
}
?>