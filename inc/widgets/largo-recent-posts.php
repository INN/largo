<?php
/*
 * Largo Recent Posts
 */
class largo_recent_posts_widget extends WP_Widget {

	function largo_recent_posts_widget() {
		$widget_ops = array(
			'classname' => 'largo-recent-posts',
			'description' => __('Show your most recent posts with thumbnails and excerpts', 'largo')
		);
		$this->WP_Widget( 'largo-recent-posts-widget', __('Largo Recent Posts', 'largo'), $widget_ops);
	}

	function widget( $args, $instance ) {
		global $ids; // an array of post IDs already on a page so we can avoid duplicating posts
		$posts_term = of_get_option( 'posts_term_plural', 'Posts' );
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __('Recent ' . $posts_term, 'largo') : $instance['title'], $instance, $this->id_base);

		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;?>

			<?php

			$thumb = isset( $instance['thumbnail_display'] ) ? $instance['thumbnail_display'] : 'small';
	        $excerpt = isset( $instance['excerpt_display'] ) ? $instance['excerpt_display'] : 'num_sentences';

			$query_args = array (
				'post__not_in' 	=> get_option( 'sticky_posts' ),
				'showposts' 	=> $instance['num_posts'],
				'post_status'	=> 'publish'
			);
			if ( isset( $instance['avoid_duplicates'] ) && $instance['avoid_duplicates'] === 1)
				$query_args['post__not_in'] = $ids;
			if ($instance['cat'] != '')
				$query_args['cat'] = $instance['cat'];
			if ($instance['tag'] != '')
				$query_args['tag'] = $instance['tag'];
			if ($instance['author'] != '')
				$query_args['author'] = $instance['author'];
			if ($instance['taxonomy'] != '')
				$query_args['tax_query'] = array(
					array(
						'taxonomy' => $instance['taxonomy'],
						'field' => 'slug',
						'terms' => $instance['term']
					)
				);

			// if we're just showing a list of headlines, wrap the elements in a ul
			if ($excerpt == 'none')
	        	echo '<ul>';

			$my_query = new WP_Query( $query_args );
          		if ( $my_query->have_posts() ) :

          			$output = '';

          			while ( $my_query->have_posts() ) : $my_query->the_post(); $ids[] = get_the_ID();

          				// wrap the items in li if we're just showing a list of headlines, otherwise use a div
          				$output .= ( $excerpt == 'none' || $thumb == 'none' ) ? '<li>' : '<div class="post-lead clearfix"><h5>';

          				// the headline
          				$output .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a></h5>';

          				// the thumbnail image (if we're using one)
	                    if ($thumb == 'small')
	                    	$output .= '<a href="' . get_permalink() . '">' . get_the_post_thumbnail( get_the_ID(), '60x60') . '</a>';
	                    elseif ($thumb == 'medium')
	                    	$output .= '<a href="' . get_permalink() . '">' . get_the_post_thumbnail() . '</a>';
	                    elseif ($thumb == 'large')
	                    	$output .= '<a href="' . get_permalink() . '">' . get_the_post_thumbnail( get_the_ID(), 'large') . '</a>';

	                    // the excerpt
	                    if ($excerpt == 'num_sentences')
	                    	$output .= '<p>' . largo_trim_sentences( get_the_content(), $instance['num_sentences'] ) . '</p>';
	                    elseif ($excerpt == 'custom_excerpt')
	                    	$output .= '<p>' . get_the_excerpt() . '</p>';

	                    // close the item
	                  	$output .= ( $excerpt == 'none' || $thumb == 'none' ) ? '</li>' : '</div>';

	                  endwhile;

	                  // print all of the items
	                  echo $output;
	            else:
	    			printf(__('<p class="error"><strong>You don\'t have any recent %s.</strong></p>', 'largo'), strtolower( $posts_term ) );
	    		endif; // end more featured posts

	    	// close the ul if we're just showing a list of headlines
	    	if ($excerpt == 'none')
	        	echo '</ul>';

    		if($instance['linkurl'] !='') {?>
				<p class="morelink"><a href="<?php echo esc_url( $instance['linkurl'] ); ?>"><?php echo esc_html( $instance['linktext'] ); ?></a></p>
			<?php }
		echo $after_widget;
		wp_reset_postdata();
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['num_posts'] = intval( $new_instance['num_posts'] );
		$instance['avoid_duplicates'] = ! empty( $new_instance['avoid_duplicates'] ) ? 1 : 0;
		$instance['thumbnail_display'] = sanitize_key( $new_instance['thumbnail_display'] );
		$instance['excerpt_display'] = sanitize_key( $new_instance['excerpt_display'] );
		$instance['num_sentences'] = intval( $new_instance['num_sentences'] );
		$instance['cat'] = intval( $new_instance['cat'] );
		$instance['tag'] = sanitize_text_field( $new_instance['tag'] );
		$instance['taxonomy'] = sanitize_text_field( $new_instance['taxonomy'] );
		$instance['term'] = sanitize_text_field( $new_instance['term'] );
		$instance['author'] = intval( $new_instance['author'] );
		$instance['linktext'] = sanitize_text_field( $new_instance['linktext'] );
		$instance['linkurl'] = esc_url_raw( $new_instance['linkurl'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title' 			=> __('Recent ' . of_get_option( 'posts_term_plural', 'Posts' ), 'largo'),
			'num_posts' 		=> 5,
			'avoid_duplicates'	=> '',
			'thumbnail_display' => 'small',
			'excerpt_display' 	=> 'num_sentences',
			'num_sentences' 	=> 2,
			'cat' 				=> 0,
			'tag'				=> '',
			'taxonomy'			=> '',
			'term'				=> '',
			'author' 			=> '',
			'linktext' 			=> '',
			'linkurl' 			=> ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$duplicates = $instance['avoid_duplicates'] ? 'checked="checked"' : '';
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
			<input class="checkbox" type="checkbox" <?php echo $duplicates; ?> id="<?php echo $this->get_field_id('avoid_duplicates'); ?>" name="<?php echo $this->get_field_name('avoid_duplicates'); ?>" /> <label for="<?php echo $this->get_field_id('avoid_duplicates'); ?>"><?php _e('Avoid Duplicate Posts?', 'largo'); ?></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'thumbnail_display' ); ?>"><?php _e('Thumbnail Image', 'largo'); ?></label>
			<select id="<?php echo $this->get_field_id('thumbnail_display'); ?>" name="<?php echo $this->get_field_name('thumbnail_display'); ?>" class="widefat" style="width:90%;">
			    <option <?php selected( $instance['thumbnail_display'], 'small'); ?> value="small"><?php _e('Small (60x60)', 'largo'); ?></option>
			    <option <?php selected( $instance['thumbnail_display'], 'medium'); ?> value="medium"><?php _e('Medium (150x150)', 'largo'); ?></option>
			    <option <?php selected( $instance['thumbnail_display'], 'large'); ?> value="large"><?php _e('Large (Full width of the widget)', 'largo'); ?></option>
			    <option <?php selected( $instance['thumbnail_display'], 'none'); ?> value="none"><?php _e('None', 'largo'); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'excerpt_display' ); ?>"><?php _e('Excerpt Display', 'largo'); ?></label>
			<select id="<?php echo $this->get_field_id('excerpt_display'); ?>" name="<?php echo $this->get_field_name('excerpt_display'); ?>" class="widefat" style="width:90%;">
			    <option <?php selected( $instance['excerpt_display'], 'num_sentences'); ?> value="num_sentences"><?php _e('Use # of Sentences', 'largo'); ?></option>
			    <option <?php selected( $instance['excerpt_display'], 'custom_excerpt'); ?> value="custom_excerpt"><?php _e('Use Custom Post Excerpt', 'largo'); ?></option>
			    <option <?php selected( $instance['excerpt_display'], 'none'); ?> value="none"><?php _e('None', 'largo'); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'num_sentences' ); ?>"><?php _e('Excerpt Length (# of Sentences):', 'largo'); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_sentences' ); ?>" name="<?php echo $this->get_field_name( 'num_sentences' ); ?>" value="<?php echo (int) $instance['num_sentences']; ?>" style="width:90%;" />
		</p>

		<p><strong><?php _e('Limit by Author, Categories or Tags', 'largo'); ?></strong><br /><small><?php _e('Select an author or category from the dropdown menus or enter post tags separated by commas (\'cat,dog\')', 'largo'); ?></small></p>
		<p>
			<label for="<?php echo $this->get_field_id('author'); ?>"><?php _e('Limit to author: ', 'largo'); ?><br />
			<?php wp_dropdown_users(array('name' => $this->get_field_name('author'), 'show_option_all' => __('None (all authors)', 'largo'), 'selected'=>$instance['author'])); ?></label>

		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>"><?php _e('Limit to category: ', 'largo'); ?>
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => __('None (all categories)', 'largo'), 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('tag'); ?>"><?php _e('Limit to tags:', 'largo'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('tag'); ?>" name="<?php echo $this->get_field_name('tag'); ?>" type="text" value="<?php echo $instance['tag']; ?>" />
		</p>

		<p><strong><?php _e('Limit by Custom Taxonomy', 'largo'); ?></strong><br /><small><?php _e('Enter the slug for the custom taxonomy you want to query and the term within that taxonomy to display', 'largo'); ?></small></p>
		<p>
			<label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Taxonomy:', 'largo'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>" type="text" value="<?php echo $instance['taxonomy']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('term'); ?>"><?php _e('Term:', 'largo'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('term'); ?>" name="<?php echo $this->get_field_name('term'); ?>" type="text" value="<?php echo $instance['term']; ?>" />
		</p>

		<p><strong><?php _e('More Link', 'largo'); ?></strong><br /><small><?php _e('If you would like to add a more link at the bottom of the widget, add the link text and url here.', 'largo'); ?></small></p>
		<p>
			<label for="<?php echo $this->get_field_id('linktext'); ?>"><?php _e('Link text:', 'largo'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('linktext'); ?>" name="<?php echo $this->get_field_name('linktext'); ?>" type="text" value="<?php echo esc_attr( $instance['linktext'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('linkurl'); ?>"><?php _e('URL:', 'largo'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('linkurl'); ?>" name="<?php echo $this->get_field_name('linkurl'); ?>" type="text" value="<?php echo esc_attr( $instance['linkurl'] ); ?>" />
		</p>

	<?php
	}
}