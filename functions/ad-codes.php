<?php 
/**
 * Ad Codes congifuration for use with Ad Code Manager plugin
 */


// Any ad network URLs must be whitelisted here first.
function largo_acm_whiltelisted_script_urls( $whitelisted_urls ) {
	$whitelisted_urls = array( 
		//'ad.doubleclick.net', 
		'placehold.it', // Placeholder graphics 
	);
	return $whitelisted_urls;
}
add_filter( 'acm_whitelisted_script_urls', 'largo_acm_whiltelisted_script_urls');


// Set default URL (currently placeholder)
function largo_acm_default_url( $url ) {
	/*
	 * This is being left as a placeholder for future use.
	 * 
	 * Example in Gist: https://gist.github.com/1631131
	 */
}
//add_filter( 'acm_default_url', 'largo_acm_default_url' ) ;


// Add additional output tokens
function largo_acm_output_tokens( $output_tokens, $tag_id, $code_to_display ) {
	// This is a quick exampel to show how to assign an output token to any value. Things like the zone1 value can be used to compute.
	$output_tokens['%rand%'] = rand(1,100);
	return $output_tokens;
}
// The low priority will not overwrite what's set up. Higher values will.
add_filter('acm_output_tokens', 'largo_acm_output_tokens', 5, 3 );


// Add actaul ad tags
function largo_ad_tags_ids( $ad_tag_ids ) {
	return array(
		array(
			'tag' => 'banner',
			'url_vars' => array(
				'sz' => '728x90',
				'bgcolor' => '666666',
				'fgcolor' => '00ff00',
			),
		),
		array(
			'tag' => 'mobile',
			'url_vars' => array(
				'sz' => '300x50',
				'bgcolor' => '9999ff',
				'fgcolor' => '333333',
			),
		),
		array(
			'tag' => 'widget',
			'url_vars' => array(
				'sz' => '300x250',
				'bgcolor' => '443322',
				'fgcolor' => 'ffeedd',
			),
		),
	);	
}
add_filter( 'acm_ad_tag_ids', 'largo_ad_tags_ids' );


function largo_acm_output_html( $output_html, $tag_id ) {
	return '<img src="http://placehold.it/%sz%/%bgcolor%/%fgcolor%&text=(%zone1%)+' . urlencode($tag_id) . '+%rand%">';
}
add_filter( 'acm_output_html','largo_acm_output_html', 5, 2 );


add_filter( 'acm_display_ad_codes_without_conditionals', '__return_true' );


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