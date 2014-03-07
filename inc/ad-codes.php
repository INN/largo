<?php
/**
 * Ad Codes configuration for use with Ad Code Manager plugin
 * http://wordpress.org/extend/plugins/ad-code-manager/
 */

// Need to allow script tags in elements for the ad_html option
// This is a potential security risk, but it's pretty slight
/*
global $allowedposttags;
$allowedposttags['script'] = array(
	'type' => array(),
	'src' => array(),
	'language' => array()
);
$allowedposttags['noscript'] = array();
*/

// Any ad network URLs must be whitelisted here first. This may be superfluous as ACM includes the major ones.
function largo_acm_whiltelisted_script_urls( $whitelisted_urls ) {
	$whitelisted_urls = array($_SERVER['HTTP_HOST']);
	return $whitelisted_urls;
}
add_filter( 'acm_whitelisted_script_urls', 'largo_acm_whiltelisted_script_urls');

/* Set a default URL if %url% is used? (currently placeholder)
 * Example in Gist: https://gist.github.com/1631131
 */
function largo_acm_default_url( $url ) {
	 if ( 0 === strlen( $url )  ) {
		return "http://ad.doubleclick.net/adj/%site_name%/%zone1%;s1=%zone1%;s2=;pid=%permalink%;fold=%fold%;kw=;test=%test%;ltv=ad;pos=%pos%;dcopt=%dcopt%;tile=%tile%;sz=%size%;";
	}
}
//add_filter( 'acm_default_url', 'largo_acm_default_url' ) ;

// Add additional output tokens
function largo_acm_output_tokens( $output_tokens, $tag_id, $code_to_display ) {
	// This is a quick example to show how to assign an output token to any value. Things like the zone1 value can be used to compute.
	$output_tokens['%rand%'] = rand(1,100);
	return $output_tokens;
}
// The low priority will not overwrite what's set up. Higher values will.
// add_filter('acm_output_tokens', 'largo_acm_output_tokens', 5, 3 );


// Add actual ad tags
if ( ! function_exists( 'largo_ad_tags_ids' ) ) {
	function largo_ad_tags_ids( $ad_tag_ids ) {
		return array(
				array(
						'tag'       => 'leaderboard',
						'url_vars'  => array(
								'tag'       => '728x90',
								'sz'        => '728x90',
								'height'    => '90',
								'width'     => '728',
							),
						'enable_ui_mapping' => true,
					),
				array(
						'tag'       => 'sidebar 300x250',
						'url_vars'  => array(
								'tag'       => '300x250',
								'sz'        => '300x250',
								'height'    => '250',
								'width'     => '300',
							),
						'enable_ui_mapping' => true,
					),
				array(
						'tag'       => 'mobile banner',
						'url_vars'  => array(
								'tag'       => '300x50',
								'sz'        => '300x50',
								'height'    => '50',
								'width'     => '300',
							),
						'enable_ui_mapping' => true,
					),

		);
	}
}
add_filter( 'acm_ad_tag_ids', 'largo_ad_tags_ids' );


function largo_acm_output_html( $output_html, $tag_id ) {
	return '<img src="http://placehold.it/%size%/%bgcolor%/%fgcolor%&text=(%zone1%)+' . urlencode($tag_id) . '+%site_name%">';
	//return of_get_option('ad_html');
}
//add_filter( 'acm_output_html','largo_acm_output_html', 5, 2 );
add_filter( 'acm_display_ad_codes_without_conditionals', '__return_true' );