<?php
/**
 * Contains functions and tool for transitioning between Largo 0.2 and Largo 0.3
 */

/**
 * Returns current version of largo
 */
function largo_version() {
	$largo = wp_get_theme( 'largo' );
	return floatval( $largo->get( 'Version' ) );
}

/**
 * Checks if widgets need to be placed by checking old theme settings
 */
function largo_need_updates() {

	// only do this for newer versions of largo
	if ( largo_version() < 0.3 ) return false;

	// try to figure out which versions of the options are stored. Implemented in 0.3
	if ( of_get_option('largo_version') ) {
		$old_version = floatval( of_get_option('largo_version') );
		if ( $old_version < largo_version() ) {
			return true;
		} else {
			return false;
		}
	}

	// if 'largo_version' isn't present, the settings are old!
	return true;
}

/**
 * Performs various database updates upon Largo version change. Fairly primitive as of 0.3
 *
 * @since 0.3
 */
function largo_perform_update() {
	if ( largo_need_updates() ) {
		if ( largo_version() == 0.3 ) largo_update_widgets();
	}
}
add_action( 'widgets_init', 'largo_perform_update', 20 );

/**
 * Upgrades for moving from 0.2 to 0.3
 * In which many theme options became widgets
 */
function largo_update_widgets() {

	/* check and add if necessary:
		social_icons_display ('btm' or 'both')
		-- add series widget
		show_tags
		show_author_box
		show_related_content
		show_next_prev_nav_single
	*/
	$checks = array();

	$checks['social_icons_display'] = array(
		'values' => array('btm', 'both'),
		'widget' => 'largo-follow',
		'settings' => array( 'title' => '' ),
	);

	//this is a dummy check
	$checks['in_series'] = array(
		'values' => NULL,
		'widget' => 'largo-post-series-links',
		'settings' => array('title' => 'Related Series'),
	);

	$checks['show_tags'] = array(
		'values' => array(1),
		'widget' => 'largo-tag-list',
		'settings' => array('title' => 'Filed Under:', 'tag_limit' => 20),
	);

	$checks['show_author_box'] = array(
		'values' => array('1'),
		'widget' => 'largo-author-bio',
		'settings' => array('title' => 'Author'),
	);

	$checks['show_related_content'] = array(
		'values' => array('1'),
		'widget' => 'largo-explore-related',
		'settings' => array('title' => 'More About', 'topics' => 6, 'posts' => 3),
	);

	$checks['show_next_prev_nav_single'] = array(
		'values' => array('1'),
		'widget' => 'largo-prev-next-post-links',
		'settings' => array(),
	);

	//loop thru, see if value is present, then see if widget exists, if not, create one
	foreach( $checks as $option => $i ) {
		$opt = of_get_option( $option );
		if ( $i['values'] === NULL || in_array($opt, $i['values']) ) {
			//we found an option that suggests we need to add a widget.
			//if there's not aleady one present, add it
			if ( !largo_widget_in_region( $i['widget'] ) ) {
				largo_instantiate_widget( $i['widget'], $i['settings'], 'article-bottom');
			}
		}
	}
}

/**
 * Checks to see if a given widget is in a given region already
 */
function largo_widget_in_region( $widget_name, $region = 'article-bottom' ) {

	$widgets = get_option( 'sidebars_widgets ');

	if ( !isset( $widgets[$region] ) ) {
		return new WP_Error( 'region-missing', __('Invalid region specified.') );
	}

	foreach( $widgets[$region] as $key => $widget ) {
		if ( stripos( $widget, $widget_name ) === 0 ) return true;	//we found a copy of this widget! Note this may return a false positive if the widget we're checking is the same name (but shorter) as another kind of widget
	}
	return false;	// the widget wasn't there

}

/**
 * Inserts a widget programmatically.
 * This is slightly dangerous as it makes some assumptions about existing plugins
 * if $instance_settings are wrong, bad things might happen
 */
function largo_instantiate_widget( $kind, $instance_settings, $region ) {

	$defaults = array(
		'widget_class' => 'default',
		'hidden_desktop' => 0,
		'hidden_tablet' => 0,
		'hidden_phone' => 0,
		'title_link' => ''
	);

	$instance_id = 2; 	// default, not sure why it always seems to start at 2
	$full_kind = 'widget_' . str_replace("_", "-", $kind) . '-widget';

	// step 1: add the widget instance to the database and get the ID
	$widget_instances = get_option( $kind );

	// no instances of this exist, yay
	if ( !$widget_instances ) {
		update_option( $full_kind,
			array(
				2 => wp_parse_args( $instance_settings, $defaults ),
				'_multiwidget' => 1,
			)
		);

	} else {
		//figure out what ID we're creating. Don't just use count() as things might get deleted or something...
		//there's probably a smarter way to do this...
		while ( array_key_exists( $instance_id, $widget_instances) ) {
			$instance_id++;
		}

		//pop off _multiwidget, add our element to the end, then add _multiwidget back
		$new_instances = array_pop( $widget_instances );
		$new_instances[ $instance_id ] = wp_parse_args( $instance_settings, $defaults );
		$new_instances[ '_multiwidget' ] = 1;
		update_option( $full_kind, $new_instances );
	}

	// step 2: add the widget instance we just created to the region; this isn't so bad
	$region_widgets = get_option( 'sidebars_widgets' );
	$region_widgets[ $region ][] = $kind . '-widget-' . $instance_id;
	update_option( 'sidebars_widgets', $region_widgets );

}