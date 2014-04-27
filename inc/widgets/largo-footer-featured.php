<?php
/*
 * Largo Footer Featured Posts
 */
class largo_footer_featured_widget extends WP_Widget {

	function largo_footer_featured_widget() {
		$widget_ops = array(
			'classname' 	=> 'largo-footer-featured',
			'description' 	=> '*DEPRECATED* Show recent featured posts with thumbnails and excerpts', 'largo-footer-featured'
		);
		$this->WP_Widget( 'largo-footer-featured-widget', __('Largo Footer Featured Posts', 'largo'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __('In Case You Missed It', 'largo') : $instance['title'], $instance, $this->id_base);

		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;?>

			<?php $missedit = largo_get_featured_posts( array(
				'tax_query' => array(
					array(
						'taxonomy' 	=> 'prominence',
						'field' 	=> 'slug',
						'terms' 	=> 'footer-featured'
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
                      	<?php echo '<p>' . largo_trim_sentences( get_the_content(), $instance['num_sentences'] ) . '</p>'; ?>
                  	</div> <!-- /.post-lead -->
	            <?php endwhile; ?>
	            <?php else: ?>
	    		<p class="error"><strong><?php _e('You don\'t presently have any posts in the footer featured category.</strong> Mark more posts as featured on the add/edit post screen to populate this region.', 'largo') ?></p>

    		<?php endif; // end more featured posts ?>

		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['num_posts'] = intval( $new_instance['num_posts'] );
		$instance['num_sentences'] = intval( $new_instance['num_sentences'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title' 		=> __('In Case You Missed It', 'largo'),
			'num_posts' 	=> 2,
			'num_sentences' => 2
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', 'largo'); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'num_posts' ); ?>"><?php _e('Number of posts to show', 'largo'); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'num_posts' ); ?>" name="<?php echo $this->get_field_name( 'num_posts' ); ?>" value="<?php echo (int) $instance['num_posts']; ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'num_sentences' ); ?>"><?php _e('Excerpt Length (# of Sentences)', 'largo'); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'num_sentences' ); ?>" name="<?php echo $this->get_field_name( 'num_sentences' ); ?>" value="<?php echo (int) $instance['num_sentences']; ?>" style="width:90%;" />
		</p>

	<?php
	}
}