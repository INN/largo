<?php
	add_action( 'wp_head', 'largo_opengraph' );

	function twitter_url_to_username ($url) {
		$urlParts = explode("/", $url);
		$username = '@' . $urlParts[3];
		return $username;
	}

	function largo_opengraph() {

		//get the current url
		$current_url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

		//set a default thumbnail, if a post has a featured image use that instead
		$thumbnailURL = of_get_option( 'logo_thumbnail_sq' );

		if ( is_single() ) :
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' );

			if ($image != '') :
				$thumbnailURL = $image[0];
			endif;

		endif; // is_single ?>

		<?php // start the output, some attributes will be the same for all page types ?>

			<meta name="twitter:card" content="summary">
		<?php if ( of_get_option( 'twitter_link' ) ) : ?>
			<meta name="twitter:site" content="<?php echo twitter_url_to_username( of_get_option( 'twitter_link' ) ); ?>">
		<?php endif; ?>

		<?php //output appropriate OG tags by page type
		if ( is_single() ) {

			if ( have_posts() ) : the_post(); // we need to queue up the post to get the post specific info

				if ( get_the_author_meta( 'twitter' ) ) : ?>
					<meta name="twitter:creator" content="<?php echo twitter_url_to_username( get_the_author_meta( 'twitter' ) ); ?>">
				<?php endif; ?>

				<meta property="og:title" content="<?php the_title(); ?>" />
				<meta property="og:type" content="article" />
				<meta property="og:url" content="<?php the_permalink(); ?>"/>
				<meta property="og:description" content="<?php echo strip_tags(get_the_excerpt()); ?>" />
			<?php endif; // have_posts

			rewind_posts();

		} elseif ( is_home() ) { ?>

			<meta property="og:title" content="<?php bloginfo('name'); echo ' - '; bloginfo('description'); ?>" />
			<meta property="og:type" content="website" />
			<meta property="og:url" content="<?php bloginfo('url'); ?>"/>
			<meta property="og:description" content="<?php bloginfo('description'); ?>" />

		<?php } else { ?>

			<meta property="og:title" content="<?php bloginfo('name'); wp_title(); ?>" />
			<meta property="og:type" content="article" />
			<meta property="og:url" content="<?php echo $current_url; ?>"/>
			<?php
				//let's try to get a better description when available
				$description = get_bloginfo('description');

				if ( is_category() && category_description() ) {
					$description = category_description();
				} elseif ( is_author() ) {
					if ( have_posts() ) : the_post(); // we need to queue up the post to get the post specific info
						if ( get_the_author_meta( 'description' ) )
							$description = get_the_author_meta( 'description' );
					endif;
					rewind_posts();
				}
			?>
			<meta property="og:description" content="<?php echo strip_tags($description); ?>" />

		<?php } ?>

		<?php // a few more attributes that are common to all page types ?>
			<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
			<meta property="og:image" content="<?php echo $thumbnailURL; ?>" />

		<?php if ( of_get_option( 'gplus_link' ) ) : // don't forget about Google+ ?>
			<link href="<?php echo esc_url( of_get_option( 'gplus_link' ) ); ?>" rel="publisher" />
		<?php endif;

	};
?>