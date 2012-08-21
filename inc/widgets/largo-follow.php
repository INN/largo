<?php
/*
 * Largo Follow Widget
 */

class largo_follow_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function largo_follow_widget() {
		/* Widget settings. */
		$widget_ops = array(
			'classname' => 'largo-follow',
			'description' => 'Display links to social media sites set in Largo theme options',
		);

		/* Create the widget. */
		$this->WP_Widget( 'largo-follow-widget', 'Largo Follow', $widget_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
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

		/* Before widget*/
		echo $before_widget;

		/* Display the widget title if one was input */
		if ( $title )
			echo $before_title . $title . $after_title;

			$feed = get_feed_link();
			if ( of_get_option( 'rss_link' ) )
				$feed = esc_url (of_get_option( 'rss_link' ) );
			printf('
				<div class="subscribe">
					<a href="%1$s"><i class="social-icons small rss24"></i>Subscribe via RSS</a>
				</div>',
				$feed
			);

			if ( of_get_option( 'twitter_link' ) ) : ?>
				<a href="<?php echo esc_url( of_get_option( 'twitter_link' ) ); ?>" class="twitter-follow-button" data-width="100%" data-align="left" data-size="large">Follow @INN</a>
			<?php endif;

			if ( of_get_option( 'facebook_link' ) ) : ?>
				<div class="fb-like" data-href="<?php echo esc_url( of_get_option( 'facebook_link' ) ); ?>" data-send="false" data-show-faces="false"></div>
			<?php endif; ?>

		<?php

		/* After widget */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['widget_class'] = $new_instance['widget_class'];

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array(
			'title' => 'Follow ' . get_bloginfo('name'),
			'widget_class' => 'default'
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:90%;" />
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