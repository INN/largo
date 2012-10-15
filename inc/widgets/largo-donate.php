<?php
/*
 * Largo donate widget
 */
class largo_donate_widget extends WP_Widget {

	function largo_donate_widget() {
		$widget_opts = array(
			'classname' => 'largo-donate',
			'description'=> 'Call-to-action for donations'
		);
		$this->WP_Widget('largo-donate-widget', __('Largo Donate Widget', 'largo-donate'),$widget_opts);
	}
	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', $instance['title'] );

		$widget_class = !empty($instance['widget_class']) ? $instance['widget_class'] : '';
		if ($instance['hidden_tablet'] === 1)
			$widget_class .= ' hidden-tablet';
		if ($instance['hidden_phone'] === 1)
			$widget_class .= ' hidden-phone';
		/* Add the widget class to $before widget, used as a style hook */
		if( strpos($before_widget, 'class') === false ) {
			$before_widget = str_replace('>', 'class="'. $widget_class . '"', $before_widget);
		}
		else {
			$before_widget = str_replace('class="', 'class="'. $widget_class . ' ', $before_widget);
		}

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title; ?>

            <p><?php echo $instance['cta_text']; ?></p>
            <a class="btn btn-primary" href="<?php echo $instance['button_url']; ?>"><?php echo $instance['button_text']; ?></a>

		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['cta_text'] = strip_tags( $new_instance['cta_text'] );
		$instance['button_text'] = strip_tags( $new_instance['button_text'] );
		$instance['button_url'] = strip_tags( $new_instance['button_url'] );
		$instance['widget_class'] = $new_instance['widget_class'];
		$instance['hidden_tablet'] = $new_instance['hidden_tablet'] ? 1 : 0;
		$instance['hidden_phone'] = $new_instance['hidden_phone'] ? 1 : 0;
		return $instance;
	}
	function form( $instance ) {
		$donate_link = '';
		if ( of_get_option( 'donate_link' ) )
			$donate_link = esc_url( of_get_option( 'donate_link' ) );
		$donate_btn_text = 'Donate Now';
		if ( of_get_option( 'donate_button_text' ) )
			$donate_btn_text = esc_attr( of_get_option( 'donate_button_text' ) );
		$defaults = array(
			'title' => 'Support ' . get_bloginfo('name'),
			'cta_text' => 'We depend on your support. A generous gift in any amount helps us continue to bring you this service.',
			'button_text' => $donate_btn_text,
			'button_url' => $donate_link,
			'widget_class' => 'default',
			'hidden_tablet' => '',
			'hidden_phone'	=> ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$tablet = $instance['hidden_tablet'] ? 'checked="checked"' : '';
		$phone = $instance['hidden_phone'] ? 'checked="checked"' : '';
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'largo-donate'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cta_text' ); ?>">Call-to-Action Text:</label>
			<input id="<?php echo $this->get_field_id( 'cta_text' ); ?>" name="<?php echo $this->get_field_name( 'cta_text' ); ?>" value="<?php echo $instance['cta_text']; ?>" style="width:90%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'button_text' ); ?>">Button Text:</label>
			<input id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" value="<?php echo $instance['button_text']; ?>" style="width:90%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'button_url' ); ?>">Button URL (for custom campaigns):</label>
			<input id="<?php echo $this->get_field_id( 'button_url' ); ?>" name="<?php echo $this->get_field_name( 'button_url' ); ?>" value="<?php echo $instance['button_url']; ?>" style="width:90%;" />
		</p>

		<label for="<?php echo $this->get_field_id( 'widget_class' ); ?>"><?php _e('Widget Background', 'largo-about'); ?></label>
		<select id="<?php echo $this->get_field_id('widget_class'); ?>" name="<?php echo $this->get_field_name('widget_class'); ?>" class="widefat" style="width:90%;">
		    <option <?php selected( $instance['widget_class'], 'default'); ?> value="default">Default</option>
		    <option <?php selected( $instance['widget_class'], 'rev'); ?> value="rev">Reverse</option>
		    <option <?php selected( $instance['widget_class'], 'no-bg'); ?> value="no-bg">No Background</option>
		</select>

		<p style="margin:15px 0 10px 5px">
			<input class="checkbox" type="checkbox" <?php echo $tablet; ?> id="<?php echo $this->get_field_id('hidden_tablet'); ?>" name="<?php echo $this->get_field_name('hidden_tablet'); ?>" /> <label for="<?php echo $this->get_field_id('hidden_tablet'); ?>"><?php _e('Hide on Tablets?'); ?></label>
			<br />
			<input class="checkbox" type="checkbox" <?php echo $phone; ?> id="<?php echo $this->get_field_id('hidden_phone'); ?>" name="<?php echo $this->get_field_name('hidden_phone'); ?>" /> <label for="<?php echo $this->get_field_id('hidden_phone'); ?>"><?php _e('Hide on Phones?'); ?></label>
		</p>

		<?php
	}
}