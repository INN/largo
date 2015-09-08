<?php
/*
 * Largo Follow Widget
 */

class largo_follow_widget extends WP_Widget {

	/**
	 * Used to tell largo_footer_js whether it needs
	 * to load twitter scripts.
	 */
	private static $rendered = false;

	/**
	 * Widget setup.
	 */
	function largo_follow_widget() {
		/* Widget settings. */
		$widget_ops = array(
			'classname' 	=> 'largo-follow',
			'description' 	=> __('Display links to social media sites set in Largo theme options', 'largo'),
		);

		/* Create the widget. */
		$this->WP_Widget( 'largo-follow-widget', __('Largo Follow', 'largo'), $widget_ops );
	
		self::$rendered = true;
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title',  $instance['title'], $instance, $this->id_base);

		echo $before_widget;

		/* Display the widget title if one was input */
		if ( $title )
			echo $before_title . $title . $after_title;

		$feed = get_feed_link();

		if ( is_single() && isset($id) && $id == 'article-bottom' ) :
			// display the post social bar
			largo_post_social_links();
		else :
			// display the usual buttons and whatnot
			if ( of_get_option( 'rss_link' ) )
				$feed = esc_url (of_get_option( 'rss_link' ) );

			printf(__('<a class="rss subscribe btn social-btn" href="%1$s"><i class="icon-rss"></i>Subscribe via RSS</a>', 'largo'), $feed );

			if ( of_get_option( 'twitter_link' ) ) : ?>
				<a href="<?php echo esc_url( of_get_option( 'twitter_link' ) ); ?>" class="twitter subscribe btn social-btn"><i class="icon-twitter"></i><?php printf( __('Follow @%1$s', 'largo'), largo_twitter_url_to_username ( of_get_option( 'twitter_link' ) ) ); ?></a>
			<?php endif;

			if ( of_get_option( 'facebook_link' ) ) : ?>
				<a href="<?php echo esc_url( of_get_option( 'facebook_link' ) ); ?>" class="facebook subscribe btn social-btn"><i class="icon-facebook"></i> <?php _e( 'Like on Facebook', 'largo' ); ?></a>
			<?php endif;

			if ( of_get_option( 'linkedin_link' ) ) : ?>
				<a class="linkedin subscribe btn social-btn" href="<?php echo esc_url( of_get_option( 'linkedin_link' ) ); ?>"><i class="icon-linkedin"></i><?php _e( 'Find on LinkedIn', 'largo' ); ?></a>
			<?php endif;

			if ( of_get_option( 'gplus_link' ) ) : ?>
				<a class="gplus subscribe btn social-btn" href="<?php echo esc_url( of_get_option( 'gplus_link' ) ); ?>"><i class="icon-gplus"></i><?php _e('Follow on G+', 'largo'); ?></a>

			<?php endif;

			if ( of_get_option( 'flickr_link' ) ) : ?>
				<a class="flickr subscribe btn social-btn" href="<?php echo esc_url( of_get_option( 'flickr_link' ) ); ?>"><i class="icon-flickr"></i><?php _e('Follow on Flickr', 'largo'); ?></a>
			<?php endif;

			if ( of_get_option( 'youtube_link' ) ) : ?>
				<a class="youtube subscribe btn social-btn" href="<?php echo esc_url( of_get_option( 'youtube_link' ) ); ?>"><i class="icon-youtube"></i><?php _e('Follow on YouTube', 'largo'); ?></a>
		<?php endif;

			//the below is for G+ and YouTube subscribe buttons
			?>
			<script type="text/javascript">
			  (function() {
			    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			    po.src = 'https://apis.google.com/js/platform.js';
			    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			  })();
			</script>
		<?php
		endif;

		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => sprintf( __('Follow %s', 'largo'), get_bloginfo('name') ) );
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', 'largo'); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:90%;" />
		</p>

	<?php
	}

	/**
	 * Returns true if this widget has been rendered one or more times.
	 * 
	 * @since 0.5
	 */
	function is_rendered() {
		return self::$rendered;
	}
	
}