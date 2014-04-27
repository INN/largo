<?php
/*
 * List all of the terms in a custom taxonomy
 */
class largo_explore_related_widget extends WP_Widget {

	function largo_explore_related_widget() {
		$widget_ops = array(
			'classname' 	=> 'largo-explore-related',
			'description' 	=> __('A fancy tabbed interface showing posts related to the current post; formerly a theme option.', 'largo')
		);
		$this->WP_Widget( 'largo-explore-related-widget', __('Largo Explore Related', 'largo'), $widget_ops);
	}

	function widget( $args, $instance ) {
		global $post;
		extract( $args );

		// only useful on post pages
		if ( !is_single() ) return;

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'More About', 'largo' ) : $instance['title'], $instance, $this->id_base);

		echo $before_widget;

		if ( $rel_topics = largo_get_post_related_topics( $instance['topics'] ) ) : ?>

			<div id="related-posts" class="idTabs row-fluid clearfix">
				<ul id="related-post-nav" class="span4">
					<li><h5><?php echo esc_html( $title ); ?></h5></li>
					<?php
						foreach ( $rel_topics as $count => $topic ) {
							echo '<li><a href="#rp' . (int) $count . '">' . esc_html( $topic->name ) . '</a></li>';
						}
					?>
				</ul>

				<div class="related-items span8">
					<?php foreach ( $rel_topics as $count => $topic ):
						$rel_posts = largo_get_recent_posts_for_term( $topic, $instance['posts'] );
						?>
						<div id="rp<?php echo (int) $count; ?>">
							<ul>
							<?php
								// the top related post
								$top_post = array_shift( $rel_posts );
								?>

								<li class="top-related clearfix">
								<?php
									$permalink = get_permalink( $top_post->ID );
									$post_title = $top_post->post_title;
									echo '<h3><a href="' . esc_url( $permalink ) . '" title="' . esc_attr( sprintf( __( 'Read %s', 'largo' ), $post_title ) ) . '">' . esc_html( $post_title ) . '</a></h3>';
									if ( get_the_post_thumbnail( $top_post->ID ) )
										echo '<a href="' . esc_url( $permalink ) . '"/>' . get_the_post_thumbnail( $top_post->ID, '60x60' ) . '</a>';
									if ($top_post->post_excerpt) {
										echo '<p>' . $top_post->post_excerpt . '</p>';
									} else {
										echo '<p>' . largo_trim_sentences($top_post->post_content, 2) . '</p>';
									}
								?>
								</li>
								<?php
								// the other related posts
								foreach ( $rel_posts as $rel_post ) {
									echo '<li><a href="' . esc_url( get_permalink( $rel_post->ID ) ) . '" title="' . esc_attr($topic->name) . '">' . $rel_post->post_title . '</a></li>';
								}
								?>
							</ul>

							<p><a href="<?php echo esc_url( get_term_link( $topic ) ); ?>" title="<?php echo esc_attr($topic->name); ?>" target="_blank"><strong><?php printf( __('View all %1$s %2$s &rarr;', 'largo'), $topic->name, of_get_option( 'posts_term_plural' ) ); ?></strong></a></p>
						</div> <!-- /#rpX -->
					<?php endforeach; ?>
				</div> <!-- /.items -->
			</div> <!-- /#related-posts -->
		<?php

		endif; // if ( $rel_topics )


		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['topics'] = (int) $new_instance['topics'];
		$instance['posts'] = (int) $new_instance['posts'];
		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => __('More About', 'largo'), 'topics' => 6, 'posts' => 3) );
		$title = esc_attr( $instance['title'] );
		$topics = $instance['topics'];
		$posts = $instance['posts'];
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title', 'largo' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p>
			<label for="<?php echo $this->get_field_id('topics'); ?>"><?php _e('Max # of terms to include', 'largo'); ?>:</label>
			<select name="<?php echo $this->get_field_name('topics'); ?>" id="<?php echo $this->get_field_id('topics'); ?>">
			<?php
			for ($i = 1; $i < 10; $i++) {
				echo '<option value="', $i, '"', selected($topics, $i, FALSE), '>', $i, '</option>';
			} ?>
			</select>
			<div class="description"><?php _e( 'Previous versions of Largo set this at 6.', 'largo' ); ?></div>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('posts'); ?>"><?php _e('Max # of posts per term', 'largo'); ?>:</label>
			<select name="<?php echo $this->get_field_name('posts'); ?>" id="<?php echo $this->get_field_id('posts'); ?>">
			<?php
			for ($i = 1; $i < 10; $i++) {
				echo '<option value="', $i, '"', selected($posts, $i, FALSE), '>', $i, '</option>';
			} ?>
			</select>
			<div class="description"><?php _e( 'Previous versions of Largo set this at 3.', 'largo' ); ?></div>
		</p>

	<?php
	}

}
