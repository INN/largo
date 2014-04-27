<?php
/*
 * Largo Featured Posts
 */
class largo_featured_widget extends WP_Widget {

	function largo_featured_widget() {
		$widget_ops = array(
			'classname' 	=> 'largo-featured',
			'description' 	=> 'Show recent featured posts with thumbnails and excerpts', 'largo-featured'
		);
		$this->WP_Widget( 'largo-featured-widget', __('Largo Featured Posts', 'largo'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$placeholder_title = ( strpos($id, 'footer') !== false ) ? __('In Case You Missed It', 'largo') : __('We Recommend', 'largo');

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? $placeholder_title : $instance['title'], $instance, $this->id_base);

		echo $before_widget;

		if ( $title ) echo $before_title . $title . $after_title;

		$missedit = largo_get_featured_posts( array(
			'tax_query' => array(
				array(
					'taxonomy' 	=> 'prominence',
					'field' 	=> 'slug',
					'terms' 	=> $instance['term']
				)
			),
			'showposts' => $instance['num_posts']
			)
		);

  	if ( $missedit->have_posts() ) : while ( $missedit->have_posts() ) : $missedit->the_post(); ?>
    	<div class="post-lead clearfix">
      	<?php if ( $instance['thumb'] == 'before' ) the_post_thumbnail( '60x60' ); ?>
        <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
      	<?php if ( $instance['thumb'] == 'after' ) the_post_thumbnail( '60x60' ); ?>
        <?php echo '<p>' . largo_trim_sentences( get_the_content(), $instance['num_sentences'] ) . '</p>'; ?>
			</div> <!-- /.post-lead -->
    <?php endwhile; ?>
    <?php else: ?>
			<p class="error"><strong><?php _e('You don\'t presently have any posts in the chosen category.</strong> Mark more posts as featured on the add/edit post screen to populate this region.', 'largo') ?></p>

<?php endif; // end more featured posts ?>

		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['num_posts'] = intval( $new_instance['num_posts'] );
		$instance['num_sentences'] = intval( $new_instance['num_sentences'] );
		$instance['term'] = sanitize_key( $new_instance['term'] );
		$instance['thumb'] = sanitize_key( $new_instance['thumb'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title' 		=> __('In Case You Missed It', 'largo'),
			'num_posts' 	=> 2,
			'num_sentences' => 2,
			'thumb' => 'before',
			'term' => 'sidebar-featured'
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', 'largo'); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'term' ); ?>"><?php _e('Display posts from', 'largo'); ?>:</label>
			<select id="<?php echo $this->get_field_id( 'term' ); ?>" name="<?php echo $this->get_field_name( 'term' ); ?>">
			<?php
				$terms = get_terms('prominence');
				foreach ($terms as $term) :
			?>
				<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $instance['term'], $term->slug); ?> ><?php echo $term->name; ?></option>
			<?php
				endforeach;
			?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'num_posts' ); ?>"><?php _e('Number of posts to show:', 'largo'); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_posts' ); ?>" name="<?php echo $this->get_field_name( 'num_posts' ); ?>" value="<?php echo (int) $instance['num_posts']; ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'num_sentences' ); ?>"><?php _e('Excerpt length (# of sentences):', 'largo'); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_sentences' ); ?>" name="<?php echo $this->get_field_name( 'num_sentences' ); ?>" value="<?php echo (int) $instance['num_sentences']; ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'thumb' ); ?>"><?php _e('Thumbnail location', 'largo'); ?></label>
			<select id="<?php echo $this->get_field_id( 'thumb' ); ?>" name="<?php echo $this->get_field_name( 'thumb' ); ?>">
			<?php
				$opts = array( 'before' => 'Before headline', 'after' => 'After headline', 'hidden' => 'Do not show' );
				foreach ( $opts as $opt => $display ) :
			?>
				<option value="<?php echo esc_attr( $opt ); ?>" <?php selected( $instance['thumb'], $opt); ?> ><?php echo $display; ?></option>
			<?php
				endforeach;
			?>
			</select>
		</p>

	<?php
	}
}