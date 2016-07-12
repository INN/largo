<?php
/**
 * This file contains largo functions related to social media buttons on posts.
 */


/**
 * Outputs facebook, twitter and print utility links on article pages
 *
 * The Twitter 'via' attribute output is set in the following order
 *
 * - The single coauthor's twitter handle, if it is set
 * - The site's twitter handle, if there are multiple coauthors and a site twitter handle
 * - The single user's twitter handle, if it is set
 * - The site's twitter handle, if it is set and if there is a custom byline
 * - The site's twitter handle, if it is set
 * - No 'via' attribute if no twitter handles are set or if there are multiple coauthors but no site twitter handle
 *
 * @param $echo bool echo the string or return it (default: echo)
 * @return string social icon area markup as formatted html
 * @since 0.3
 * @todo maybe let people re-arrange the order of the links or have more control over how they appear
 * @link https://github.com/INN/Largo/issues/1088
 */
if ( ! function_exists( 'largo_post_social_links' ) ) {
	function largo_post_social_links( $echo = true ) {
		global $post, $wpdb;

		$utilities = of_get_option( 'article_utilities' );

		$output = '<div class="largo-follow post-social clearfix">';

		$values = get_post_custom( $post->ID );

		if ( $utilities['facebook'] === '1' ) {
			$fb_share = '<span class="facebook"><a target="_blank" href="http://www.facebook.com/sharer/sharer.php?u=%1$s"><i class="icon-facebook"></i><span class="hidden-phone">%2$s</span></a></span>';
			$output .= sprintf(
				$fb_share,
				rawurlencode( get_permalink() ),
				__( ucfirst( of_get_option( 'fb_verb' ) ), 'largo' )
			);
		}

		if ( $utilities['twitter'] === '1' ) {
			$twitter_share = '<span class="twitter"><a target="_blank" href="https://twitter.com/intent/tweet?text=%1$s&url=%2$s%3$s"><i class="icon-twitter"></i><span class="hidden-phone">%4$s</span></a></span>';

			// By default, don't set a via.
			$via = '';

			// If there are coauthors, use a coauthor twitter handle, otherwise use the normal author twitter handle
			// If there is a custom byline, don't try to use the author byline.
			if ( function_exists( 'coauthors_posts_links' ) && !isset( $values['largo_byline_text'] ) ) {
				$coauthors = get_coauthors( $post->ID );
				$author_twitters = array();
				foreach ( $coauthors as $author ) {
					if ( isset( $author->twitter ) ) {
						$author_twitters[] = $author->twitter;
					}
				}
				if ( count( $author_twitters ) == 1 && !empty($author_twitters[0]) ) {
					$via = '&via=' . rawurlencode( largo_twitter_url_to_username( $author_twitters[0] ) );
				}
				// in the event that there are more than one author twitter accounts, we fall back to the org account
				// @link https://github.com/INN/Largo/issues/1088
			} else if ( empty($via) && !isset( $values['largo_byline_text'] ) ) {
				$user =  get_the_author_meta( 'twitter' );
				if ( !empty( $user ) ) {
					$via = '&via=' . rawurlencode( largo_twitter_url_to_username( $user ) );
				}
			}

			// Use the site Twitter handle if that exists and there isn't yet a via
			if ( empty( $via ) ) {
				$site = of_get_option( 'twitter_link' );
				if ( !empty( $site ) ) {
					$via = '&via=' . rawurlencode( largo_twitter_url_to_username( $site ) ) ;
				}
			}

			$output .= sprintf(
				$twitter_share,
				// Yes, rawurlencode. Otherwise, the link will break. Use html_entity_decode to handle wordpress saving smart quotes as &#1234; entities.
				rawurlencode( html_entity_decode( get_the_title(), ENT_QUOTES, "UTF-8" ) ),
				rawurlencode( get_permalink() ),
				$via,
				__( 'Tweet', 'largo' )
			);
		}
		
		if ( $utilities['email'] === '1' ) {
			$output .= '<span data-service="email" class="email custom-share-button share-button"><a><i class="icon-mail"></i> <span class="hidden-phone">' . esc_attr( __( 'Email', 'largo' ) ) . '</span></a></span>';
		}

		
		if ( $utilities['print'] === '1' ) {
			$output .= '<span class="print"><a href="#" onclick="window.print()" title="' . esc_attr( __( 'Print this article', 'largo' ) ) . '" rel="nofollow"><i class="icon-print"></i><span class="hidden-phone">' . esc_attr( __( 'Print', 'largo' ) ) . '</span></a></span>';
		}

		
		// More social links
		$more_social_links = array();

		// Try to get the top term permalink and RSS feed
		$top_term_id = get_post_meta( $post->ID, 'top_term', TRUE );
		$top_term_taxonomy = $wpdb->get_var(
			$wpdb->prepare( "SELECT taxonomy FROM $wpdb->term_taxonomy WHERE term_id = %d LIMIT 1", $top_term_id )
		);

		if ( empty( $top_term_id ) || empty( $top_term_taxonomy ) ) {
			$top_term_id = get_the_category( $post );
			if ( is_array( $top_term_id ) && count( $top_term_id ) ) {
				$top_term_taxonomy = 'category';
				$top_term_id = $top_term_id[0]->term_id;
			}
		}

		if ( ! empty( $top_term_id ) ) {
			$top_term = get_term( (int) $top_term_id, $top_term_taxonomy );
			$top_term_link = get_term_link( (int) $top_term_id, $top_term_taxonomy );
			if ( ! is_wp_error( $top_term_link ) ) {
				$more_social_links[] = '<li><a href="' . $top_term_link . '"><i class="icon-link"></i> <span>' . __( 'More on ', 'largo' ) . $top_term->name . '</span></a></li>';
			}

			$top_term_feed_link = get_term_feed_link( $top_term_id, $top_term_taxonomy );
			if ( ! is_wp_error( $top_term_feed_link ) ) {
				$more_social_links[] = '<li><a href="' . $top_term_feed_link . '"><i class="icon-rss"></i> <span>' . __( 'Subscribe to ', 'largo' ) . $top_term->name . '</span></a></li>';
			}
		}

		// Try to get the author's Twitter link
		// Commented out until we get a better grasp of coauthors
		// Don't do this if we have a custom byline text
		if ( ! function_exists('get_coauthors') && !isset( $values['largo_byline_text'] ) ) {
			$twitter_username = get_user_meta( $post->post_author, 'twitter', true );
			if ( ! empty( $twitter_username ) ) {
				$twitter_link = 'https://twitter.com/' . $twitter_username;
				$more_social_links[] = '<li><a href="' . $twitter_link . '"><i class="icon-twitter"></i> <span>' . __( 'Follow this author', 'largo' ) . '</span></a></li>';
			}
		}

		/**
		 * Filter the array of More Social Links, which are added to the "More" menu output by largo_post_social_links
		 *
		 * @filter
		 * @param Array $more_social_links
		 * @since 0.5.5
		 * @link https://github.com/INN/Largo/issues/219
		 */
		$more_social_links = apply_filters( 'largo_post_social_more_social_links', $more_social_links );

		if ( count( $more_social_links ) ) {
			$more_social_links_str = implode( $more_social_links, "\n" );
			$more = __( 'More', 'largo' );
			
			$output .= <<<EOD
<span class="more-social-links">
	<a class="popover-toggle" href="#"><i class="icon-plus"></i><span class="hidden-phone">${more}</span></a>
	<span class="popover">
	<ul>
		${more_social_links_str}
	</ul>
	</span>
</span>
EOD;
		}

		$output .= '</div>';

		/**
		 * Filter the output text of largo_post_social_links() after the closing </div> but before it is echoed or returned.
		 *
		 * @since 0.5.3
		 * @param string $output A div containing a number of spans containing social links and other utilities.
		 */
		$output = apply_filters( 'largo_post_social_links', $output );

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
}

/**
 * New floating social buttons
 *
 * Only displayed if the floating share icons option is checked.
 * Formerly only displayed if the post template was the single-column template.
 *
 * @since 0.5.4
 * @link https://github.com/INN/Largo/issues/961
 * @link http://jira.inn.org/browse/VO-10
 * @see largo_floating_social_button_width_json
 */
if ( ! function_exists( 'largo_floating_social_buttons' ) ) {
	function largo_floating_social_buttons() {
		if ( is_single() && of_get_option('single_floating_social_icons', '1') == '1' ) {
			echo '<script type="text/template" id="tmpl-floating-social-buttons">';
			largo_post_social_links();
			echo '</script>';
		}
	}
}
add_action('wp_footer', 'largo_floating_social_buttons');

/**
 * Responsive viewport information for the floating social buttons, in the form of JSON in a script tag, and the relevant javascript.
 *
 * @since 0.5.4
 * @see largo_floating_social_buttons
 * @see largo_floating_social_button_js
 */
if ( ! function_exists('largo_floating_social_button_width_json') ) {
	function largo_floating_social_button_width_json() {
		if ( is_single() && of_get_option('single_floating_social_icons', '1') == '1' ) {
			$template = get_post_template(null);

			if ( is_null( $template ) )
				$template = of_get_option( 'single_template' );

			$is_single_column = (bool) strstr( $template, 'single-one-column' ) || $template == 'normal' || is_null( $template );

			if ( $is_single_column ) {
				$config = array(
					'min' => '980',
					'max' => '9999',
				);
			} else {
				$config = array(
					'min' => '1400',
					'max' => '9999',
				);
			}

			$config = apply_filters( 'largo_floating_social_button_width_json', $config );
			?>
			<script type="text/javascript" id="floating-social-buttons-width-json">
				window.floating_social_buttons_width = <?php echo json_encode( $config ); ?>
			</script>
			<?php
		}
	}
}
add_action('wp_footer', 'largo_floating_social_button_width_json');

/**
 * Enqueue floating social button javascript
 *
 * @since 0.5.4
 * @see largo_floating_social_buttons
 * @see largo_floating_social_button_width_json
 * @global LARGO_DEBUG
 */
if ( ! function_exists('largo_floating_social_button_js') ) {
	function largo_floating_social_button_js() {
		if ( is_single() && of_get_option('single_floating_social_icons', '1') == '1' ) {
			?>
			<script type="text/javascript" src="<?php
				$suffix = (LARGO_DEBUG)? '' : '.min';
				$version = largo_version();
				echo get_template_directory_uri() . '/js/floating-social-buttons' . $suffix . '.js?ver=' . $version;
			?>" async></script>
			<?php
		}
	}
}
add_action('wp_footer', 'largo_floating_social_button_js');
