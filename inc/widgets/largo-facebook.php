<?php
/*
 * A simple Twitter widget
 */
class largo_facebook_widget extends WP_Widget {

	function largo_facebook_widget() {
		$widget_ops = array(
			'classname' 	=> 'largo-facebook',
			'description' 	=> __('Show a Facebook Like Box for your page', 'largo')
		);
		$this->WP_Widget( 'largo-facebook-widget', __('Largo Facebook Widget', 'largo'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );

		echo $before_widget;

			$height = isset( $instance['widget-height'] ) ? $instance['widget-height'] : 350;
			$output = '<div class="fb-like-box" data-href="' . $instance['fb_page_url'] . '" data-height="' . $height . '"';
			$output .= $instance['show_faces'] === 1 ? ' data-show-faces="true"' : ' data-show-faces="false"';
			$output .= $instance['show_stream'] === 1 ? ' data-stream="true"' : ' data-stream="false"';
			$output .= $instance['show_header'] === 1 ? ' data-header="true"' : ' data-header="false"';
			$output .= '></div>';

		echo $output;

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['fb_page_url'] = strip_tags( $new_instance['fb_page_url'] );
		$instance['widget_height'] = strip_tags( $new_instance['widget_height'] );
		$instance['show_faces'] = $new_instance['show_faces'] ? 1 : 0;
		$instance['show_stream'] = $new_instance['show_stream'] ? 1 : 0;
		$instance['show_header'] = $new_instance['show_header'] ? 1 : 0;
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'fb_page_url' 		=> of_get_option( 'facebook_link' ),
			'widget_height' 	=> 350,
			'show_faces' 		=> 1,
			'show_stream' 		=> 0,
			'show_header' 		=> 0
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$show_faces = $instance['show_faces'] ? 'checked="checked"' : '';
		$show_stream = $instance['show_stream'] ? 'checked="checked"' : '';
		$show_header = $instance['show_header'] ? 'checked="checked"' : '';
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'fb_page_url' ); ?>"><?php _e('Facebook Page URL:', 'largo'); ?></label>
			<input id="<?php echo $this->get_field_id( 'fb_page_url' ); ?>" name="<?php echo $this->get_field_name( 'fb_page_url' ); ?>" value="<?php echo $instance['fb_page_url']; ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'widget_height' ); ?>"><?php _e('Widget Height:', 'largo'); ?></label>
			<input id="<?php echo $this->get_field_id( 'widget_height' ); ?>" name="<?php echo $this->get_field_name( 'widget_height' ); ?>" value="<?php echo $instance['widget_height']; ?>" style="width:90%;" />
		</p>

		<p style="margin:15px 0 10px 5px">
			<input class="checkbox" type="checkbox" <?php echo $show_faces; ?> id="<?php echo $this->get_field_id('show_faces'); ?>" name="<?php echo $this->get_field_name('show_faces'); ?>" /> <label for="<?php echo $this->get_field_id('show_faces'); ?>"><?php _e('Show Faces?', 'largo'); ?></label>
			<br />
			<input class="checkbox" type="checkbox" <?php echo $show_stream; ?> id="<?php echo $this->get_field_id('show_stream'); ?>" name="<?php echo $this->get_field_name('show_stream'); ?>" /> <label for="<?php echo $this->get_field_id('show_stream'); ?>"><?php _e('Show Stream?', 'largo'); ?></label>
			<br />
			<input class="checkbox" type="checkbox" <?php echo $show_header; ?> id="<?php echo $this->get_field_id('show_header'); ?>" name="<?php echo $this->get_field_name('show_header'); ?>" /> <label for="<?php echo $this->get_field_id('show_header'); ?>"><?php _e('Show Header?', 'largo'); ?></label>
		</p>

	<?php
	}
}