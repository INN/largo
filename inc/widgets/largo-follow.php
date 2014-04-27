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
			'classname' 	=> 'largo-follow',
			'description' 	=> __('Display links to social media sites set in Largo theme options', 'largo'),
		);

		/* Create the widget. */
		$this->WP_Widget( 'largo-follow-widget', __('Largo Follow', 'largo'), $widget_ops );
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

			printf(__('<a class="subscribe" href="%1$s"><i class="icon-rss"></i>Subscribe via RSS</a>', 'largo'), $feed );

			if ( of_get_option( 'twitter_link' ) ) : ?>
				<a href="<?php echo esc_url( of_get_option( 'twitter_link' ) ); ?>" class="twitter-follow-button" data-width="100%" data-align="left" data-size="large"><?php printf( __('Follow @%1$s', 'largo'), largo_twitter_url_to_username ( of_get_option( 'twitter_link' ) ) ); ?></a>
			<?php endif;

			if ( of_get_option( 'facebook_link' ) ) : ?>
				<div class="fb-like" data-href="<?php echo esc_url( of_get_option( 'facebook_link' ) ); ?>" data-send="false" data-show-faces="false"></div>
			<?php endif;

			if ( of_get_option( 'linkedin_link' ) ) : ?>
				<a class="subscribe" href="<?php echo esc_url( of_get_option( 'linkedin_link' ) ); ?>"><i class="icon-linkedin"></i>Find Us on LinkedIn</a>
			<?php endif;

			if ( of_get_option( 'gplus_link' ) ) : ?>
				<div class="g-follow" data-annotation="bubble" data-height="24" data-href="<?php echo esc_url( of_get_option( 'gplus_link' ) ); ?>" data-rel="publisher"></div>

			<?php endif;

			if ( of_get_option( 'flickr_link' ) ) : ?>
				<div class="flickr-follow"><a href="<?php echo esc_url( of_get_option( 'flickr_link' ) ); ?>" title="See our photos on Flickr!"><img src="https://s.yimg.com/pw/images/goodies/white-flickr.png" width="56" height="26" alt=""></a></div>
			<?php endif;

			if ( of_get_option( 'youtube_link' ) ) :
				$path = parse_url( of_get_option( 'youtube_link' ), PHP_URL_PATH);
				$pathFragments = explode('/', $path);
				$yt_user = end($pathFragments);
				?>
				<div class="g-ytsubscribe" data-channel="<?php echo esc_attr( $yt_user ); ?>" data-layout="default" data-count="default"></div>
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
}