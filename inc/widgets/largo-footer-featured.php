<?php
/*
 * Largo Footer Featured Posts
 */
class largo_footer_featured_widget extends WP_Widget {

	function largo_footer_featured_widget() {
		$widget_ops = array(
		'classname' => 'largo-footer-featured',
		'description' => __('Show recent featured posts with thumbnails and excerpts', 'largo-footer-featured') );

		$this->WP_Widget( 'largo-footer-featured-widget', __('Largo Footer Featured Posts', 'largo-footer-featured'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;?>

			<?php $missedit = largo_get_featured_posts( array(
				'tax_query' => array(
					array(
						'taxonomy' => 'prominence',
						'field' => 'slug',
						'terms' => 'footer-featured-widget'
					)
				),
				'showposts' => $instance['num_posts']
				)
			);
          	if ( $missedit->have_posts() ) : ?>
             	 <?php while ( $missedit->have_posts() ) : $missedit->the_post(); ?>
                  	<div class="post-lead clearfix">
                      	<?php the_post_thumbnail( '60x60' ); ?>
                      	<h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                     	<?php the_excerpt(); ?>
                  	</div> <!-- /.post-lead -->
	            <?php endwhile; ?>
	            <?php else: ?>
	    		<p class="error"><strong>You don't presently have any posts in the footer featured category.</strong> Mark more posts as featured on the add/edit post screen to populate this region.</p>

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