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

			<?php
			$my_query = new WP_Query( array(
				'cat' 			=> $instance['cat'],
				'tag' 			=> $instance['tag'],
				'author' 		=> $instance['author'],
				'post__not_in' 	=> get_option( 'sticky_posts' ),
				'showposts' 	=> $instance['num_posts']
			) );
          		if ( $my_query->have_posts() ) :
          			while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
	                  	<div class="post-lead clearfix">
	                      	<h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
	                      	<?php the_post_thumbnail( '60x60' ); ?>
	                     	<?php echo '<p>' . largo_trim_sentences(get_the_content(), $instance['num_sentences']) . '</p>'; ?>
	                  	</div> <!-- /.post-lead -->
	            <?php endwhile;
	            else: ?>
	    		<p class="error"><strong>You don't have any recent posts.</strong></p>

    		<?php endif; // end more featured posts

    		if($instance['linkurl'] !='') {?>
				<p class="morelink"><a href="<?php echo $instance['linkurl']; ?>"><?php echo $instance['linktext']; ?></a></p>
			<?php }
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['num_posts'] = strip_tags( $new_instance['num_posts'] );
		$instance['num_sentences'] = strip_tags( $new_instance['num_sentences'] );
		$instance['cat'] = $new_instance['cat'];
		$instance['tag'] = $new_instance['tag'];
		$instance['author'] = $new_instance['author'];
		$instance['linktext'] = $new_instance['linktext'];
		$instance['linkurl'] = $new_instance['linkurl'];
		$instance['widget_class'] = $new_instance['widget_class'];
		$instance['hidden_tablet'] = $new_instance['hidden_tablet'] ? 1 : 0;
		$instance['hidden_phone'] = $new_instance['hidden_phone'] ? 1 : 0;
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title' 		=> 'Recent Stories',
			'num_posts' 	=> 5,
			'num_sentences' => 2,
			'cat' 			=> 0,
			'tag'			=> '',
			'author' 		=> '',
			'linktext' 		=> '',
			'linkurl' 		=> '',
			'widget_class' 	=> 'default',
			'hidden_tablet' => '',
			'hidden_phone'	=> ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$tablet = $instance['hidden_tablet'] ? 'checked="checked"' : '';
		$phone = $instance['hidden_phone'] ? 'checked="checked"' : '';
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'largo-recent-posts'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'num_posts' ); ?>"><?php _e('Number of posts to show:', 'largo-recent-posts'); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_posts' ); ?>" name="<?php echo $this->get_field_name( 'num_posts' ); ?>" value="<?php echo $instance['num_posts']; ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'num_sentences' ); ?>"><?php _e('Excerpt Length (# of Sentences):', 'largo-footer-featured'); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_sentences' ); ?>" name="<?php echo $this->get_field_name( 'num_sentences' ); ?>" value="<?php echo $instance['num_sentences']; ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>"><?php _e('Limit to category: '); ?>
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => __('None (all categories)'), 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('tag'); ?>"><?php _e('Limit to tags:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('tag'); ?>" name="<?php echo $this->get_field_name('tag'); ?>" type="text" value="<?php echo $instance['tag']; ?>" />
			<br /><small><?php _e('Enter post tags separated by commas (\'cat,dog\')'); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('author'); ?>"><?php _e('Limit to author: '); ?>
			<?php wp_dropdown_users(array('name' => $this->get_field_name('author'), 'show_option_all' => __('None (all authors)'), 'selected'=>$instance['author'])); ?></label>

		</p>

		<p>
			<label for="<?php echo $this->get_field_id('linktext'); ?>"><?php _e('Link text:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('linktext'); ?>" name="<?php echo $this->get_field_name('linktext'); ?>" type="text" value="<?php echo $instance['linktext']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('linkurl'); ?>"><?php _e('URL:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('linkurl'); ?>" name="<?php echo $this->get_field_name('linkurl'); ?>" type="text" value="<?php echo $instance['linkurl']; ?>" />
		</p>

		<label for="<?php echo $this->get_field_id( 'widget_class' ); ?>"><?php _e('Widget Background', 'largo-recent-posts'); ?></label>
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