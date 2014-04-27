<?php if( is_single() ): ?>
<div class="sticky-footer-holder">
	<div class="sticky-footer-container social-icons">

		<?php // Share the post using the ShareThis API. The class *_custom gives us a blank slate. ?>
		<div class="share">
			<h4><?php _e('Share', 'largo'); ?></h4>
			<span data-service="facebook" class="custom-share-button icon-facebook share-button"></span>
			<span data-service="twitter" class="custom-share-button icon-twitter share-button"></span>
			<span data-service="email" class="custom-share-button icon-mail share-button"></span>
			<?php
			/*
			<span data-service="googleplus" class="custom-share-button icon-gplus share-button"></span>
			<span data-service="linkedin" class="custom-share-button icon-linkedin share-button"></span>
			*/ ?>
		</div>

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

		// Get the author object
		$author = get_userdata( get_the_author_meta( 'ID' ) );
		$byline_text = get_post_meta( $post->ID, 'largo_byline_text' ) ? esc_attr( get_post_meta( $wp_query->post->ID, 'largo_byline_text', true ) ) : '';
		// If the byline override is being used, then don't use it
		if ( !empty($byline_text) ) {
			$author = null;
		}

		if ( !empty($author) || !empty($cat_feed_link) ): ?>
		<div class="follow">
			<h4><?php _e( 'Follow', 'largo' ); ?></h4>

			<?php
				if ( $cat_feed_link ) {
					echo $cat_feed_link;
			} ?>

				<?php if ( $author ): ?>
				<div class="follow-author">
					<a href="<?php echo get_author_feed_link($author->ID); ?>" class="icon-rss"></a>
					<?php if ( $twitter = get_the_author_meta( 'twitter', $author->ID ) ) : ?>
						<a href="<?php echo esc_url( $twitter ); ?>" class="icon-twitter"></a>
					<?php endif; ?>

					<a href="<?php echo get_author_posts_url( $author->ID ); ?>"><?php echo esc_html( $author->display_name ); ?></a>
				</div>
				<?php endif; ?>
		</div>
		<?php endif; ?>

	</div>
</div>
<?php endif; ?>