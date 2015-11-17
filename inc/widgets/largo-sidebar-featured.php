<?php
/*
 * Largo Sidebar Featured Posts
 */
class largo_sidebar_featured_widget extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'largo-sidebar-featured',
			'description' 	=> __('*DEPRECATED* Show recent featured posts with thumbnails and excerpts', 'largo')
		);
		parent::__construct( 'largo-sidebar-featured-widget', __('Largo Sidebar Featured Posts', 'largo'), $widget_ops);
	}

	function widget( $args, $instance ) {
		global $post;
		// Preserve global $post
		$preserve = $post;

		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'We Recommend', 'largo' ) : $instance['title'], $instance, $this->id_base);

		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;?>

			<?php $featured = largo_get_featured_posts( array(
				'tax_query' => array(
					array(
						'taxonomy' 	=> 'prominence',
						'field' 	=> 'slug',
						'terms' 	=> 'sidebar-featured'
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
                     	<?php echo '<p>' . largo_trim_sentences( get_the_content(), $instance['num_sentences'] ) . '</p>'; ?>
                  	</div> <!-- /.post-lead -->
	            <?php endwhile; ?>
	            <?php else: ?>
	    		<p class="error"><strong>You don't have any posts in the sidebar featured category.</strong> Mark more posts as featured on the add/edit post screen to populate this region.</p>

    		<?php endif; // end more featured posts ?>

		<?php
		echo $after_widget;

		// Restore global $post
		wp_reset_postdata();
		$post = $preserve;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['num_posts'] = strip_tags( $new_instance['num_posts'] );
		$instance['num_sentences'] = strip_tags( $new_instance['num_sentences'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title' 			=> __('We Recommend', 'largo'),
			'num_posts' 		=> 5,
			'num_sentences' 	=> 2
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'largo'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'num_posts' ); ?>"><?php _e('Number of posts to show:', 'largo'); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_posts' ); ?>" name="<?php echo $this->get_field_name( 'num_posts' ); ?>" value="<?php echo $instance['num_posts']; ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'num_sentences' ); ?>"><?php _e('Excerpt Length (# of Sentences):', 'largo'); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_sentences' ); ?>" name="<?php echo $this->get_field_name( 'num_sentences' ); ?>" value="<?php echo $instance['num_sentences']; ?>" style="width:90%;" />
		</p>

	<?php
	}
}
