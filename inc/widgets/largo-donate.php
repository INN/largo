<?php
/*
 * Largo donate widget
 */
class largo_donate_widget extends WP_Widget {

	function largo_donate_widget() {
		$widget_opts = array(
			'classname' => 'largo-donate',
			'description'=> __('Call-to-action for donations', 'largo')
		);
		$this->WP_Widget('largo-donate-widget', __('Largo Donate Widget', 'largo'),$widget_opts);
	}
	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __('Support ' . get_bloginfo('name'), 'largo') : $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title; ?>

            <p><?php echo esc_html( $instance['cta_text'] ); ?></p>
            <a class="btn btn-primary" href="<?php echo esc_url( $instance['button_url'] ); ?>"><?php echo esc_html( $instance['button_text'] ); ?></a>

		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['cta_text'] = sanitize_text_field( $new_instance['cta_text'] );
		$instance['button_text'] = sanitize_text_field( $new_instance['button_text'] );
		$instance['button_url'] = esc_url_raw( $new_instance['button_url'] );
		return $instance;
	}
	function form( $instance ) {
		$donate_link = '';
		if ( of_get_option( 'donate_link' ) )
			$donate_link = esc_url( of_get_option( 'donate_link' ) );
		$donate_btn_text = __('Donate Now', 'largo');
		if ( of_get_option( 'donate_button_text' ) )
			$donate_btn_text = esc_attr( of_get_option( 'donate_button_text' ) );
		$defaults = array(
			'title' 			=> __('Support ' . get_bloginfo('name'), 'largo'),
			'cta_text' 			=> __('We depend on your support. A generous gift in any amount helps us continue to bring you this service.', 'largo'),
			'button_text' 		=> $donate_btn_text,
			'button_url' 		=> $donate_link
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'largo'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:90%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cta_text' ); ?>"><?php _e('Call-to-Action Text:', 'largo'); ?></label>
			<input id="<?php echo $this->get_field_id( 'cta_text' ); ?>" name="<?php echo $this->get_field_name( 'cta_text' ); ?>" value="<?php echo esc_attr( $instance['cta_text'] ); ?>" style="width:90%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'button_text' ); ?>"><?php _e('Button Text:', 'largo'); ?></label>
			<input id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" value="<?php echo esc_attr( $instance['button_text'] ); ?>" style="width:90%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'button_url' ); ?>"><?php _e('Button URL (for custom campaigns):', 'largo'); ?></label>
			<input id="<?php echo $this->get_field_id( 'button_url' ); ?>" name="<?php echo $this->get_field_name( 'button_url' ); ?>" value="<?php echo esc_attr( $instance['button_url'] ); ?>" style="width:90%;" />
		</p>

		<?php
	}
}