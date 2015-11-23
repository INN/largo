<?php

/**
 * List all of the terms in a custom taxonomy
 */
class largo_post_series_links_widget extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'largo-post-series-links',
			'description' 	=> __('Shows the titles/descriptions of the series the post belongs to.', 'largo')
		);
		parent::__construct( 'largo-post-series-links-widget', __('Largo Post Series Links', 'largo'), $widget_ops);
	}

	function widget( $args, $instance ) {
		global $post;
		extract( $args );

		// only useful on post pages
		if ( !is_single() || !largo_post_in_series() ) return;

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __('Related Series', 'largo') : $instance['title'], $instance, $this->id_base);

		echo $before_widget;

		/* Display the widget title if one was input */
		if ( $title ) echo $before_title . $title . $after_title;

		global $post;
		$post_terms = largo_custom_taxonomy_terms( $post->ID );		//this is the only invocation of this function anywhere in Largo
		foreach ( $post_terms as $term ) {
			if ( strtolower( $term->name ) == 'series' )
				continue;
			echo largo_term_to_label( $term ); //this is the only invocation of this function anywhere in Largo
		}

		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Related Series', 'largo') );
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'largo'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:90%;" />
		</p>

	<?php
	}
}
