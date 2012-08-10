<?php
/*
 * About this site
 */
class largo_about_widget extends WP_Widget {

	function largo_about_widget() {
		$widget_ops = array(
		'classname' => 'largo-about',
		'description' => __('Show the site description from your theme options page') );

		$this->WP_Widget( 'largo-about-widget', __('Largo About Site', 'largo-about'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', $instance['title'] );

		$widget_class = !empty($instance['widget_class']) ? $instance['widget_class'] : '';
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

			<?php if ( of_get_option( 'site_blurb' ) ) : ?>
                <p><?php echo of_get_option( 'site_blurb' ); ?></p>
			<?php else: ?>
    			<p class="error"><strong>You have not set a description for your site.</strong> Add a site description by visiting the Largo Theme Options page.</p>
        	<?php endif; // end about site ?>

		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['widget_class'] = $new_instance['widget_class'];
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title' => 'About ' . get_bloginfo('name'),
			'widget_class' => 'default'
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'largo-about'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
		</p>

		<label for="<?php echo $this->get_field_id( 'widget_class' ); ?>"><?php _e('Widget Background', 'largo-about'); ?></label>
		<select id="<?php echo $this->get_field_id('widget_class'); ?>" name="<?php echo $this->get_field_name('widget_class'); ?>" class="widefat" style="width:90%;">
		    <option <?php selected( $instance['widget_class'], 'default'); ?> value="default">Default</option>
		    <option <?php selected( $instance['widget_class'], 'rev'); ?> value="rev">Reverse</option>
		    <option <?php selected( $instance['widget_class'], 'no-bg'); ?> value="no-bg">No Background</option>
		</select>

	<?php
	}
}