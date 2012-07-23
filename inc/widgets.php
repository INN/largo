<?php

function largo_load_widgets() {
    register_widget( 'Argo_follow_Widget' );
    register_widget( 'Argo_more_featured_Widget' );
    register_widget( 'Argo_about_Widget' );
    register_widget( 'Argo_hosts_Widget' );
}
add_action( 'widgets_init', 'largo_load_widgets' );

/*
 * Argo Follow Widget
 */
class Argo_follow_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Argo_follow_Widget() {
		/* Widget settings. */
		$widget_ops = array(
			'classname' => 'argo-follow',
			'description' => 'Display links to social media sites set in Argo theme options',
		);

		/* Create the widget. */
		$this->WP_Widget( 'argo-follow-widget', 'Argo Follow', $widget_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );

		/* Before widget*/
		echo $before_widget;

		/* Display the widget title if one was input */
		if ( $title )
			echo $before_title . $title . $after_title; ?>

			<ul>
				<li class="subscribe"><?php echo the_feed_link( 'RSS' ); ?></li>

			<?php if ( get_option( 'facebook_link' ) ) : ?>
				<li>
					<img src="<?php bloginfo('template_directory'); ?>/img/fb16.png" alt="facebook-fav" width="16" height="16" />
					<a href="<?php echo esc_url( get_option( 'facebook_link' ) ); ?>" title="Facebook">Facebook</a>
				</li>
			<?php endif; ?>

			<?php if ( get_option( 'twitter_link' ) ) : ?>
				<li>
					<img src="<?php bloginfo('template_directory'); ?>/img/twitter16.png" alt="twitter-fav" width="16" height="16" />
					<a href="<?php echo esc_url( get_option( 'twitter_link' ) ); ?>" title="twitter">Twitter</a>
				</li>
			<?php endif; ?>

			<?php if ( get_option( 'youtube_link' ) ) : ?>
				<li>
					<img src="<?php bloginfo('template_directory'); ?>/img/youtube16.png" alt="youtube-fav" width="16" height="16" />
					<a href="<?php echo esc_url( get_option( 'youtube_link' ) ); ?>" title="youtube">YouTube</a>
				</li>
			<?php endif; ?>

			<?php if ( get_option( 'flickr_link' ) ) : ?>
				<li>
					<img src="<?php bloginfo('template_directory'); ?>/img/flickr16.png" alt="flickr-fav" width="16" height="16" />
					<a href="<?php echo esc_url( get_option( 'flickr_link' ) ); ?>" title="flickr">Flickr</a>
				</li>
			<?php endif; ?>

			<?php if ( get_option( 'gplus_link' ) ) : ?>
				<li>
					<img src="<?php bloginfo('template_directory'); ?>/img/gplus16.png" alt="gplus-fav" width="16" height="16" />
					<a href="<?php echo esc_url( get_option( 'gplus_link' ) ); ?>?rel=author" title="Google+">Google+</a>
				</li>
			<?php endif; ?>

			<?php if ( get_option( 'podcast_link' ) ) :?>
				<li class="podcast">
				<a href="<?php echo esc_url( get_option( 'podcast_link' ) ); ?>">Podcast</a>
				</li>
			<?php endif; ?>

		</ul>
		<?php
		/* After widget */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title */
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => 'Follow Us' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:90%;" />
		</p>
	<?php
	}
}

/*
 * Argo More Featured Posts
 */
class Argo_more_featured_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Argo_more_featured_Widget() {
		/* Widget settings. */
		$widget_ops = array(
		'classname' => 'argo-more-featured',
		'description' => __('Show the fourth and fifth most recently featured posts with thumbnails and excerpts', 'argo-more-featured') );

		/* Create the widget. */
		$this->WP_Widget( 'argo-more-featured-widget', __('Argo More Featured Posts', 'argo-more-featured'), $widget_ops);
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;

		/* Display the widget title if one was input */
		if ( $title )
			echo $before_title . $title . $after_title;?>

			<?php $missedit = argo_get_featured_posts( array( 'offset' => 0, 'showposts' => 2 ) );
          	if ( $missedit->have_posts() ) : ?>
             	 <?php while ( $missedit->have_posts() ) : $missedit->the_post(); ?>
                  	<div class="post-lead">
                      	<?php the_post_thumbnail( '60x60' ); ?>
                      	<h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                     	<?php the_excerpt(); ?>
                  	</div> <!-- /.post-lead -->
            <?php endwhile; ?>
            <?php else: ?>
    		<p class="error">You're currently featuring 3 or fewer posts. Mark more posts as featured on the add/edit post screen to populate this region.</p>

         <?php endif; // end more featured posts ?>

		<?php
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title */
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('In case you missed it', 'argo-more-featured'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'argo-more-featured'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
		</p>


	<?php
	}
}

/*
 * About this site
 */
class Argo_about_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Argo_about_Widget() {
		/* Widget settings. */
		$widget_ops = array(
		'classname' => 'argo-about',
		'description' => __('Show the site description from your theme options page') );

		/* Create the widget. */
		$this->WP_Widget( 'argo-about-widget', __('Argo About Site', 'argo-about'), $widget_ops);
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;?>

			<?php if ( get_option( 'site_blurb' ) ) : ?>
                <p><?php echo get_option( 'site_blurb' ); ?></p>
			<?php else: ?>
    			<p class="error"><strong>You have not set a description for your site.</strong> Add a site description by visiting the Argo Theme Options page, or add a different widget to this region.</p>
        	<?php endif; // end about site ?>

		<?php
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('About this site', 'argo-about'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'argo-about'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
		</p>


	<?php
	}
}

/*
 * Blog Hosts Widget.
 */
class Argo_hosts_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Argo_hosts_Widget() {
		/* Widget settings. */
		$widget_ops = array(
		'classname' => 'hosts',
		'description' => __('Show an gravatar, Twitter ID and link to the blog host author page') );

		/* Create the widget. */
		$this->WP_Widget( 'hosts', __('Argo Blog Hosts', 'hosts'), $widget_ops);
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );

		/* Before widget */
		echo $before_widget;

		/* Display the widget title */
		if ( $title )
			echo $before_title . $title . $after_title;?>

			<?php if ( argo_get_staff() ): ?>
    		<?php $users = argo_get_staff(); ?>
    		<?php foreach ( $users as $user ): ?>

        		<div class="clearfix">
            		<a href="<?php echo get_author_posts_url( $user->ID ); ?>"><?php echo get_avatar( $user->ID, 60 ); ?></a>
            		<h4><a href="<?php echo get_author_posts_url( $user->ID ); ?>"><?php the_author_meta( 'display_name', $user->ID ); ?></a></h4>
            		<?php if ( get_the_author_meta( 'argo_twitter', $user->ID ) ): ?>
						<p><strong>Twitter:</strong>
    					<a href="http://twitter.com/<?php the_author_meta( 'argo_twitter', $user->ID ); ?>">@<?php the_author_meta( 'argo_twitter', $user->ID ); ?></a></p>
					<?php endif; ?>
        		</div><!-- /.ft-reporter -->
    	<?php endforeach; ?>

    	<?php else: ?>
    		<p><strong>Your blog has no hosts.</strong> Add a host by selecting the blog host checkbox on any user profile screen.</p>

	<?php endif; // end argo get staff ?>

		<?php
		/* After widget */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Blog Hosts', 'hosts'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hosts'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
		</p>


	<?php
	}
}