<?php

function largo_load_widgets() {
    register_widget( 'largo_follow_widget' );
    register_widget( 'largo_footer_featured_widget' );
    register_widget( 'largo_about_widget' );
    register_widget( 'largo_donate_widget' );
}
add_action( 'widgets_init', 'largo_load_widgets' );

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
					<a href="%1$s"><i class="social-icons small rss"></i>Subscribe via RSS</a>
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

/*
 * Largo Footer Featured Posts
 */
class largo_footer_featured_widget extends WP_Widget {

	function largo_footer_featured_widget() {
		$widget_ops = array(
		'classname' => 'largo-footer-featured',
		'description' => __('Show two recent featured posts with thumbnails and excerpts', 'largo-footer-featured') );

		$this->WP_Widget( 'largo-footer-featured-widget', __('Largo Footer Featured Posts', 'largo-footer-featured'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;?>

			<?php $missedit = largo_get_featured_posts( array( 'meta_key' => 'footer_featured_widget', 'showposts' => $instance['num_posts'] ) );
          	if ( $missedit->have_posts() ) : ?>
             	 <?php while ( $missedit->have_posts() ) : $missedit->the_post(); ?>
                  	<div class="post-lead clearfix">
                      	<?php the_post_thumbnail( '60x60' ); ?>
                      	<h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                     	<?php the_excerpt(); ?>
                  	</div> <!-- /.post-lead -->
            <?php endwhile; ?>
            <?php else: ?>
    		<p class="error">You're currently featuring 2 or fewer posts. Mark more posts as featured on the add/edit post screen to populate this region.</p>

         <?php endif; // end more featured posts ?>

		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['num_posts'] = strip_tags( $new_instance['num_posts'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'title' => __('In Case You Missed It', 'largo-footer-featured'));
		$defaults = array(
			'title' => 'In Case You Missed It',
			'num_posts' => 2
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'largo-footer-featured'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'num_posts' ); ?>"><?php _e('Number of posts to show:', 'largo-footer-featured'); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_posts' ); ?>" name="<?php echo $this->get_field_name( 'num_posts' ); ?>" value="<?php echo $instance['num_posts']; ?>" style="width:90%;" />
		</p>

	<?php
	}
}

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
class largo_donate_widget extends WP_Widget {

	function largo_donate_widget() {

		$widget_opts = array(
		'classname' => 'largo-donate',
		'description'=>__("Call-to-action for donations"));
		$this->WP_Widget('largo-donate-widget', __('Largo Donate Widget', 'largo-donate'),$widget_opts);

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
			'widget_class' => 'default'
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

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

		<?php
	}
}