<div class="author-box clearfix">
	<?php if ( is_author() ) { ?>
		<h1><?php echo esc_attr( get_the_author() ); ?></h1>
	<?php } else { ?>
		<h4>About <?php echo esc_attr( get_the_author() ); ?><span><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>" rel="author" title="See all posts by <?php the_author_meta('display_name'); ?>">More by this author</a></span></h4>
	<?php } ?>

	<?php if (has_gravatar( get_the_author_meta('user_email') ) ) :
			echo get_avatar( get_the_author_meta('ID'), 96 );
		endif;
	?>

	<?php if ( get_the_author_meta( 'description' ) ) : ?>
		<p><?php the_author_meta( 'description' ); ?></p>
	<?php endif; ?>

	<ul class="social-links">
		<?php if ( get_the_author_meta( 'user_email' ) ) : ?>
		<li class="email">
			<a href="mailto:<?php echo esc_attr( get_the_author_meta( 'user_email' ) ); ?>" title="e-mail <?php echo esc_attr( get_the_author() ); ?>"><i class="icon-envelope icon-white"></i> email</a>
		</li>
		<?php endif; ?>

		<?php if ( get_the_author_meta( 'fb' ) ) : ?>
		<li class="facebook">
			<div class="fb-subscribe" data-href="<?php echo esc_url( get_the_author_meta( 'fb' ) ); ?>" data-layout="button_count" data-show-faces="false" data-width="225"></div>
		</li>
		<?php endif; ?>

		<?php if ( get_the_author_meta( 'twitter' ) ) : ?>
		<li class="twitter">
			<a href="<?php echo esc_url( get_the_author_meta( 'twitter' ) ); ?>" class="twitter-follow-button" data-show-count="false" data-lang="en">Follow @twitterapi</a>
		</li>
		<?php endif; ?>

		<?php if ( get_the_author_meta( 'googleplus' ) ) : ?>
		<li class="gplus">
			<a href="<?php echo esc_url( get_the_author_meta( 'googleplus' ) ); ?>" title="<?php echo esc_attr( get_the_author() ); ?> on Google+" rel="me"><img src="<?php bloginfo( 'template_directory' ); ?>/img/gplus-19.png" alt="Google+" /></a>
		</li>
		<?php endif; ?>
	</ul>

</div>