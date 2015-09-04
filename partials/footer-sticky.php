<?php if( is_singular( 'post' ) ): ?>
<div class="sticky-footer-holder">
	<div class="sticky-footer-container social-icons">

		<?php // Share the post using the ShareThis API. The class *_custom gives us a blank slate.

		if ( of_get_option('single_social_icons_footer' ) == '1' ) {
			$utilities = of_get_option('footer_utilities');
			echo '<div class="share">';
			echo '<h4>' . __('Share', 'largo') . '</h4>';
				if ($utilities['ffacebook']) { ?>
					<span data-service="facebook" class="custom-share-button icon-facebook share-button"></span>
				<?php }
				if ($utilities['ftwitter']) { ?>
					<span data-service="twitter" class="custom-share-button icon-twitter share-button"></span>
				<?php }
				if ($utilities['femail']) { ?>
					<span data-service="email" class="custom-share-button icon-mail share-button"></span>
				<?php }
			/*
			<span data-service="googleplus" class="custom-share-button icon-gplus share-button"></span>
			<span data-service="linkedin" class="custom-share-button icon-linkedin share-button"></span>
			*/
			echo '</div>';
		}
		?>

		<?php // Comment link ?>
		<?php if ( comments_open() ): ?>
		<div class="comments">
			<a href="<?php comments_link(); ?>"><h4><?php _e( 'Comment', 'largo' ); ?></h4> <i class="icon-comment"></i></a>
		</div>
		<?php endif; ?>

		<div class="dismiss">
			<a href="#">
			<i class="icon-cancel"></i>
			</a>
		</div>

		<?php // The category RSS and author follow links ?>
		<?php
		// Get the category object
		$cat_feed_link = largo_top_term( array( 'use_icon' => 'rss', 'rss' => true, 'echo' => false ) );

		// Set one of two author objects (leave other null).
		// We make $coauthor a one dimensional array if author hasn't been overwritten.
		$coauthors = null;
		if( get_post_meta( $post->ID, 'largo_byline_text' ) ) {
			$byline_text = esc_attr( get_post_meta( $post->ID, 'largo_byline_text', true ) );
			$byline_link = esc_attr( get_post_meta( $wp_query->post->ID, 'largo_byline_link', true ) );
		} else if ( function_exists('get_coauthors') ) {
			$coauthors =  get_coauthors( $post->ID );
		} else {
			$coauthors = array( get_userdata( $post->post_author ));
		}

		?>
		<div class="follow">
			<h4><?php _e( 'Follow', 'largo' ); ?></h4>

			<?php
				if ( $cat_feed_link ) {
					echo $cat_feed_link;
			} ?>

			<?php if ( !empty( $byline_text ) && !empty( $byline_link ) ) { ?>
				<a href="<?php echo $byline_link; ?>" class="icon-link"><?php echo esc_html( $byline_text ); ?></a>
			<?php } else if ( !empty( $coauthors )) { ?>
				<?php // $coauthor covers base case (1 dimensional) or where coauthors were defined. ?>
				<?php foreach( $coauthors as $author ) : ?>
					<div class="follow-author">
					<a href="<?php echo get_author_feed_link( $author->ID, '' ); ?>" class="icon-rss"></a>
					<?php if ( $twitter = get_the_author_meta( 'twitter', $author->ID ) ) : ?>
						<a href="https://twitter.com/<?php echo largo_twitter_url_to_username( esc_url( $twitter ) ); ?>" class="icon-twitter"></a>
					<?php endif; ?>
					<a href="<?php echo get_author_posts_url( $author->ID, $author->user_nicename ); ?>"><?php echo esc_html( $author->display_name ); ?></a>
					</div>
				<?php endforeach; ?>
			<?php } ?>

		</div>

	</div>
</div>
<?php endif; ?>
