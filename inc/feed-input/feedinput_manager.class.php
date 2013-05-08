<?php

/**
 * Takes care of the high level tasks of managing the various feeds
 */
class FeedInput_Manager {
	
	static $feed_sets=array();


	/**
	 * Initialize and set hooks
	 */
	static function init() {
		// If no next scheduled time is set, then go ahead and create the cron job
		if ( wp_next_scheduled( 'feedinput_update_feeds' ) === false ) {
			wp_schedule_event( time(), 'hourly', 'feedinput_update_feeds' );
		}
		add_action( 'feedinput_update_feeds', array( 'FeedInput_Manager', 'update_feeds') );
	}


	/**
	 * Register a feed
	 */
	static function register_feed_set( $feed_name, $feed_urls, $options=array() ) {
		if ( defined('WP_DEBUG') && WP_DEBUG && isset(self::$feed_sets[$feed_name]) ) {
			trigger_error( $feed_name.' is already registered with Feed Input.', E_USER_WARNING );
		}
		self::$feed_sets[ $feed_name ] = new FeedInput_FeedSet( $feed_name, $feed_urls, $options );
	}


	/**
	 * Update the value of the feeds
	 */
	static function update_feeds() {
		foreach( self::$feed_sets as $feed_name => $feed_set ) {
			$feed_set->update();
		}
	}


	/**
	 * Force an update of a feed
	 */
	static function force_update_feedset( $feed_set_name ) {
		if ( isset(self::$feed_sets[$feed_set_name]) ) {
			self::$feed_sets[$feed_set_name]->update();
		}
	}


	/**
	 * Get a registered feedset
	 *
	 * @param string $feed_name
	 *
	 * @return FeedInput_FeedSet
	 */
	static function get_feedset( $feed_name ) {
		if ( isset( self::$feed_sets[ $feed_name ] ) ) {
			return self::$feed_sets[ $feed_name ];
		}
		return null;
	}
}


// Kick it off
FeedInput_Manager::init();