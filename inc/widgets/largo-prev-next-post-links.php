<?php
/*
 * List all of the terms in a custom taxonomy
 */
class largo_prev_next_post_links_widget extends WP_Widget {

	function largo_prev_next_post_links_widget() {
		$widget_ops = array(
			'classname' 	=> 'largo-prev-next-post-links',
			'description' 	=> __('Prev/next post navigation, typically at the bottom of single posts; formerly a theme option.', 'largo')
		);
		$this->WP_Widget( 'largo-prev-next-post-links-widget', __('Largo Prev/Next Links', 'largo'), $widget_ops);
	}

	function widget( $args, $instance ) {
		global $post;
		extract( $args );

		// only useful on post pages
		if ( !is_single() ) return;

		echo $before_widget;

		largo_content_nav( 'single-post-nav-below', $instance['in_same_cat'] );

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['in_same_cat'] = !empty($new_instance['in_same_cat']) ? 1 : 0;
		return $new_instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance );
		$in_same_cat = isset( $instance['in_same_cat'] ) ? (bool) $instance['in_same_cat'] : false;
		?>

		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('in_same_cat'); ?>" name="<?php echo $this->get_field_name('in_same_cat'); ?>"<?php checked( $in_same_cat ); ?> />
		<label for="<?php echo $this->get_field_id('in_same_cat'); ?>"><?php _e( 'Limit to same category?', 'largo' ); ?></label></p>
	<?php
	}

}
