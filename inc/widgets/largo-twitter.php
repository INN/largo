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
		/* Add the widget class to $before widget, used as a style hook */
		if( strpos($before_widget, 'class') === false ) {
			$before_widget = str_replace('>', 'class="'. $widget_class . '"', $before_widget);
		}
		else {
			$before_widget = str_replace('class="', 'class="'. $widget_class . ' ', $before_widget);
		}

		echo $before_widget; ?>

			<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
			<script>
			new TWTR.Widget({
			  version: 2,
			  type: '<?php echo $instance['widget_type']; ?>',
			  rpp: 30,
			  interval: 30000,
			  <?php if ($instance['widget_type'] == 'list' || $instance['widget_type'] == 'search' )  { ?>
			  title: '<?php echo $instance['title']; ?>',
			  subject: '<?php echo $instance['subtitle']; ?>',
			  <?php } ?>
			  width: '100%',
			  height: 300,
			  theme: {
			    shell: {
			      background: '#<?php echo $instance['bg_color']; ?>',
			      color: '#ffffff'
			    },
			    tweets: {
			      background: '#ffffff',
			      color: '#444444',
			      links: '#2275bb'
			    }
			  },
			  features: {
			    scrollbar: true,
			    loop: false,
			    live: true,
			    behavior: 'all'

			<?php if ($instance['widget_type'] == 'list') { ?>
			  }
			}).render().setList('<?php echo $instance['twitter_username']; ?>', '<?php echo $instance['twitter_list_slug']; ?>').start();
			<?php } else if ($instance['widget_type'] == 'search') { ?>
			  },
			  search: '<?php echo $instance['twitter_search']; ?>',
			}).render().start();
			<?php } else if ($instance['widget_type'] == 'profile') { ?>
			  }
			}).render().setUser('<?php echo $instance['twitter_username']; ?>').start();
			<?php } ?>
			</script>

		<?php echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['subtitle'] = strip_tags( $new_instance['subtitle'] );
		$instance['twitter_username'] = strip_tags( $new_instance['twitter_username'] );
		$instance['twitter_list_slug'] = strip_tags( $new_instance['twitter_list_slug'] );
		$instance['twitter_search'] = strip_tags( $new_instance['twitter_search'] );
		$instance['widget_type'] = $new_instance['widget_type'];
		$instance['bg_color'] = $new_instance['bg_color'];
		$instance['widget_class'] = $new_instance['widget_class'];
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title' => 'Follow ' . get_bloginfo('name'),
			'subtitle' => 'Follow us on Twitter',
			'twitter_username' => twitter_url_to_username( of_get_option( 'twitter_link' ) ),
			'twitter_list_slug' => 'inn-staff-and-associates',
			'twitter_search' => 'your search',
			'widget_type' => 'profile',
			'bg_color' => '333333',
			'widget_class' => 'default'
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<label for="<?php echo $this->get_field_id( 'widget_type' ); ?>"><?php _e('Widget Type', 'largo-twitter'); ?></label>
		<select id="<?php echo $this->get_field_id('widget_type'); ?>" name="<?php echo $this->get_field_name('widget_type'); ?>" class="widefat" style="width:90%;">
		    <option <?php selected( $instance['widget_type'], 'profile'); ?> value="profile">Profile</option>
		    <option <?php selected( $instance['widget_type'], 'list'); ?> value="list">List</option>
		    <option <?php selected( $instance['widget_type'], 'search'); ?> value="search">Search</option>
		</select>

		<p>
			<label for="<?php echo $this->get_field_id( 'bg_color' ); ?>"><?php _e('Background Color:', 'largo-twitter'); ?></label>
			<input id="<?php echo $this->get_field_id( 'bg_color' ); ?>" name="<?php echo $this->get_field_name( 'bg_color' ); ?>" value="<?php echo $instance['bg_color']; ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'largo-twitter'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'subtitle' ); ?>"><?php _e('Subtitle (for list and search widget):', 'largo-twitter'); ?></label>
			<input id="<?php echo $this->get_field_id( 'subtitle' ); ?>" name="<?php echo $this->get_field_name( 'subtitle' ); ?>" value="<?php echo $instance['subtitle']; ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_username' ); ?>"><?php _e('Twitter Username (for profile and list widget):', 'largo-twitter'); ?></label>
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

	<?php
	}
}