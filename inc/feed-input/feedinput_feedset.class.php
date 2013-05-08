<?php
/**
 * Represents a set of Feeds.
 */
class FeedInput_FeedSet {

	var $name; // The name of the feeds
	var $urls; // array of URLs
	var $options;

	/**
	 * @param string $feed_name - The name of this feed set
	 * @param array $feed_urls - Array of URLs
	 * @param array $options - Various options for this feed set
	 *
	 * Options:
	 *
	 * array(
	 *   // Maps the item data to the post and meta fields
	 *   // The key is the name of the post field (eg. post_content) or post meta key.
	 *   // The value is an array with two values: the type is 'literal', 'field', or 'callback';
	 *   // the value is the either the literal value, the name of the field in the item data,
	 *   // or a callback that accepts the data array.
	 *   'convert' => array(
	 *     // Maps the item data to the post fields
	 *     'post' => array( 'post_field_name' => array( 'type' => 'field', 'value' => 'field_name') ),
	 *     // Maps the item data to the post meta data
	 *     'meta' => array( 'metakey' => array( 'type' => 'callback', 'value => 'callback_name' ) ),
	 *   ),
	 *
	 *   // Flag to automatically convert the items to a post
	 *   'convert_to_post' => true,
	 *
	 *   // The post type to save the converted items to
	 *   'convert_post_type' => 'post',
	 * )
	 */
	function __construct( $feed_name, $feed_urls, $options ) {
		$this->name = $feed_name;
		$this->urls = (array) $feed_urls;

		$default = array(
			// Options for converting an item into a post
			'convert' => array(),
			'convert_to_post' => true,
			'convert_post_type' => 'post'

		);
		$this->options = array_merge( $default, $options );

		$this->options['convert'] = array_merge( array(
			'post' => array(),
			'meta' => array(),
		), $this->options['convert'] );
	}


	/**
	 * Call to update the feed set
	 */
	function update() {

		$feed = fetch_feed( $this->urls );

		$items = FeedInput_FeedItem::parse_feed_items( $feed->get_items(), $this );

		foreach ( $items as $item ) {
			$item->save( $this );
		}
		
		if ( $this->options['convert_to_post']) {
			foreach ( $items as $item ) {
				$item->convert_to_post( $this->options['convert'], $this );
			}
		}
	}


	/**
	 * Retrieve saved items
	 *
	 * @param int $number_of_items - the number of items to return
	 * @param int $page - the page of results to retrieve
	 */
	function get_items( $number_of_items=10, $page=1 ) {
		return FeedInput_FeedItem::get_items( $this, $number_of_items, $page );
	}
	

	/**
	 * Convert an item in the feed into a post
	 */
	function convert_item_to_post( $uid ) {
		$item = FeedInput_FeedItem::get_item( $this, $uid );
		if ( !empty( $item ) ) {
			return $item->convert_to_post( $this->options['convert'], $this );
		}

		return null;
	}


	function remove_item( $uid ) {
		$item = FeedInput_FeedItem::get_item( $this, $uid );
		if ( !empty( $item ) ) {
			return $item->remove_item();
		}

		return null;
	}
}
