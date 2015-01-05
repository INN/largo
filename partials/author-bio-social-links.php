<ul class="social-links">
	<?php if ( $fb = $author_obj->fb ) { ?>
		<li class="facebook">
			<div class="fb-follow" data-href="https://facebook.com/<?php echo largo_fb_url_to_username(esc_url( $fb )); ?>" data-layout="button_count" data-show-faces="false" data-width="225"></div>
		</li>
	<?php } ?>

	<?php if ( $twitter = $author_obj->twitter ) { ?>
		<li class="twitter">
			<a href="https://twitter.com/<?php echo largo_twitter_url_to_username ( $twitter ); ?>" class="twitter-follow-button" data-show-count="false"><?php printf( __('Follow @%1$s', 'largo'), largo_twitter_url_to_username ( $twitter ) ); ?></a>
		</li>
	<?php } ?>

	<?php if ( $email = $author_obj->user_email ) { ?>
		<li class="email">
			<a href="mailto:<?php echo esc_attr( $email ); ?>" title="e-mail <?php echo esc_attr( $author_obj->display_name ); ?>"><i class="icon-mail"></i></a>
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
			printf( __( '<li class="author-posts-link"><a class="url" href="%1$s" rel="author" title="See all posts by %1$s">More by %2$s</a></li>', 'largo' ),
				get_author_posts_url($author_obj->ID),
				esc_attr( $author_obj->first_name )
			);
		}
	?>
</ul>
