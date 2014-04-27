<?php
/*
 * List all of the terms in a custom taxonomy
 */
class largo_related_posts_widget extends WP_Widget {

	function largo_related_posts_widget() {
		$widget_ops = array(
			'classname' 	=> 'largo-related-posts',
			'description' 	=> __('Lists posts related to the current post', 'largo')
		);
		$this->WP_Widget( 'largo-related-posts-widget', __('Largo Related Posts', 'largo'), $widget_ops);
	}

	function widget( $args, $instance ) {
		global $post;
		extract( $args );

		// only useful on post pages
		if ( !is_single() ) return;

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Read Next', 'largo' ) : $instance['title'], $instance, $this->id_base);

		echo $before_widget;

		if ( $title ) echo $before_title . $title . $after_title;

 		$related = new Largo_Related( $instance['qty'] );

 		//get the related posts
 		$rel_posts = new WP_Query( array(
 			'post__in' => $related->ids(),
 			'nopaging' => 1,
 			'posts_per_page' => $instance['qty'],
 			'ignore_sticky_posts' => 1
 		) );

 		if ( $rel_posts->have_posts() ) {

	 		echo '<ul class="related">';

	 		while ( $rel_posts->have_posts() ) {
		 		$rel_posts->the_post();
		 		echo '<li>';
				echo '<a href="' . get_permalink() . '"/>' . get_the_post_thumbnail( get_the_ID(), 'thumbnail', array('class'=>'alignleft') ) . '</a>';
				?>
				<h4><a href="<?php the_permalink(); ?>" title="Read: <?php esc_attr( the_title('','', FALSE) ); ?>"><?php the_title(); ?></a></h4>
				<h5 class="byline"><time class="entry-date updated dtstamp pubdate" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php largo_time(); ?></time></h5>
				<?php // post excerpt/summary
				if ($post->post_excerpt) {
					echo '<p>' . $post->post_excerpt . '</p>';
				} else {
					echo '<p>' . largo_trim_sentences($post->post_content, 2) . '</p>';
				}
				?>
				<h5 class="byline"><span class="by-author"><?php largo_byline( true, true ); ?></span></h5>
				<?php
		 		echo '</li>';
	 		}

	 		echo "</ul>";
 		}
 		wp_reset_postdata();
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['qty'] = (int) $new_instance['qty'];
		$instance['show_byline'] = (int) $new_instance['show_byline'];
		$instance['thumbnail_location'] = sanitize_key( $new_instance['thumbnail_location'] );
		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Read Next', 'qty' => 1, 'show_byline' => 0, 'thumbnail_location' => 'before') );
		$title = esc_attr( $instance['title'] );
		$qty = $instance['qty'];
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title', 'largo' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p>
			<label for="<?php echo $this->get_field_id('qty'); ?>"><?php _e('Number of Posts to Display', 'largo'); ?>:</label>
			<select name="<?php echo $this->get_field_name('qty'); ?>" id="<?php echo $this->get_field_id('qty'); ?>">
			<?php
			for ($i = 1; $i < 6; $i++) {
				echo '<option value="', $i, '"', selected($qty, $i, FALSE), '>', $i, '</option>';
			} ?>
			</select>
			<div class="description"><?php _e( "It's best to keep this at just one.", 'largo' ); ?></div>
		</p>

		<p><input id="<?php echo $this->get_field_id('show_byline'); ?>" name="<?php echo $this->get_field_name('show_byline'); ?>" type="checkbox" value="1" <?php checked( $instance['show_byline'], 1);?> />
			<label for="<?php echo $this->get_field_id('show_byline'); ?>"><?php _e( 'Show date with each post', 'largo' ); ?></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('thumbnail_location'); ?>"><?php _e('Thumbnail position', 'largo'); ?>:</label>
			<select name="<?php echo $this->get_field_name('thumbnail_location'); ?>" id="<?php echo $this->get_field_id('thumbnail_location'); ?>">
			<?php
			$choices = array( 'before' => __( 'Before Headline', 'largo' ), 'after' => __( 'After Headline', 'largo' ) );
			foreach( $choices as $i => $display ) {
				echo '<option value="', $i, '"', selected($instance['thumbnail_location'], $i, false), '>', $display, '</option>';
			} ?>
			</select>
		</p>

	<?php
	}

}
