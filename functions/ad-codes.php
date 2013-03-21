<?php
/**
 * Ad Codes configuration for use with Ad Code Manager plugin
 */

// Need to allow script tags in elements for the ad_html option
// This is a potential security risk, but it's pretty slight
global $allowedposttags;
$allowedposttags['script'] = array(
	'type' => array(),
	'src' => array(),
	'language' => array()
);
$allowedposttags['noscript'] = array();

// Any ad network URLs must be whitelisted here first.
function largo_acm_whiltelisted_script_urls( $whitelisted_urls ) {
	$safe_domains = of_get_option('ad_urls');
	$whitelisted_urls = array();
	foreach( $safe_domains as $domain => $active ) {
		if ($active) array_push($whitelisted_urls, $domain);
	}
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
	$output_tokens['%site_name%'] = of_get_option('ad_site_name', get_bloginfo('name'));
	return $output_tokens;
}
// The low priority will not overwrite what's set up. Higher values will.
add_filter('acm_output_tokens', 'largo_acm_output_tokens', 5, 3 );


// Add actual ad tags
function largo_ad_tags_ids( $ad_tag_ids ) {
	return array(
		array(
			'tag' => 'banner',
			'url_vars' => array(
				'type' => 'banner',
				'size' => '728x90',
				'bgcolor' => '666666',
				'fgcolor' => '00ff00',
			),
		),
		array(
			'tag' => 'mobile',
			'url_vars' => array(
				'type' => 'mobile',
				'size' => '300x50',
				'bgcolor' => '9999ff',
				'fgcolor' => '333333',
			),
		),
		array(
			'tag' => 'widget',
			'url_vars' => array(
				'type' => 'rect',
				'size' => '300x250',
				'bgcolor' => '443322',
				'fgcolor' => 'ffeedd',
			),
		),
	);
}
add_filter( 'acm_ad_tag_ids', 'largo_ad_tags_ids' );


function largo_acm_output_html( $output_html, $tag_id ) {
	return of_get_option('ad_html');
}
add_filter( 'acm_output_html','largo_acm_output_html', 5, 2 );
add_filter( 'acm_display_ad_codes_without_conditionals', '__return_true' );

/**
 * Register a new sidebar region for ads to appear in the header
 */
function largo_add_header_sidebar() {
	if ( function_exists('register_sidebar') ) {
		register_sidebars( 1,
			array(
				'name' => 'Header',
				'before_widget' => '<div id="%1$s" class="%2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h2 class="widgettitle">',
				'after_title' => '</h2>
				'
			)
		);
	}
}
add_action('widgets_init', 'largo_add_header_sidebar');