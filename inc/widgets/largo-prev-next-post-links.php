<?php
/*
 * List all of the terms in a custom taxonomy
 */
class largo_prev_next_post_links_widget extends WP_Widget {

	function largo_prev_next_post_links_widget() {
		$widget_ops = array(
			'classname' 	=> 'largo-prev-next-post-links',
			'description' 	=> __('Prev/next post navigation, typically at the bottom of single posts; formerly a theme option.', 'largo')
		);
		$this->WP_Widget( 'largo-prev-next-post-links-widget', __('Largo Prev/Next Links', 'largo'), $widget_ops);
	}

	function widget( $args, $instance ) {
		global $post;
		extract( $args );

		// only useful on post pages
		if ( !is_single() ) return;

		echo $before_widget;

		//Someday we may want to have widget options for language for "Previous" and "Next," but for now this should suffice.
		?>
		<nav id="nav-below" class="pager post-nav clearfix">
			<?php
				if ( $prev = get_previous_post() ) {
					printf( __('<div class="previous"><a href="%1$s"><h5>Previous %2$s</h5><span class="meta-nav">%3$s</span></a></div>', 'largo'),
						get_permalink( $prev->ID ),
						of_get_option( 'posts_term_singular' ),
						$prev->post_title
					);
				}
				if ( $next = get_next_post() ) {
					printf( __('<div class="next"><a href="%1$s"><h5>Next %2$s</h5><span class="meta-nav">%3$s</span></a></div>', 'largo'),
						get_permalink( $next->ID ),
						of_get_option( 'posts_term_singular' ),
						$next->post_title
					);
				}
			?>
		</nav><!-- #nav-below -->
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		//$instance = $old_instance;
		//$instance['title'] = strip_tags($new_instance['title']);
		return $new_instance;
	}

	//nothing to see here
	function form( $instance ) {}

}
