<?php

/**
 * A simple Twitter widget
 */
class largo_twitter_widget extends WP_Widget {

	/**
	 * Used to tell largo_footer_js whether it needs
	 * to load twitter scripts.
	 */
	private static $rendered = false;

	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'largo-twitter',
			'description' 	=> __( 'Show a Twitter profile, collection, list or likes widget', 'largo')
		);
		parent::__construct( 'largo-twitter-widget', __( 'Largo Twitter Widget', 'largo' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		echo $before_widget;
		
		// Build the placeholder URLs used by various widget types
		// Note that these are not strictly necessary (widget will render as long as the data-widget-id attribute is correct
		// The URL and text are just used as a fallback if the JS doesn't load
		switch($instance['widget_type']) {
			case 'likes':
				$widget_href = 'https://twitter.com/' . $instance['twitter_username'] . '/likes';
				/* translators: @username's Likes on Twitter */
				$widget_text = $instance['twitter_username'] . __( '\'s Likes on Twitter', 'largo' );
				break;
			case 'list':
				$widget_href = 'https://twitter.com/' . $instance['twitter_username'] . '/lists/' . $instance['twitter_list_slug'];
				/* translators: Tweets from [list URL] */
				$widget_text = __( 'A Twitter List by ', 'largo' ) . $instance['twitter_username'];
				break;
			case 'collection':
				$widget_href = 'https://twitter.com/' . $instance['twitter_username'] . '/timelines/' . $instance['twitter_collection_id'];
				$widget_text = $instance['twitter_collection_title'];
				break;
			default: //timeline, probably
				$widget_href = 'https://twitter.com/' . $instance['twitter_username'];
				/* translators: Tweets by @username */
				$widget_text = __( 'Tweets by @', 'largo' ) . $instance['twitter_username'];
		}
			
		$widget_embed = sprintf( '<a class="twitter-timeline" href="%1$s">%2$s</a>',
			esc_url( $widget_href ),
			esc_attr( $widget_text )
		);
		// N.B. - the JS is enqueued in largo_footer_js (inc/enqueue.php)
	
		echo $widget_embed;

		echo $after_widget;
		
		self::$rendered = true;

	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['twitter_username'] = sanitize_text_field( $new_instance['twitter_username'] );
		$instance['twitter_list_slug'] = sanitize_text_field( $new_instance['twitter_list_slug'] );
		$instance['widget_ID'] = sanitize_text_field( $new_instance['widget_ID'] );
		$instance['widget_type'] = sanitize_text_field( $new_instance['widget_type'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'widget_ID' 		=> '',
			'twitter_username' 	=> largo_twitter_url_to_username( of_get_option( 'twitter_link' ) ),
			'widget_type' 		=> 'timeline',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'widget_type' ); ?>"><?php _e('Widget Type', 'largo'); ?></label>
			<select id="<?php echo $this->get_field_id( 'widget_type' ); ?>" name="<?php echo $this->get_field_name( 'widget_type' ); ?>" class="widefat" style="width:90%;">
			    <option <?php selected( $instance['widget_type'], 'timeline'); ?> value="timeline"><?php _e( 'Timeline', 'largo' ); ?></option>
			    <option <?php selected( $instance['widget_type'], 'likes'); ?> value="likes"><?php _e( 'Likes', 'largo' ); ?></option>
			    <option <?php selected( $instance['widget_type'], 'list'); ?> value="list"><?php _e( 'List', 'largo' ); ?></option>
			    <option <?php selected( $instance['widget_type'], 'collection'); ?> value="collection"><?php _e( 'Collection', 'largo' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'widget_ID' ); ?>"><?php _e( 'Twitter Widget ID (from https://twitter.com/settings/widgets):', 'largo' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'widget_ID' ); ?>" name="<?php echo $this->get_field_name( 'widget_ID' ); ?>" value="<?php echo esc_attr( $instance['widget_ID'] ); ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_username' ); ?>"><?php _e( 'Twitter Username (for timeline, likes and list widgets):', 'largo' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'twitter_username' ); ?>" name="<?php echo $this->get_field_name( 'twitter_username' ); ?>" value="<?php echo esc_attr( $instance['twitter_username'] ); ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_list_slug' ); ?>"><?php _e( 'Twitter List Slug (for list widget):', 'largo' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'twitter_list_slug' ); ?>" name="<?php echo $this->get_field_name( 'twitter_list_slug' ); ?>" value="<?php echo esc_attr( $instance['twitter_list_slug'] ); ?>" style="width:90%;" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_collection_id' ); ?>"><?php _e( 'Collection ID (for collection widget):', 'largo' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'twitter_collection_id' ); ?>" name="<?php echo $this->get_field_name( 'twitter_collection_id' ); ?>" value="<?php echo esc_attr( $instance['twitter_collection_id'] ); ?>" style="width:90%;" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_collection_title' ); ?>"><?php _e( 'Collection Title (for collection widget):', 'largo' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'twitter_collection_title' ); ?>" name="<?php echo $this->get_field_name( 'twitter_collection_title' ); ?>" value="<?php echo esc_attr( $instance['twitter_collection_title'] ); ?>" style="width:90%;" />
		</p>

	<?php
	}

	/**
	 * Returns true if this widget has been rendered one or more times.
	 * 
	 * @since 0.5
	 */
	static function is_rendered() {
		return self::$rendered;
	}

}
