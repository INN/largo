<?php

/**
 * Returns a Twitter username (without the @ symbol)
 *
 * @param 	string 	$url a twitter url
 * @return 	string	the twitter username extracted from the input string
 * @since 1.0
 */
function largo_twitter_url_to_username ($url) {
	$urlParts = explode("/", $url);
	$username = $urlParts[3];
	return $username;
}

/**
 * Adds appropriate open graph, twittercards, and google publisher tags
 * to the header based on the page type displayed
 *
 * @uses largo_twitter_url_to_username()
 * @uses global $current_url
 * @since 1.0
 */
if ( ! function_exists( 'largo_opengraph' ) ) {
	function largo_opengraph() {

		global $current_url, $post;

		// set a default thumbnail, if a post has a featured image use that instead
		if ( is_single() && has_post_thumbnail( $post->ID ) ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			$thumbnailURL = $image[0];
		} else if ( of_get_option( 'logo_thumbnail_sq' ) ) {
			$thumbnailURL = of_get_option( 'logo_thumbnail_sq' );
		} else {
			$thumbnailURL = false;
		}

		// start the output, some attributes will be the same for all page types ?>

		<meta name="twitter:card" content="summary">

		<?php
			if ( of_get_option( 'twitter_link' ) )
				echo '<meta name="twitter:site" content="@' . largo_twitter_url_to_username( of_get_option( 'twitter_link' ) ) . '">';
		?>

		<?php // output appropriate OG tags by page type
			if ( is_single() ) {
				if ( have_posts() ) {
					the_post(); // we need to queue up the post to get the post specific info
					if ( get_the_author_meta( 'twitter' ) )
						echo '<meta name="twitter:creator" content="@' . largo_twitter_url_to_username( get_the_author_meta( 'twitter' ) ) . '">';
					?>
					<meta property="og:title" content="<?php the_title(); ?>" />
					<meta property="og:type" content="article" />
					<meta property="og:url" content="<?php the_permalink(); ?>"/>
					<meta property="og:description" content="<?php echo strip_tags( get_the_excerpt() ); ?>" />
			<?php
				} // have_posts

				rewind_posts();

			} elseif ( is_home() ) { ?>

				<meta property="og:title" content="<?php bloginfo( 'name' ); echo ' - '; bloginfo('description'); ?>" />
				<meta property="og:type" content="website" />
				<meta property="og:url" content="<?php echo home_url(); ?>"/>
				<meta property="og:description" content="<?php bloginfo( 'description' ); ?>" />

		<?php
			} else {
		?>
				<meta property="og:title" content="<?php bloginfo( 'name' ); wp_title(); ?>" />
				<meta property="og:type" content="article" />
				<meta property="og:url" content="<?php echo esc_url( $current_url ); ?>"/>
			<?php
				//let's try to get a better description when available
				if ( is_category() && category_description() ) {
					$description = category_description();
				} elseif ( is_author() ) {
					if ( have_posts() ) {
						the_post(); // we need to queue up the post to get the post specific info
						if ( get_the_author_meta( 'description' ) )
							$description = get_the_author_meta( 'description' );
					}
					rewind_posts();
				} else {
					$description = get_bloginfo( 'description' );
				}
				if ( $description ) {
					echo '<meta property="og:description" content="' . strip_tags( $description ) . '" />';
				}
			} // else

			// a few more attributes that are common to all page types
			echo '<meta property="og:site_name" content="'  . get_bloginfo() . '" />';

			// thumbnail url
			if ( $thumbnailURL )
				echo '<meta property="og:image" content="' . esc_url( $thumbnailURL ) . '" />';

			// google author/publisher markup
			// see: https://support.google.com/webmasters/answer/1408986
			if ( of_get_option( 'gplus_link' ) )
				echo '<link href="' . esc_url( of_get_option( 'gplus_link' ) ) . '" rel="publisher" />';
	}
}
add_action( 'wp_head', 'largo_opengraph' );