<?php
/*
 * List all of the terms in a custom taxonomy
 */
class largo_series_posts_widget extends WP_Widget {

	function largo_series_posts_widget() {
		$widget_ops = array(
			'classname' 	=> 'largo-series-posts',
			'description' 	=> __('Lists posts in the given series', 'largo')
		);
		$this->WP_Widget( 'largo-series-posts-widget', __('Largo Series Posts', 'largo'), $widget_ops);
	}

	function widget( $args, $instance ) {
		// instance: num, series (id), title, heading
		extract( $args );

		//get the posts
 		$series_posts = largo_get_series_posts( $instance['series'], $instance['num'] );

 		if ( ! $series_posts ) return; //output nothing if no posts found

		//$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);

		echo $before_widget;

		//if ( $title ) echo $before_title . $title . $after_title;

		//term link
		$term = get_term( $instance['series'], 'series' );
		echo '<h5 class="top-tag"><a href="' . get_term_link( (int) $instance['series'], 'series' ) . '">' . $term->name . '</a></h5>';

	 	//first post
	 	$series_posts->the_post();
	 	$instance['test'] = 'banana';

	 	include(locate_template('content-tiny.php'));

 		//divider
 		echo '<h5 class="series-split top-tag">' . esc_html( $instance['heading'] ) .'</h5><ul>';

 		while ( $series_posts->have_posts() ) {
	 		$series_posts->the_post();
	 		echo '<li>';
	 		post_type_icon();
	 		echo '<a href="';
	 		the_permalink();
	 		echo '">';
	 		the_title();
	 		echo '</a></li>';
 		}

 		echo '</ul>';

 		echo '<a class="more" href="' . get_term_link( (int) $instance['series'], 'series' ) . '">' . __('Complete Coverage', 'largo') . "</a>";

 		wp_reset_postdata();
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		//$instance['title'] = strip_tags($new_instance['title']);
		$instance['heading'] = sanitize_text_field( $new_instance['heading'] );
		$instance['num'] = (int)$new_instance['num'];
		$instance['series'] = sanitize_key( $new_instance['series'] );
		$instance['show_byline'] = (int) $new_instance['show_byline'];
		$instance['thumbnail_location'] = sanitize_key( $new_instance['thumbnail_location'] );
		return $instance;
	}

	function form( $instance ) {
		//Defaults
		// to control: which series, # of posts, explore heading...
		// @todo enhance with more control over thumbnail, icon, etc
		$instance = wp_parse_args( (array) $instance, array(
			'num' => 4,
			'heading' => 'Explore:',
			'thumbnail_location' => 'before',
			'show_byline' => 0,
			'series' => 'null')
		);
		//$title = esc_attr( $instance['title'] );
		$num = $instance['num'];
		$heading = esc_attr( $instance['heading'] );
		?>

		<p>
			<label for="<?php echo $this->get_field_id('series'); ?>"><?php _e( 'Series', 'largo'); ?>:</label><br/>
			<select name="<?php echo $this->get_field_name('series'); ?>" id="<?php echo $this->get_field_id('series'); ?>">
			<?php
			$terms = get_terms( 'series' );
			foreach ( $terms as $term ) {
				echo '<option value="', $term->term_id, '"', selected($instance['series'], $term->term_id, FALSE), '>', $term->name, '</option>';
			} ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('num'); ?>"><?php _e('Number of Posts to Display', 'largo'); ?>:</label>
			<select name="<?php echo $this->get_field_name('num'); ?>" id="<?php echo $this->get_field_id('num'); ?>">
			<?php
			for ($i = 1; $i < 6; $i++) {
				echo '<option value="', $i, '"', selected($num, $i, FALSE), '>', $i, '</option>';
			} ?>
			</select>
		</p>

		<p><input id="<?php echo $this->get_field_id('show_byline'); ?>" name="<?php echo $this->get_field_name('show_byline'); ?>" type="checkbox" value="1" <?php checked( $instance['show_byline'], 1);?> />
			<label for="<?php echo $this->get_field_id('show_byline'); ?>"><?php _e( 'Show date on first post', 'largo' ); ?></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('thumbnail_location'); ?>"><?php _e('Thumbnail position on first post', 'largo'); ?>:</label>
			<select name="<?php echo $this->get_field_name('thumbnail_location'); ?>" id="<?php echo $this->get_field_id('thumbnail_location'); ?>">
			<?php
			$choices = array( 'before' => __( 'Before Headline', 'largo' ), 'after' => __( 'After Headline', 'largo' ) );
			foreach( $choices as $i => $display ) {
				echo '<option value="', $i, '"', selected($instance['thumbnail_location'], $i, false), '>', $display, '</option>';
			} ?>
			</select>
		</p>

		<p><label for="<?php echo $this->get_field_id('heading'); ?>"><?php _e( 'Divider heading', 'largo' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id('heading'); ?>" name="<?php echo $this->get_field_name('heading'); ?>" type="text" value="<?php echo esc_attr( $heading ); ?>" /></p>

	<?php
	}

}
