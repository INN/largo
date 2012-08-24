<?php
/*
 * Largo Recent Posts
 */
class largo_recent_posts_widget extends WP_Widget {

	function largo_recent_posts_widget() {
		$widget_ops = array(
			'classname' => 'largo-recent-posts',
			'description' => 'Show your most recent posts with thumbnails and excerpts', 'largo-recent-posts'
		);
		$this->WP_Widget( 'largo-recent-posts-widget', __('Largo Recent Posts', 'largo-recent-posts'), $widget_ops);
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

		/* Before widget*/
		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;?>

			<?php
			$my_query = new WP_Query( array( 'post__not_in' => get_option( 'sticky_posts' ), 'showposts' => 5 ) );
          		if ( $my_query->have_posts() ) :
          			while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
	                  	<div class="post-lead clearfix">
	                      	<h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
	                      	<?php the_post_thumbnail( '60x60' ); ?>
	                     	<?php the_excerpt(); ?>
	                  	</div> <!-- /.post-lead -->
	            <?php endwhile;
	            else: ?>
	    		<p class="error"><strong>You don't have any recent posts.</strong></p>

    		<?php endif; // end more featured posts ?>

		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['num_posts'] = strip_tags( $new_instance['num_posts'] );
		$instance['widget_class'] = $new_instance['widget_class'];
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title' => 'Recent Posts',
			'num_posts' => 5,
			'widget_class' => 'default'
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'largo-recent-posts'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'num_posts' ); ?>"><?php _e('Number of posts to show:', 'largo-recent-posts'); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_posts' ); ?>" name="<?php echo $this->get_field_name( 'num_posts' ); ?>" value="<?php echo $instance['num_posts']; ?>" style="width:90%;" />
		</p>

		<label for="<?php echo $this->get_field_id( 'widget_class' ); ?>"><?php _e('Widget Background', 'largo-recent-posts'); ?></label>
		<select id="<?php echo $this->get_field_id('widget_class'); ?>" name="<?php echo $this->get_field_name('widget_class'); ?>" class="widefat" style="width:90%;">
		    <option <?php selected( $instance['widget_class'], 'default'); ?> value="default">Default</option>
		    <option <?php selected( $instance['widget_class'], 'rev'); ?> value="rev">Reverse</option>
		    <option <?php selected( $instance['widget_class'], 'no-bg'); ?> value="no-bg">No Background</option>
		</select>

	<?php
	}
}