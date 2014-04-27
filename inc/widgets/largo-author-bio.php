<?php
/*
 * Author Bio Widget
 */
class largo_author_widget extends WP_Widget {

	function largo_author_widget() {
		$widget_ops = array(
			'classname' 	=> 'largo-author',
			'description'	=> __('Show the author bio in a widget', 'largo')
		);
		$this->WP_Widget( 'largo-author-widget', __('Largo Author Bio', 'largo'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$authors = array();
		$bios = "";

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base);

		if( is_single() || is_author() ):

				if ( is_single() ) {
					if ( function_exists( 'get_coauthors' ) ) {
						$authors = get_coauthors( get_queried_object_id() );
					} else {
						$authors = array( get_user_by( 'id', get_queried_object()->post_author ) );
					}
				} else if ( is_author() ) {
					$authors = array( get_queried_object() );
				}

				// make sure we have at least one bio before we show the widget
				foreach ( $authors as $key => $author ) {
					$bio = trim( $author->description );
					if ( empty( $bio ) ) {
						unset( $authors[$key] );
					} else {
						$bios .= $bio;
					}
				}
				if ( !is_author() && empty($bios) ) {
					return;
				}

				echo $before_widget;
				if ($title) {
					echo $before_title . $title . $after_title;
				}

				// BEGIN what used to be in largo-author-box.php

				foreach( $authors as $author ) {
				?>

				<div class="author-box author vcard clearfix">
					<?php if ( is_author() ) { ?>
						<h1 class="fn n"><?php echo $author->display_name; ?></h1>
					<?php } else {
						printf( __('<h5>About <span class="fn n">%1$s</span><span class="author-posts-link"><a class="url" href="%2$s" rel="author" title="See all posts by %1$s">More by this author</a></span></h5>', 'largo'),
							esc_attr( $author->display_name ),
							esc_url( get_author_posts_url( $author->ID ) )
							);
					} ?>

					<?php if ( largo_has_gravatar( $author->user_email ) ) : ?>
							<div class="photo">
							<?php echo get_avatar( $author->ID, 96, '', $author->display_name ); ?>
							</div>
						<?php endif;
					?>

					<?php if ( $description = get_the_author_meta( 'description', $author->ID ) ) : ?>
						<p><?php echo esc_attr( $description ); ?></p>
					<?php endif; ?>

					<ul class="social-links">
						<?php if ( $fb = get_the_author_meta( 'fb', $author->ID ) ) : ?>
						<li class="facebook">
							<div class="fb-subscribe" data-href="<?php echo esc_url( $fb ); ?>" data-layout="button_count" data-show-faces="false" data-width="225"></div>
						</li>
						<?php endif; ?>

						<?php if ( $twitter = get_the_author_meta( 'twitter', $author->ID ) ) : ?>
						<li class="twitter">
							<a href="<?php echo esc_url( $twitter ); ?>" class="twitter-follow-button" data-show-count="false"><?php printf( __('Follow @%1$s', 'largo'), largo_twitter_url_to_username ( $twitter ) ); ?></a>
						</li>
						<?php endif; ?>

						<?php if ( $email = get_the_author_meta( 'user_email', $author->ID ) ) : ?>
						<li class="email">
							<a href="mailto:<?php echo esc_attr( $email ); ?>" title="e-mail <?php echo esc_attr( $author->display_name ); ?>"><i class="icon-mail"></i></a>
						</li>
						<?php endif; ?>

						<?php if ( $googleplus = get_the_author_meta( 'googleplus', $author->ID ) ) : ?>
						<li class="gplus">
							<a href="<?php echo esc_url( $googleplus ); ?>" title="<?php echo esc_attr( $author->display_name ); ?> on Google+" rel="me"><i class="icon-gplus"></i></a>
						</li>
						<?php endif; ?>

						<?php if ( $linkedin = get_the_author_meta( 'linkedin', $author->ID ) ) : ?>
						<li class="linkedin">
							<a href="<?php echo esc_url( $linkedin ); ?>" title="<?php echo esc_attr( $author->display_name ); ?> on LinkedIn"><i class="icon-linkedin"></i></a>
						</li>
						<?php endif; ?>
					</ul>

				</div>

				<?php }
				// END what used to be in largo-author-box.php

				echo $after_widget;
		else:
			_e("Not a valid author context");
		endif;

	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'title' => __('Author', 'largo') );
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'largo'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:90%;" />
		</p>
	<?php
	}
}