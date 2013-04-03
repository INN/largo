<div class="author-box author vcard clearfix">
	<?php if ( is_author() ) { ?>
		<h1 class="fn n"><?php echo esc_attr( get_the_author() ); ?></h1>
	<?php } else {
		printf( __('<h5>About <span class="fn n">%1$s</span><span class="author-posts-link"><a class="url" href="%2$s" rel="author" title="See all posts by %3$s">More by this author</a></span></h5>', 'largo'),
			esc_attr( get_the_author() ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( get_the_author_meta( 'display_name' ) )
			);
	} ?>

	<?php if ( largo_has_gravatar( get_the_author_meta('user_email') ) ) : ?>
			<div class="photo">
			<?php echo get_avatar( get_the_author_meta('ID'), 96, '', get_the_author_meta('display_name') ); ?>
			</div>
		<?php endif;
	?>

	<?php if ( get_the_author_meta( 'description' ) ) : ?>
		<p><?php echo esc_attr( get_the_author_meta( 'description' ) ); ?></p>
	<?php endif; ?>

	<ul class="social-links">
		<?php if ( get_the_author_meta( 'fb' ) ) : ?>
		<li class="facebook">
			<div class="fb-subscribe" data-href="<?php echo esc_url( get_the_author_meta( 'fb' ) ); ?>" data-layout="button_count" data-show-faces="false" data-width="225"></div>
		</li>
		<?php endif; ?>

		<?php if ( get_the_author_meta( 'twitter' ) ) : ?>
		<li class="twitter">
			<a href="<?php echo esc_url( get_the_author_meta( 'twitter' ) ); ?>" class="twitter-follow-button" data-show-count="false" data-lang="en"><?php printf( __('Follow @%1$s', 'largo'), twitter_url_to_username ( get_the_author_meta( 'twitter' ) ) ); ?></a>
		</li>
		<?php endif; ?>

		<?php if ( get_the_author_meta( 'user_email' ) ) : ?>
		<li class="email">
			<a href="mailto:<?php echo esc_attr( get_the_author_meta( 'user_email' ) ); ?>" title="e-mail <?php echo esc_attr( get_the_author() ); ?>"><i class="icon-mail"></i></a>
		</li>
		<?php endif; ?>

		<?php if ( get_the_author_meta( 'googleplus' ) ) : ?>
		<li class="gplus">
			<a href="<?php echo esc_url( get_the_author_meta( 'googleplus' ) ); ?>" title="<?php echo esc_attr( get_the_author() ); ?> on Google+" rel="me"><i class="icon-gplus"></i></a>
		</li>
		<?php endif; ?>

		<?php if ( get_the_author_meta( 'linkedin' ) ) : ?>
		<li class="linkedin">
			<a href="<?php echo esc_url( get_the_author_meta( 'linkedin' ) ); ?>" title="<?php echo esc_attr( get_the_author() ); ?> on LinkedIn"><i class="icon-linkedin"></i></a>
		</li>
		<?php endif; ?>
	</ul>

</div>