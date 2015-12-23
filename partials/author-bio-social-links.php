<?php
/*
 * Social links for the author described in $author_object
 *
 * @param WP_User $author_object the author
 * @since 0.5.3
 */

	$email = $author_obj->user_email;

	/**
	 * Figure out whether to hide the author's email address
	 */
	$user_meta = get_user_meta($author_obj->ID);
	$show_email = $user_meta['show_email'][0];
?>
<ul class="social-links">
	<?php if ( $fb = $author_obj->fb ) { ?>
		<li class="facebook">
			<a href="https://facebook.com/<?php echo largo_fb_url_to_username(esc_url( $fb )); ?>" title="<?php echo esc_attr( $author_obj->display_name ); ?> on Facebook" rel="me"><i class="icon-facebook"></i></a>
		</li>
	<?php } ?>

	<?php if ( $twitter = $author_obj->twitter ) { ?>
		<li class="twitter">
			<a href="https://twitter.com/<?php echo largo_twitter_url_to_username ( $twitter ); ?>"><i class="icon-twitter"></i></a>
		</li>
	<?php } ?>

	<?php if ( $email && $show_email !== 'off' ) { ?>
		<li class="email">
			<a class="email" href="mailto:<?php echo esc_attr( $email ); ?>" title="e-mail <?php echo esc_attr( $author_obj->display_name ); ?>"><i class="icon-mail"></i></a>
		</li>
	<?php } ?>

	<?php if ( $googleplus = $author_obj->googleplus ) { ?>
		<li class="gplus">
			<a href="<?php echo esc_url( $googleplus ); ?>" title="<?php echo esc_attr( $author_obj->display_name ); ?> on Google+" rel="me"><i class="icon-gplus"></i></a>
		</li>
	<?php } ?>

	<?php if ( $linkedin = $author_obj->linkedin ) { ?>
		<li class="linkedin">
			<a href="<?php echo esc_url( $linkedin ); ?>" title="<?php echo esc_attr( $author_obj->display_name ); ?> on LinkedIn"><i class="icon-linkedin"></i></a>
		</li>
	<?php } ?>

	<?php
		if ( !is_author() ) {
			printf( __( '<li class="author-posts-link"><a class="url" href="%1$s" rel="author" title="See all posts by %2$s">More by %2$s</a></li>', 'largo' ),
				get_author_posts_url( $author_obj->ID, $author_obj->user_nicename ),
				esc_attr( $author_obj->first_name )
			);
		}
	?>
</ul>
