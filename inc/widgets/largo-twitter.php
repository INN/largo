<?php
/*
 * A simple Twitter widget
 */
class largo_twitter_widget extends WP_Widget {

	function largo_twitter_widget() {
		$widget_ops = array(
			'classname' 	=> 'largo-twitter',
			'description' 	=> __('Show a Twitter profile, list or search widget', 'largo')
		);
		$this->WP_Widget( 'largo-twitter-widget', __('Largo Twitter Widget', 'largo'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );

		echo $before_widget;

			 $widget_embed = '<a class="twitter-timeline" height="500" href="https://twitter.com/';

			 if ($instance['widget_type'] == 'search') {
			 	$widget_embed .= 'search?q=' . $instance['twitter_search'] . '" data-widget-id="' . $instance['widget_ID'] . '"data-theme="' . $instance['widget_theme'] . '">Tweets about "' . $instance['twitter_search'] . '"</a>';
			 } else {

				$widget_embed .= $instance['twitter_username'];

				if ($instance['widget_type'] == 'timeline') {

					$widget_embed .= '" data-widget-id="' . $instance['widget_ID'] . '"data-theme="' . $instance['widget_theme'] . '">Tweets by @' . $instance['twitter_username'] . '</a>';

				} elseif ($instance['widget_type'] == 'favorites') {

					$widget_embed .= '/favorites" data-widget-id="' . $instance['widget_ID'] . '"data-theme="' . $instance['widget_theme'] . '">Favorite Tweets by ' . $instance['twitter_username'] . '</a>';

				} elseif ($instance['widget_type'] == 'list') {

					$widget_embed .= '/' . $instance['twitter_list_slug'] . '" data-widget-id="' . $instance['widget_ID'] . '"data-theme="' . $instance['widget_theme'] . '">Tweets from ' . $instance['twitter_username'] . '/' . $instance['twitter_list_slug'] .'</a>';

				}
			 };

		echo $widget_embed;

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['twitter_username'] = sanitize_text_field( $new_instance['twitter_username'] );
		$instance['twitter_list_slug'] = sanitize_text_field( $new_instance['twitter_list_slug'] );
		$instance['twitter_search'] = sanitize_text_field( $new_instance['twitter_search'] );
		$instance['widget_ID'] = sanitize_text_field( $new_instance['widget_ID'] );
		$instance['widget_type'] = sanitize_text_field( $new_instance['widget_type'] );
		$instance['widget_theme'] = sanitize_text_field( $new_instance['widget_theme'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'widget_ID' 		=> '',
			'twitter_username' 	=> largo_twitter_url_to_username( of_get_option( 'twitter_link' ) ),
			'twitter_list_slug' => 'inn-staff-and-associates',
			'twitter_search' 	=> __('your search', 'largo'),
			'widget_type' 		=> 'timeline',
			'widget_theme' 		=> 'light'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'widget_type' ); ?>"><?php _e('Widget Type', 'largo'); ?></label>
			<select id="<?php echo $this->get_field_id('widget_type'); ?>" name="<?php echo $this->get_field_name('widget_type'); ?>" class="widefat" style="width:90%;">
			    <option <?php selected( $instance['widget_type'], 'timeline'); ?> value="timeline"><?php _e('Timeline', 'largo'); ?></option>
			    <option <?php selected( $instance['widget_type'], 'favorites'); ?> value="favorites"><?php _e('Favorites', 'largo'); ?></option>
			    <option <?php selected( $instance['widget_type'], 'list'); ?> value="list"><?php _e('List', 'largo'); ?></option>
			    <option <?php selected( $instance['widget_type'], 'search'); ?> value="search"><?php _e('Search', 'largo'); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'widget_theme' ); ?>"><?php _e('Widget Theme', 'largo'); ?></label>
			<select id="<?php echo $this->get_field_id('widget_theme'); ?>" name="<?php echo $this->get_field_name('widget_theme'); ?>" class="widefat" style="width:90%;">
			    <option <?php selected( $instance['widget_theme'], 'light'); ?> value="light"><?php _e('Light', 'largo'); ?></option>
			    <option <?php selected( $instance['widget_theme'], 'dark'); ?> value="dark"><?php _e('Dark', 'largo'); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'widget_ID' ); ?>"><?php _e('Twitter Widget ID (from https://twitter.com/settings/widgets):', 'largo'); ?></label>
			<input id="<?php echo $this->get_field_id( 'widget_ID' ); ?>" name="<?php echo $this->get_field_name( 'widget_ID' ); ?>" value="<?php echo esc_attr( $instance['widget_ID'] ); ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_username' ); ?>"><?php _e('Twitter Username (for timeline, favorites and list widgets):', 'largo'); ?></label>
			<input id="<?php echo $this->get_field_id( 'twitter_username' ); ?>" name="<?php echo $this->get_field_name( 'twitter_username' ); ?>" value="<?php echo esc_attr( $instance['twitter_username'] ); ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_list_slug' ); ?>"><?php _e('Twitter List Slug (for list widget):', 'largo'); ?></label>
			<input id="<?php echo $this->get_field_id( 'twitter_list_slug' ); ?>" name="<?php echo $this->get_field_name( 'twitter_list_slug' ); ?>" value="<?php echo esc_attr( $instance['twitter_list_slug'] ); ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_search' ); ?>"><?php _e('Twitter Search Query (for search widget):', 'largo'); ?></label>
			<input id="<?php echo $this->get_field_id( 'twitter_search' ); ?>" name="<?php echo $this->get_field_name( 'twitter_search' ); ?>" value="<?php echo esc_attr( $instance['twitter_search'] ); ?>" style="width:90%;" />
		</p>

	<?php
	}
}