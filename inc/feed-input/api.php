<?php
/**
 * The public API functions for Feed Input
 *
 */


/**
 * Register a feed set to be pulled into
 *
 * @param string $feed_name - an identify name for the feed set
 * @param array $feed_urls - the URLs for the feeds
 * @param array $options - the various options for how to handle the feeds
 */
function feedinput_register_feed( $feed_name, $feed_urls, $options=array() ) {
  return FeedInput_Manager::register_feed_set( $feed_name, $feed_urls, $options );
}



/**
 * Force an update of a feed set instead of waiting for the next cron cycle
 *
 * @param string $feed_name - the identify name for the feed set
 */
function feedinput_force_update_feed( $feed_name ) {
  return FeedInput_Manager::force_update_feedset( $feed_name );
}


/**
 * Retrieve a registered feed set
 *
 * @param string $feed_name
 *
 * @return FeedInput_FeedSet
 */
function feedinput_get_feed( $feed_name ) {
  return FeedInput_Manager::get_feedset( $feed_name );
}