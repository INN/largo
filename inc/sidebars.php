<?php

/**
 * Register our sidebars and other widget areas
 *
 * @todo move the taxonomy landing page sidebar registration here
 *  (currently in inc/wp-taxonomy-landing/functions/cftl-admin.php)
 * @since 1.0
 */
function largo_register_sidebars() {
	$sidebars = array (
		// the default widget areas
		array (
			'name'	=> __( 'Main Sidebar', 'largo' ),
			'desc' 	=> __( 'The sidebar for the homepage. If you do not add widgets to any of the other sidebars, this will also be used on all of the other pages of your site.', 'largo' ),
			'id' 	=> 'sidebar-main'
		),
		array (
			'name' 	=> __( 'Single Sidebar', 'largo' ),
			'desc' 	=> __( 'The sidebar for posts and pages', 'largo' ),
			'id' 	=> 'sidebar-single'
		),
		array (
			'name' 	=> __( 'Footer 1', 'largo' ),
			'desc' 	=> __( 'The first footer widget area.', 'largo' ),
			'id' 	=> 'footer-1'
		),
		array (
			'name' 	=> __( 'Footer 2', 'largo' ),
			'desc' 	=> __( 'The second footer widget area.', 'largo' ),
			'id' 	=> 'footer-2'
		),
		array(
			'name' 	=> __( 'Footer 3', 'largo' ),
			'desc' 	=> __( 'The third footer widget area.', 'largo' ),
			'id' 	=> 'footer-3'
		),
		array(
			'name' 	=> __( 'Article Bottom', 'largo' ),
			'desc' 	=> __( 'Footer widget area for posts', 'largo' ),
			'id' 	=> 'article-bottom'
		),
		array(
			'name' 	=> __( 'Homepage Alert', 'largo' ),
			'desc' 	=> __( 'Region atop homepage reserved for breaking news and announcements', 'largo' ),
			'id' 	=> 'homepage-alert'
		),	);

	// optional widget areas
	if ( of_get_option( 'use_topic_sidebar' ) ) {
		$sidebars[] = array(
			'name' 	=> __( 'Archive/Topic Sidebar', 'largo' ),
			'desc' 	=> __( 'The sidebar for category, tag and other archive pages', 'largo' ),
			'id' 	=> 'topic-sidebar'
		);
	}
	if ( of_get_option( 'use_before_footer_sidebar' ) ) {
		$sidebars[] = array(
			'name' 	=> __( 'Before Footer', 'largo' ),
			'desc' 	=> __( 'Full-width area immediately above footer', 'largo' ),
			'id' 	=> 'before-footer'
		);
	}
	if ( of_get_option('footer_layout') == '4col' ) {
		$sidebars[] = array(
			'name' 	=> __( 'Footer 4', 'largo' ),
			'desc' 	=> __( 'The fourth footer widget area.', 'largo' ),
			'id' 	=> 'footer-4'
		);
	}
	if ( of_get_option('homepage_layout') == '3col' ) {
		$sidebars[] = array(
			'name' 	=> __( 'Homepage Left Rail', 'largo' ),
			'desc' 	=> __( 'An optional widget area that, when enabled, appears to the left of the main content area on the homepage.', 'largo' ),
			'id' 	=> 'homepage-left-rail'
		);
	}
	if ( of_get_option('homepage_bottom') == 'widgets' ) {
		$sidebars[] = array(
			'name' 	=> __( 'Homepage Bottom', 'largo' ),
			'desc' 	=> __( 'An optional widget area at the bottom of the homepage', 'largo' ),
			'id' 	=> 'homepage-bottom'
		);
	}

	// @todo - probably add an option to enable this because not everyone is going to have ads here
	// @todo - add additional widget area in the footer for a leaderboard ad unit there too
	$sidebars[] = array(
		'name' 	=> __( 'Header Ad Zone', 'largo'),
		'desc' 	=> __( 'An optional leaderboard ad zone above the main site header', 'largo' ),
		'id' 	=> 'header-ads'
	);

	// user-defined custom widget areas
	$custom_sidebars = preg_split( '/$\R?^/m', of_get_option( 'custom_sidebars' ) );
	if ( is_array( $custom_sidebars ) ) {
		foreach( $custom_sidebars as $sidebar ) {
			$sidebar_slug = largo_make_slug( $sidebar );
			if ( $sidebar_slug ) {
				$sidebars[] = array(
					'name' 	=> __( $sidebar, 'largo' ),
					'desc' 	=> '',
					'id' 	=> $sidebar_slug
				);
			}
		}
	}

	// register the active widget areas
	foreach ( $sidebars as $sidebar ) {
		register_sidebar( array(
			'name' 			=> $sidebar['name'],
			'description' 	=> $sidebar['desc'],
			'id' 			=> $sidebar['id'],
			'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
			'after_widget' 	=> "</aside>",
			'before_title' 	=> '<h3 class="widgettitle">',
			'after_title' 	=> '</h3>',
		) );
	}
}
add_action( 'widgets_init', 'largo_register_sidebars' );

/**
 * Helper function to transform user-entered text into WP-compatible slugs
 *
 * @since 1.0
 */
function largo_make_slug($string, $maxLength = 63) {
  $result = preg_replace("/[^a-z0-9\s-]/", "", strtolower($string));
  $result = trim(preg_replace("/[\s-]+/", " ", $result));
  $result = trim(substr($result, 0, $maxLength));
  return preg_replace("/\s/", "-", $result);
}

/**
 * Builds a dropdown menu of the custom sidebars
 * Used in the meta box on post/page edit screen and landing page edit screen
 *
 * @since 1.0
 */
if( !function_exists( 'custom_sidebars_dropdown' ) ) {
	function custom_sidebars_dropdown( $selected = '', $skip_default = false, $post_id = NULL ) {
		global $wp_registered_sidebars, $post;
		$the_id = ( $post_id ) ? $post_id : $post->ID ;
		$custom = ( $selected ) ? $selected : get_post_meta( $the_id, 'custom_sidebar', true );
		$val = ( $custom ) ? $custom : 'default';

		// Add a default option
		if ( ! $skip_default ) {
			$output .= '<option value="default" ';
			$output .= selected( 'default', $val, false );
			$output .= '>' . __( 'Default', 'largo' ) . '</option>';
		}

		// Add a 'none' option
		$output .= '<option value="none" ';
		$output .= selected( 'none', $val, false );
		$output .= '>' . __( 'None', 'largo' ) . '</option>';

		// Filter list of sidebars to exclude those we don't want users to choose
		$excluded = array(
			'Footer 1', 'Footer 2', 'Footer 3', 'Article Bottom', 'Header Ad Zone'
		);
	  // Let others change the list
	  $excluded = apply_filters( 'largo_excluded_sidebars', $excluded );
		// Fill the select element with all registered sidebars that are custom
		foreach( $wp_registered_sidebars as $sidebar_id => $sidebar ) {
			//check if excluded
			if ( in_array( $sidebar_id, $excluded ) || in_array( $sidebar['name'], $excluded ) ) continue;

			$output .= '<option value="' . $sidebar_id . '" ' . selected($sidebar_id, $val, false) . '>' . $sidebar['name'] . '</option>';
		}

		echo $output;
	}
}

/**
 * Render the widget setting fields on the widget page
 */
function largo_widget_settings() {
	?>
 		<div class="advance-widget-settings">
 			<div class="advance-widget-settings-title"><?php _e( 'Largo Sidebar Options', 'largo' ); ?></div>
			<div id="optionsframework-metabox" class="metabox-holder">
			    <div id="optionsframework" class="postbox">
					<form action="options.php" method="post">
						<div> <?php // Extra open <div> because optinosframework_fields() adds an extra closing </div> ?>
					<?php 
					// Prints hidden tags for WP's options.php to save the value
					settings_fields('optionsframework');
					
					// Print all the currently saved values for those fields that aren't outputted
					$options_to_show = optionsframework_options();
					$config = get_option( 'optionsframework', array() );
					// Gets the unique option id
					if ( isset( $config['id'] ) ) {
						$option_name = $config['id'];
					}
					else {
						$option_name = 'optionsframework';
					};

					$current_values = get_option( $option_name, array() );

					foreach ( $options_to_show as $key => $field ) {
						if ( isset($field['id']) && isset($current_values[$field['id']]) ) {
							unset( $current_values[$field['id']] );
						}
					}

					foreach ( $current_values as $key => $val ) {
						echo '<input type="hidden" name="', esc_attr( $option_name . '[' . $key . ']'), '" value="', esc_attr( $val ) ,'" />';
					}

					// Prints the fields
					optionsframework_fields(); /* Settings */ ?>
					<div id="optionsframework-submit">
						<input type="submit" class="button-primary" name="update" value="<?php esc_attr_e( 'Save Options', 'options_framework_theme' ); ?>" />
						<input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e( 'Restore Defaults', 'options_framework_theme' ); ?>" onclick="return confirm( '<?php print esc_js( __( 'Click OK to reset. Any theme settings will be lost!', 'options_framework_theme' ) ); ?>' );" />
						<br class="clear" />
					</div>
					</form>
				</div>
			</div>
		</div>
	<?php
}
add_action( 'widgets_admin_page', 'largo_widget_settings' );

/**
 * Load up the scripts for options framework on the widgets
 */
function largo_load_of_script_for_widget( $hook ) {

	if ( $hook == 'widgets.php' ) {
		optionsframework_load_scripts( 'appearance_page_options-framework' );
		optionsframework_load_styles();
		wp_enqueue_style( 'largo-widgets-php', get_template_directory_uri() . '/css/widgets-php.css');
	}
}
add_action('admin_enqueue_scripts', 'largo_load_of_script_for_widget');

