<?php

/**
 * Register our custom nav menus
 * and link menus to locations
 *
 * @since 1.0
 */
function largo_register_custom_menus() {
    $menus = array(
        'global-nav'         	=> __( 'Global Navigation', 'largo' ),
        'sticky-nav'          => __( 'Sticky Navigation', 'largo' ),
        'navbar-categories'     => __( 'Navbar Categories List', 'largo' ),
        'navbar-supplemental'	=> __( 'Navbar Supplemental Links', 'largo' ),
        'dont-miss'       		=> __( 'Don\'t Miss', 'largo' ),
        'footer'          		=> __( 'Footer Navigation', 'largo' ),
        'footer-bottom'			=> __( 'Footer Bottom', 'largo' )
    );
    register_nav_menus( $menus );

    // Avoid database writes on the frontend
    if ( ! is_admin() ) {
    	return;
    }

    //Try to automatically link menus to each of the locations.
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
add_action( 'after_setup_theme', 'largo_register_custom_menus' );

/**
 * Output a donate button from theme options
 * used by default in the global nav area
 *
 * @since 1.0
 */
if ( ! function_exists( 'largo_donate_button' ) ) {
	function largo_donate_button () {
		if ( $donate_link = of_get_option( 'donate_link' ) )
			printf('<div class="donate-btn"><a href="%1$s"><i class="icon-heart"></i>%2$s</a></div> ',
				esc_url( $donate_link ),
				esc_html( of_get_option( 'donate_button_text' ) )
			);
	}
}

/**
 * Append static labels to the beginning of the "don't miss" and footer menu areas
 * these can be set in the largo theme options
 *
 * @since 1.0
 */
if ( ! function_exists( 'largo_add_dont_miss_label' ) ) {
	function largo_add_dont_miss_label( $items, $args ) {
	    return '<li class="menu-label">' . esc_html( of_get_option( 'dont_miss_label') ) . '</li>' . $items;
	}
}
add_filter( 'wp_nav_menu_dont-miss_items', 'largo_add_dont_miss_label', 10, 2 );

if ( ! function_exists( 'largo_add_footer_menu_label' ) ) {
	function largo_add_footer_menu_label( $items, $args ) {
	    return '<li class="menu-label">' . esc_html( of_get_option( 'footer_menu_label') ) . '</li>' . $items;
	}
}
add_filter( 'wp_nav_menu_footer-navigation_items', 'largo_add_footer_menu_label', 10, 2 );

/**
 * An enhanced menu walker
 * Supports up to second level dropdown menus using appropriate markup for bootstrap
 *
 * @since 1.0
 */
class Bootstrap_Walker_Nav_Menu extends Walker_Nav_Menu {

	function start_lvl( &$output, $depth = 0, $args = array() ) {

		$indent = str_repeat( "\t", $depth );
		if ($depth == 1) {
			$output	   .= "\n$indent<ul class=\"dropdown-menu sub-menu\">\n";
		} elseif ($depth == 2) {
			$output	   .= "\n$indent<ul class=\"dropdown-menu sub-sub-menu\">\n";
		} else {
			$output	   .= "\n$indent<ul class=\"dropdown-menu\">\n";
		}
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$li_attributes = '';
		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = ($args->has_children) ? 'dropdown' : '';
		$classes[] = ($item->current || $item->current_item_ancestor) ? 'active' : '';
		$classes[] = 'menu-item-' . $item->ID;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		$attributes .= (($args->has_children) && ($depth == 0))	    ? ' class="dropdown-toggle"' : '';

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= (($args->has_children) && ($depth == 0)) ? ' <b class="caret"></b></a>' : '';
		$item_output .= (($args->has_children) && ($depth != 0)) ? ' <i class="icon-arrow-right"></i></a>' : '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
		if ( !$element )
			return;

		$id_field = $this->db_fields['id'];

		//display this element
		if ( is_array( $args[0] ) )
			$args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );
		else if ( is_object( $args[0] ) )
			$args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
		$cb_args = array_merge( array(&$output, $element, $depth), $args);
		call_user_func_array(array(&$this, 'start_el'), $cb_args);

		$id = $element->$id_field;

		// descend only when the depth is right and there are childrens for this element
		if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {

			foreach( $children_elements[ $id ] as $child ){

				if ( !isset($newlevel) ) {
					$newlevel = true;
					//start the child delimiter
					$cb_args = array_merge( array(&$output, $depth), $args);
					call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
				}
				$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
			}
				unset( $children_elements[ $id ] );
		}

		if ( isset($newlevel) && $newlevel ){
			//end the child delimiter
			$cb_args = array_merge( array(&$output, $depth), $args);
			call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
		}

		//end this element
		$cb_args = array_merge( array(&$output, $element, $depth), $args);
		call_user_func_array(array(&$this, 'end_el'), $cb_args);

	}
}