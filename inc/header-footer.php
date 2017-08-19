<?php

/**
 * DEPRECATED: Check the constant SHOW_STICKY_NAV.
 *
 * Whether or not the sticky nav is displayed is determined by the javascript in js/navigation.js, since Largo 0.5.4.
 *
 * @return Bool
 * @deprecated the constant SHOW_STICKY_NAV is deprecated
 * @link https://github.com/INN/Largo/issues/1135
 * @since 0.5.5
 */
function largo_sticky_nav_active() {
	if ( SHOW_STICKY_NAV ) {
		return true;
	}
	return false;
}

/**
 * Output the site header image or the text-only header
 *
 * @since 0.1
 */
if ( ! function_exists( 'largo_header' ) ) {
	function largo_header() {
		$header_tag = is_home() ? 'h1' : 'h2'; // use h1 for the homepage, h2 for internal pages

		// if we're using the text only header, display the output, otherwise this is just replacement text for the banner image
		$header_class = of_get_option( 'no_header_image' ) ? 'branding' : 'visuallyhidden';
		$divider = $header_class == 'branding' ? '' : ' - ';

		// print the text-only version of the site title
		printf( '
				<%1$s class="%2$s">
					<a itemprop="url" href="%3$s">
						<span class="sitename" itemprop="name">%4$s</span>
						<span class="tagline" itemprop="description">%5$s%6$s</span>
					</a>
				</%1$s>
			',
			$header_tag,
			$header_class,
			esc_url( home_url( '/' ) ),
			esc_attr( get_bloginfo('name') ),
			$divider,
			esc_attr( get_bloginfo('description') )
		);

		// add an image placeholder, the src is added by largo_header_js() in inc/enqueue.php
		if ($header_class != 'branding') {
			echo '<a itemprop="url" href="' . esc_url( home_url( '/' ) ) . '"><img class="header_img" src="" alt="" /></a>';
		}

		if ( of_get_option( 'logo_thumbnail_sq' ) )
			echo '<meta itemprop="logo" content="' . esc_url( of_get_option( 'logo_thumbnail_sq' ) ) . '"/>';
	}
}

/**
 * Print the copyright message in the footer
 *
 * @since 0.3
 */
if ( ! function_exists( 'largo_copyright_message' ) ) {
	function largo_copyright_message() {
		$msg = of_get_option( 'copyright_msg' );
		if ( ! $msg )
			$msg = __( 'Copyright %s', 'largo' );
		printf( $msg, date( 'Y' ) );
	}
}

/**
 * Outputs a list of social media links (as icons) from theme options
 *
 * @since 0.3
 */
if ( ! function_exists( 'largo_social_links' ) ) {
	function largo_social_links() {

		$networks = array(
			'facebook' 	=> __( 'Link to Facebook Profile', 'largo' ),
			'twitter' 	=> __( 'Link to Twitter Page', 'largo' ),
			'gplus' 	=> __( 'Link to Google Plus Page', 'largo' ),
			'youtube' 	=> __( 'Link to YouTube Page', 'largo' ),
			'instagram' => __( 'Link to Instagram Page', 'largo' ),
			'tumblr' 	=> __( 'Link to Tumblr', 'largo' ),
			'linkedin' 	=> __( 'Link to LinkedIn Page', 'largo' ),
			'pinterest' => __( 'Link to Pinterest Page', 'largo' ),
			'github' 	=> __( 'Link to Github Page', 'largo' ),
			'flickr' 	=> __( 'Link to Flickr Page', 'largo' ),
			'rss' 		=> __( 'Link to RSS Feed', 'largo' )
		);
		$networks = apply_filters( 'largo_additional_networks', $networks );

		foreach ( $networks as $field => $title ) {
			$field_link =  $field . '_link';

			if ( of_get_option( $field_link ) ) {
				echo '<li><a href="' . esc_url( of_get_option( $field_link ) ) . '" title="' . esc_attr( $title ) . '"><i class="icon-' . esc_attr( $field ) . '"></i></a></li>';
			}
		}
	}
}

/**
 * Adds shortcut icons to the header
 *
 * @since 0.3
 */
if ( ! function_exists( 'largo_shortcut_icons' ) ) {
	function largo_shortcut_icons() {
		if ( of_get_option( 'logo_thumbnail_sq' ) )
			echo '<link rel="apple-touch-icon" href="' . esc_url( of_get_option( 'logo_thumbnail_sq' ) ) . '"/>';
		if ( of_get_option( 'favicon' ) )
			echo '<link rel="shortcut icon" href="' . esc_url( of_get_option( 'favicon' ) ) . '"/>';
	}
}
add_action( 'wp_head', 'largo_shortcut_icons' );

/**
 * Various meta tags to help Google crawl our sites more easily
 *
 * @since 0.3
 */
if ( ! function_exists ( 'largo_seo' ) ) {
	function largo_seo() {

		// noindex for date archives (and optionally on all archive pages)
		// if the blog is set to private wordpress already adds noindex,nofollow
		if ( get_option( 'blog_public') ) {
			if ( is_date() || ( is_archive() &&  of_get_option( 'noindex_archives' ) ) ) {
				echo '<meta name="robots" content="noindex,follow" />';
			}
		}
		// single posts get a bunch of other google news specific meta tags
		if ( is_single() ) {
			if ( have_posts() ) : the_post();
				$permalink = get_permalink();

				// use categories and tags for the news_keywords meta tag
				// up to 10 terms per Google guidelines:
				// https://support.google.com/news/publisher/answer/68297
				if ( largo_has_categories_or_tags() ) {
					echo '<meta name="news_keywords" content="';
						largo_categories_and_tags( 10, true, false, false, ', ' );
					echo '">';
				}

				// set the original-source meta tag
				// see: http://googlenewsblog.blogspot.com/2010/11/credit-where-credit-is-due.html
				echo '<meta name="original-source" content="'. esc_url( $permalink ) .'" />';

				// check for the existence of a custom field 'permalink'
				// if it doesn't exist we'll just use the current url as the syndication source
				if ( get_post_meta( get_the_ID(), 'permalink', true ) ) {
				 	echo '<meta name="syndication-source" content="' . get_post_meta( get_the_ID(), 'permalink', true ) . '" />';
				} else {
					echo '<meta name="syndication-source" content="' . esc_url( $permalink ) . '" />';
				}

				// add the standout metatag if this post is flagged with any of the terms in the prominence taxonomy
				// see: https://support.google.com/news/publisher/answer/191283
				if ( has_term( get_terms( 'prominence', array( 'fields' => 'names' ) ), 'prominence' ) ) {
					echo '<meta name="standout" content="' . esc_url( $permalink ) . '"/>';
				}

			endif;
		}
		rewind_posts();
	}
}
add_action( 'wp_head', 'largo_seo' );

/**
 * Schema.org article metadata we include in the header of each single post
 *
 * @since 0.4
 */
if ( ! function_exists( 'largo_post_metadata' ) ) {
	function largo_post_metadata( $post_id, $echo = TRUE ) {
		$out = '<meta itemprop="description" content="' . strip_tags( largo_excerpt( get_post( $post_id ), 5, false, '', false ) ) . '" />' . "\n";
		$out .= '<meta itemprop="datePublished" content="' . get_the_date( 'c', $post_id ) . '" />' . "\n";
		$out .= '<meta itemprop="dateModified" content="' . get_the_modified_date( 'c', $post_id ) . '" />' . "\n";

		if ( has_post_thumbnail( $post_id ) ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'thumbnail' );
			$out .= '<meta itemprop="image" content="' . $image[0] . '" />';
		}

		if ( $echo ) {
			echo $out;
		} else {
			return $out;
		}
	}
}

/**
 * Filter the <title> in the header
 *
 */
if( ! function_exists( 'largo_wp_title' ) ) {
	function largo_wp_title( ) {

		global $page, $paged;
//		wp_title( '|', true, 'right' );
		bloginfo( 'name' ); // Add the blog name.

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			echo " | $site_description";

		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			echo ' | ' . 'Page ' . max( $paged, $page );

	}
}
add_filter( 'wp_title', 'largo_wp_title' );


/**
 * Remove extraneous <head> elements
 *
 * @since 0.3
 */
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_generator' );
