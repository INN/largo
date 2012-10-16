<?php
/*
 * Largo Sidebar Featured Posts
 */
class largo_sidebar_featured_widget extends WP_Widget {

	function largo_sidebar_featured_widget() {
		$widget_ops = array(
			'classname' => 'largo-sidebar-featured',
			'description' => 'Show recent featured posts with thumbnails and excerpts', 'largo-sidebar-featured'
		);
		$this->WP_Widget( 'largo-sidebar-featured-widget', __('Largo Sidebar Featured Posts', 'largo-sidebar-featured'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );

		$widget_class = !empty($instance['widget_class']) ? $instance['widget_class'] : '';
		if ($instance['hidden_tablet'] === 1)
			$widget_class .= ' hidden-tablet';
		if ($instance['hidden_phone'] === 1)
			$widget_class .= ' hidden-phone';
		/* Add the widget class to $before widget, used as a style hook */
		if( strpos($before_widget, 'class') === false ) {
			$before_widget = str_replace('>', 'class="'. $widget_class . '"', $before_widget);
		}
		else {
			$before_widget = str_replace('class="', 'class="'. $widget_class . ' ', $before_widget);
		}

		/* Before widget*/
		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;?>

			<?php $featured = largo_get_featured_posts( array(
				'tax_query' => array(
					array(
						'taxonomy' => 'prominence',
						'field' => 'slug',
						'terms' => 'sidebar-featured-widget'
					)
				),
				'showposts' => $instance['num_posts']
				)
			);
          	if ( $featured->have_posts() ) : ?>
             	 <?php while ( $featured->have_posts() ) : $featured->the_post(); ?>
                  	<div class="post-lead clearfix">
                      	<h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                      	<?php the_post_thumbnail( '60x60' ); ?>
                     	<?php echo '<p>' . largo_trim_sentences(get_the_content(), $instance['num_sentences']) . '</p>'; ?>
                  	</div> <!-- /.post-lead -->
	            <?php endwhile; ?>
	            <?php else: ?>
	    		<p class="error"><strong>You don't have any posts in the sidebar featured category.</strong> Mark more posts as featured on the add/edit post screen to populate this region.</p>

    		<?php endif; // end more featured posts ?>

		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['num_posts'] = strip_tags( $new_instance['num_posts'] );
		$instance['num_sentences'] = strip_tags( $new_instance['num_sentences'] );
		$instance['widget_class'] = $new_instance['widget_class'];
		$instance['hidden_tablet'] = $new_instance['hidden_tablet'] ? 1 : 0;
		$instance['hidden_phone'] = $new_instance['hidden_phone'] ? 1 : 0;
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title' => 'We Recommend',
			'num_posts' => 5,
			'num_sentences' => 2,
			'widget_class' => 'default',
			'hidden_tablet' => '',
			'hidden_phone'	=> ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$tablet = $instance['hidden_tablet'] ? 'checked="checked"' : '';
		$phone = $instance['hidden_phone'] ? 'checked="checked"' : '';
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'largo-sidebar-featured'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'num_posts' ); ?>"><?php _e('Number of posts to show:', 'largo-sidebar-featured'); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_posts' ); ?>" name="<?php echo $this->get_field_name( 'num_posts' ); ?>" value="<?php echo $instance['num_posts']; ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'num_sentences' ); ?>"><?php _e('Excerpt Length (# of Sentences):', 'largo-footer-featured'); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_sentences' ); ?>" name="<?php echo $this->get_field_name( 'num_sentences' ); ?>" value="<?php echo $instance['num_sentences']; ?>" style="width:90%;" />
		</p>

		<label for="<?php echo $this->get_field_id( 'widget_class' ); ?>"><?php _e('Widget Background', 'largo-sidebar-featured'); ?></label>
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