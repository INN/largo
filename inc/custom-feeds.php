<?php
/**
 * Create a full text RSS feed even if site is using excerpts in the main feed
 * URL for full text feed is: http://example.org/?feed=fulltext
 *
 * @package Largo
 * @since 1.0
 */

function largo_full_text_feed() {
    add_filter('pre_option_rss_use_excerpt', '__return_zero');
    load_template( ABSPATH . WPINC . '/feed-rss2.php' );
}
add_feed('fulltext', 'largo_full_text_feed');

/*
 * Register a custom RSS feed for MailChimp (to include thumbnail images)
 * Feed address to use for MailChimp import will be http://myurl.com/?feed=mailchimp
 * And then you'll use the *|RSSITEM:IMAGE|* merge tag in your MailChimp template
 *
 * @package Largo
 * @since 1.0
 */
function largo_mailchimp_rss() {
	add_filter('pre_option_rss_use_excerpt', '__return_zero');
	load_template( get_template_directory() . '/feed-mailchimp.php' );
}
add_feed('mailchimp', 'largo_mailchimp_rss');
