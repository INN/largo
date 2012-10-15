<?php
/*
 * A simple Twitter widget
 */
class largo_twitter_widget extends WP_Widget {

	function largo_twitter_widget() {
		$widget_ops = array(
			'classname' => 'largo-twitter',
			'description' => 'Show a Twitter profile, list or search widget'
		);
		$this->WP_Widget( 'largo-twitter-widget', __('Largo Twitter Widget', 'largo-twitter'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );

		$widget_class = !empty($instance['widget_class']) ? $instance['widget_class'] : '';
		if ($instance['hidden_tablet'] === 1)
			$widget_class .= ' hidden-tablet';
		if ($instance['hidden_phone'] === 1)
			$widget_class .= ' hidden-phone';
		/* Add the widget class to $before_widget, used as a style hook */
		if( strpos($before_widget, 'class') === false ) {
			$before_widget = str_replace('>', 'class="'. $widget_class . '"', $before_widget);
		}
		else {
			$before_widget = str_replace('class="', 'class="'. $widget_class . ' ', $before_widget);
		}

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
		$instance['twitter_username'] = strip_tags( $new_instance['twitter_username'] );
		$instance['twitter_list_slug'] = strip_tags( $new_instance['twitter_list_slug'] );
		$instance['twitter_search'] = strip_tags( $new_instance['twitter_search'] );
		$instance['widget_ID'] = strip_tags( $new_instance['widget_ID'] );
		$instance['widget_type'] = $new_instance['widget_type'];
		$instance['widget_theme'] = $new_instance['widget_theme'];
		$instance['widget_class'] = $new_instance['widget_class'];
		$instance['hidden_tablet'] = $new_instance['hidden_tablet'] ? 1 : 0;
		$instance['hidden_phone'] = $new_instance['hidden_phone'] ? 1 : 0;
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'widget_ID' => '',
			'twitter_username' => twitter_url_to_username( of_get_option( 'twitter_link' ) ),
			'twitter_list_slug' => 'inn-staff-and-associates',
			'twitter_search' => 'your search',
			'widget_type' => 'timeline',
			'widget_theme' => 'light',
			'widget_class' => 'default',
			'hidden_tablet' => '',
			'hidden_phone'	=> ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$tablet = $instance['hidden_tablet'] ? 'checked="checked"' : '';
		$phone = $instance['hidden_phone'] ? 'checked="checked"' : '';
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'widget_type' ); ?>"><?php _e('Widget Type', 'largo-twitter'); ?></label>
			<select id="<?php echo $this->get_field_id('widget_type'); ?>" name="<?php echo $this->get_field_name('widget_type'); ?>" class="widefat" style="width:90%;">
			    <option <?php selected( $instance['widget_type'], 'timeline'); ?> value="timeline">Timeline</option>
			    <option <?php selected( $instance['widget_type'], 'favorites'); ?> value="favorites">Favorites</option>
			    <option <?php selected( $instance['widget_type'], 'list'); ?> value="list">List</option>
			    <option <?php selected( $instance['widget_type'], 'search'); ?> value="search">Search</option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'widget_theme' ); ?>"><?php _e('Widget Theme', 'largo-twitter'); ?></label>
			<select id="<?php echo $this->get_field_id('widget_theme'); ?>" name="<?php echo $this->get_field_name('widget_theme'); ?>" class="widefat" style="width:90%;">
			    <option <?php selected( $instance['widget_theme'], 'light'); ?> value="light">Light</option>
			    <option <?php selected( $instance['widget_theme'], 'dark'); ?> value="dark">Dark</option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'widget_ID' ); ?>"><?php _e('Twitter Widget ID (from https://twitter.com/settings/widgets):', 'largo-twitter'); ?></label>
			<input id="<?php echo $this->get_field_id( 'widget_ID' ); ?>" name="<?php echo $this->get_field_name( 'widget_ID' ); ?>" value="<?php echo $instance['widget_ID']; ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_username' ); ?>"><?php _e('Twitter Username (for timeline, favorites and list widgets):', 'largo-twitter'); ?></label>
			<input id="<?php echo $this->get_field_id( 'twitter_username' ); ?>" name="<?php echo $this->get_field_name( 'twitter_username' ); ?>" value="<?php echo $instance['twitter_username']; ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_list_slug' ); ?>"><?php _e('Twitter List Slug (for list widget):', 'largo-twitter'); ?></label>
			<input id="<?php echo $this->get_field_id( 'twitter_list_slug' ); ?>" name="<?php echo $this->get_field_name( 'twitter_list_slug' ); ?>" value="<?php echo $instance['twitter_list_slug']; ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_search' ); ?>"><?php _e('Twitter Search Query (for search widget):', 'largo-twitter'); ?></label>
			<input id="<?php echo $this->get_field_id( 'twitter_search' ); ?>" name="<?php echo $this->get_field_name( 'twitter_search' ); ?>" value="<?php echo $instance['twitter_search']; ?>" style="width:90%;" />
		</p>

		<label for="<?php echo $this->get_field_id( 'widget_class' ); ?>"><?php _e('Widget Background', 'largo-twitter'); ?></label>
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