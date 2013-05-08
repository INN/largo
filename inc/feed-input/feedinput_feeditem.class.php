<?php

/**
 * Represents an item from a feed set
 */
class FeedInput_FeedItem {
	
	// Static methods

	/**
	 * Setup global stuff for FeedItem
	 */
	static function init() {
		add_action( 'init', array( 'FeedInput_FeedItem', 'register_post_type' ) );
	}


	/**
	 * Register the post type and taxonomy
	 */
	static function register_post_type() {
		// The post type for a feed item
		register_post_type( 'feedinput_item', array(
			'public' => false,
		));

		// Taxonomy for the feeds
		register_taxonomy( 'feedinput_feed', 'feedinput_item', array(
			'public' => false,
			'rewrite' => false,
			'query_var' => false,
			'show_ui' => false,
		) );
	}


	/**
	 * Parse an array of SimplePie_Items into items
	 *
	 * @param array $items - array of SimplePie_Items
	 * @param FeedInput_FeedSet - the feed set the items come from
	 *
	 * @return an array of FeedInput_Items
	 */
	static function parse_feed_items( $items, $feedset ) {

		// Get the UIDs
		$uids = array();
		foreach ( $items as $item ) {
			$uids[] = $item->feed->feed_url . '::' . $item->get_id();
		}

		// Retrieve the items already pulled
		$posts = get_posts( array(
			'post_type' => 'feedinput_item',
			'posts_per_page' => count( $uids ),
			'post_status' => array('any', 'trash'),
			'meta_query' => array(
				array(
					'key' => 'uid',
					'value' => $uids,
					'compare' => 'IN'
				)
			)
		));

		// Make it easy to get post by UID
		$uid_to_post = array();
		foreach ( $posts as $post ) {
			$uid_to_post[$post->post_title] = $post;
		}

		// Create the array of items
		$feedinput_items = array();

		// Import new items
		foreach ( $items as $item ) {
			$uid = $item->feed->feed_url . '::' . $item->get_id();

			if ( isset($uid_to_post[$uid]) ) {
				// Existing item
				$data = self::get_data_from_post( $uid_to_post[$uid] );
				$feedinput_items[] = new FeedInput_FeedItem( $data, $uid_to_post[$uid] );
			} else {
				// New item
				$data = self::parse_item( $item, $feedset );
				$feedinput_items[] = new FeedInput_FeedItem( $data );
			}			 
		}

		return $feedinput_items;
	}


	/**
	 *
	 */
	static function get_items( $feedset, $number_of_items=10, $page=1 ) {
		$posts = get_posts( array(
			'post_type' => 'feedinput_item',
			'posts_per_page' => $number_of_items,
			'paged' => $page,
			'post_status' => array('any'),
			'tax_query' => array(
				array(
					'taxonomy' => 'feedinput_feed',
					'field' => 'slug',
					'terms' => $feedset->name,
				)
			)
		));

		$items = array();

		foreach ( $posts as $post ) {
			$data = self::get_data_from_post( $post );
			$items[] = new FeedInput_FeedItem( $data, $post );
		}

		return $items;
	}


	/**
	 * @param FeedInput_FeedSet $feedset
	 * @param string $uid
	 *
	 * @return FeedInput_FeedItem
	 */
	static function get_item( $feedset, $uid ) {
		$posts = get_posts( array(
			'post_type' => 'feedinput_item',
			'posts_per_page' => 1,
			'post_status' => array('any'),
			'tax_query' => array(
				array(
					'taxonomy' => 'feedinput_feed',
					'field' => 'slug',
					'terms' => $feedset->name,
				)
			),
			'meta_query' => array(
				array(
					'key' => 'uid',
					'value' => $uid,
				)
			)
		));
		
		if ( count( $posts ) > 0 ) {
			$data = self::get_data_from_post( $posts[0] );
			return new FeedInput_FeedItem( $data, $posts[0] );
		}
		return null;
	}


	/**
	 * Retrieve the data object for an item from a post object
	 */
	protected function get_data_from_post( $post ) {
		$data = get_post_meta( $post->ID, 'data', true );
		return json_decode( $data, true );
	}


	/** 
	 * Parse a feed item into data object
	 */
	protected function parse_item( $item, $feedset ) {
		$data = array();

		$data['uid'] = $item->feed->feed_url . '::' . $item->get_id();
		$data['id'] = $item->get_id();
		$data['title'] = $item->get_title();
		$data['permalink'] = $item->get_permalink();
		$data['content'] = $item->get_content();
		$data['description'] = $item->get_description();
		$data['copyright'] = $item->get_copyright();
		$data['links'] = $item->get_links();
		$data['latitude'] = $item->get_latitude();
		$data['longitude'] = $item->get_longitude();

		$feed = $item->get_feed();
		$data['feed_title'] = $feed->get_title();

		$data['feed_url'] = $feed->feed_url;

		// Feed Authors
		$author_objs = $feed->get_authors();
		$data['feed_authors'] = array();
		if ( is_array($author_objs) ) {
			foreach ( $author_objs as $author ) {
				$data['feed_authors'][] = self::parse_author( $author, $feedset );
			}
		}

		// Dates
		$data['date'] = $item->get_gmdate('Y-m-d H:i:s');
		$data['updated'] = $item->get_updated_gmdate('Y-m-d H:i:s');
		if ( empty($data['updated']) ) {
			$data['updated'] = $data['date'];
		}

		// Categories
		$category_objs = $item->get_categories();
		$data['categories'] = array();
		if ( is_array($category_objs) ) {
			foreach ($category_objs as $cat ) {
				$data['categories'][] = self::parse_category( $cat, $feedset );
			}
		}

		// Authors
		$author_objs = $item->get_authors();
		$data['authors'] = array();
		if ( is_array($author_objs) ) {
			foreach ( $author_objs as $author ) {
				$data['authors'][] = self::parse_author( $author, $feedset );
			}
		}

		// Contributors (same as authors in structure)
		$contributor_objs = $item->get_contributors();
		$data['contributors'] = array();
		if ( is_array($contributor_objs) ) {
			foreach ( $contributor_objs as $contributor ) {
				$data['contributors'][] = self::parse_author( $contributor, $feedset );
			}
		}

		
		// Enclosure
		$enclosure_objs = $item->get_enclosures();
		$enclosure = array();

		foreach ( $enclosure_objs as $enclosure_obj ) {
			$enclosures[] = self::parse_enclosure( $enclosure_obj, $feedset );
		}
		
		// All the raw data
		$data['data'] = $item->data['child'];

		// Allow others to hook in
		$data = apply_filters( 'feedinput_parse_item', $data, $item, $feedset );
		$data = apply_filters( "feedinput_parse_item-{$feedset->name}", $data, $item, $feedset );

		return $data;
	}


	/**
	 * Parse a SimplePie Category object into easy to use data
	 */
	protected function parse_category( $simplepie_category, $feedset ) {
		$term = $simplepie_category->get_term();
		$label = $simplepie_category->get_label();
		$data = array(
			'term' => empty($term) ? $label : $term,
			'scheme' => $simplepie_category->get_scheme(),
			'label' => empty($label) ? $term : $label,
		);

		$data = apply_filters( 'feedinput_parse_category', $data, $simplepie_category, $feedset );

		return $data;
	}


	/**
	 * Parse caption
	 */
	protected function parse_caption( $simplepie_caption, $feedset ) {
		$data = array();

		$enclousre_caption_fields = array( 'endtime', 'language', 'starttime', 'text', 'type' );
		foreach ($enclousre_caption_fields as $cap_field ) {
			$data[$cap_field] = $simplepie_caption->{"get_{$cap_field}"}();
		}

		$data = apply_filters( 'feedinput_parse_caption', $data, $simplepie_caption, $feedset );
		return $data;
	}


	/**
	 * Parse enclosure object
	 */
	protected function parse_enclosure( $simplepie_enclosure, $feedset ) {
		$data = array();

		$enclosure_fields = split( ' ', 'bitrate channels description duration expression extension framerate handler hashes height language keywords length link player sampling_rate size thumbnails title type width real_type' );

		// Simple fields
		foreach ( $enclosure_fields as $field ) {
			$data[$field] = $simplepie_enclosure->{"get_$field"}();
		}
		
		// Captions
		$caption_objs = $simplepie_enclosure->get_captions();
		$data['captions'] = array();
		if ( is_array( $caption_objs ) ) {
			foreach( $get_captions as $caption_obj ) {
				$data['captions'][] = self::parse_caption( $caption_obj, $feedset );
			}
		}

		// Categories
		$category_objs = $simplepie_enclosure->get_categories();
		$data['categories'] = array();
		if ( is_array($category_objs) ) {
			foreach ( $category_objs as $cat ) {
				$data['categories'][] = self::parse_category( $cat, $feedset );
			}
		}

		// Copyright
		$copyright = $simplepie_enclosure->get_copyright();
		$data['copyright'] = empty( $copyright ) ? null : self::parse_copyright( $copyright, $feedset );

		// Credit
		$credit = $simplepie_enclosure->get_credit();
		$data['credit'] = empty( $credit ) ? null : self::parse_credit( $credit, $feedset );


		// Ratings
		$rating_objs = $simplepie_enclosure->get_ratings();
		$data['ratings'] = array();
		if ( is_array($rating_objs) ) {
			foreach ( $rating_objs as $rating ) {
				$data['ratings'][] = self::parse_rating( $rating, $feedset );
			}
		}

		// Restrictions
		$restriction_objs = $simplepie_enclosure->get_restrictions();
		$data['restrictions'] = array();
		if ( is_array($restriction_objs) ) {
			foreach ( $restriction_objs as $restriction) {
				$data['restrictions'][] = self::parse_restriction( $restriction, $feedset );
			}
		}

		$data = apply_filters( 'feedinput_parse_enclosure', $data, $simplepie_enclosure, $feedset );

		return $data;
	}


	/**
	 * Parse copyright object
	 */
	protected function parse_copyright( $simplepie_copyright, $feedset ) {
		$data = array(
			'url' => $simplepie_copyright->get_url(),
			'attribution' => $simplepie_copyright->get_attribution(),
		);

		$data = apply_filters( 'feedinput_parse_copyright', $data, $simplepie_copyright, $feedset );

		return $data;
	}


	/**
	 * Parse credit object
	 */
	protected function parse_credit( $simplepie_credit, $feedset ) {
		$data = array(
			'role' => $simplepie_credit->get_role(),
			'scheme' => $simplepie_credit->get_scheme(),
			'name' => $simplepie_credit->get_name(),
		);

		$data = apply_filters( 'feedinput_parse_credit', $data, $simplepie_credit, $feedset );

		return $data;
	}


	/**
	 * Parse rating object
	 */
	protected function parse_rating( $simplepie_rating, $feedset ) {
		$data = array(
			'scheme' => $simplepie_rating->get_scheme(),
			'value' => $simplepie_rating->get_value(),
		);

		$data = apply_filters( 'feedinput_parse_rating', $data, $simplepie_rating, $feedset );

		return $data;
	}


	/**
	 * Parse restriction object
	 */
	protected function parse_restriction( $simplepie_restriction, $feedset ) {
		$data = array(
			'relationship' => $simplepie_restriction->get_relationship(),
			'type' => $simplepie_restriction->get_type(),
			'value' => $simplepie_restriction->get_value(),
		);

		$data = apply_filters( 'feedinput_parse_restriction', $data, $simplepie_restriction, $feedset );

		return $data;
	}
	
	/**
	 * Parse author object
	 */
	protected function parse_author( $simplepie_author, $feedset ) {
		$data = array(
			'name' => $simplepie_author->get_name(),
			'link' => $simplepie_author->get_link(),
			'email' => $simplepie_author->get_email(),
		);

		$data = apply_filters( 'feedinput_parse_author', $data, $simplepie_author, $feedset );

		return $data;
	}



	// Instances methods

	/**
	 * @param array $data
	 * @param WP_Post|null $post
	 */
	function __construct( $data, $post=null ) {
		$this->data = $data;
		$this->post = $post;
	}


	/**
	 * Save the item to the DB
	 *
	 * @param FeedInput_FeedSet $feedset - the feed set
	 * @param boolean $force - force saving of the item
	 */
	function save( $feedset=null, $force=false ) {
		if ( empty( $this->post ) ) {
			$id = wp_insert_post( array(
				'post_type' => 'feedinput_item',
				'post_title' => $this->data['uid'],
			) );

			if ( $id != 0 ) {
				$this->post = get_post( $id );
			}

			add_post_meta( $this->post->ID, 'uid', $this->data['uid'], true );
			add_post_meta( $this->post->ID, 'data', addslashes( json_encode( $this->data ) ) );

		} elseif ( $force ) {
			wp_update_post( array(
				'ID' => $this->post->ID,
				'post_type' => 'feedinput_item',
				'post_title' => $this->data['uid'],
			) );

			update_post_meta( $this->post->ID, 'uid', $this->data['uid'] );
			update_post_meta( $this->post->ID, 'data', addslashes( json_encode( $this->data ) ) );
		}


		// Add category for the Feedset it is from
		if ( !empty( $feedset ) ) {
			wp_set_post_terms( $this->post->ID, $feedset->name, 'feedinput_feed', true );
		}
	}


	/**
	 *
	 */
	function remove_item() {
		if ( !empty( $this->post ) ) {
			return wp_update_post( array( 'ID' => $this->post->ID, 'post_status' => 'trash' ) );
		}

		return null;
	}


	/**
	 * Convert the feed item into a post
	 *
	 * @param array $options
	 * @param FeedInput_FeedSet $feedset
	 *
	 * @return The WP_Post
	 */
	function convert_to_post( $options, $feedset ) {
		if ( empty( $this->post->ID ) ) {
			$this->save( $feedset );
		}

		// echo $this->post->ID;
		// echo "\n";
		// echo $this->post->post_content_filtered;
		// exit();

		// Abort if we already converted to a post
		$converted_posts = get_post_meta( $this->post->ID, 'converted_posts', true );
		if ( empty( $converted_posts ) ) {
			$converted_posts = array();
		}
		
		if ( isset( $converted_posts[$feedset->name] ) ) {
			return get_post( $converted_posts[$feedset->name] );
		}


		$default_post = array(
			'post_title' =>        array( 'type' => 'field', 'value' => 'title' ),
			'post_content' =>      array( 'type' => 'field', 'value' => 'content' ),
			'post_type' =>         array( 'type' => 'literal' , 'value' => $feedset->options['convert_post_type']),
			'post_date_gmt' =>     array( 'type' => 'field' , 'value' => 'date'),
			'post_modified_gmt' => array( 'type' => 'field' , 'value' => 'date'),
			'post_name' =>         array( 'type' => 'callback', 'value' => array( 'FeedInput_FieldFilters', 'post_name') ),
			'post_author' =>       array( 'type' => 'callback', 'value' => array( 'FeedInput_FieldFilters', 'post_author') ),
			'post_status' =>       array( 'type' => 'literal', 'value' => 'draft' ),
			'tax_input' =>         array( 'type' => 'callback', 'value' => array( 'FeedInput_FieldFilters', 'tax_input') ),
			'menu_order' =>        array( 'type' => 'literal', 'value' => 0 ),
			'comment_status' =>    array( 'type' => 'literal', 'value' => 'closed' ),
			'ping_status' =>       array( 'type' => 'literal', 'value' => 'closed' ),
		);

		$post_map = array_merge( $default_post, $options['post'] );
		$post = $this->populate_values( $post_map );
		$post = apply_filters( 'feedinput_convert_post_data', $post, $this->data );

		$post_id = wp_insert_post( $post );
		
		// Abort if unable to save
		if ( empty( $post_id ) ) {
			return;
		}

		// Save the post meta
		$default_meta = array(
			'feedinput_item' => array( 'type' => 'literal', 'value' => $this->post->ID ),
			'feed_name' =>      array( 'type' => 'literal', 'value' => $feedset->name ),
		);

		// push everything into postmeta
		foreach ( $this->data as $field => $v ) {
			$default_meta[$field] = array( 'type' => 'field', 'value' => $field );
		}


		$postmeta_map = array_merge( $default_meta, $options['meta'] );
		$postmeta = $this->populate_values( $postmeta_map );
		$postmeta = apply_filters( 'feedinput_convert_post_meta_data', $postmeta, $this->data );

		foreach ( $postmeta as $key => $value ) {
			add_post_meta( $post_id, $key, $value, true );
		}

		// Mark that we have created a post from the feed item
		$converted_posts[$feedset->name] = $post_id;
		update_post_meta( $this->post->ID, 'converted_posts', $converted_posts );


		// Allow others to do additional work
		do_action( 'feedinput_convert_to_post', get_post( $post_id ), $this->data, $feedset );
		do_action( "feedinput_convert_to_post-{$feedset->name}", get_post( $post_id ), $this->data, $feedset );

		return get_post( $post_id );
	}


	/**
	 * Executes the $map into key and values
	 */
	protected function populate_values( $map ) {
		$data = array();

		foreach ( $map as $field_name => $where ) {
			switch( $where['type'] ) {
				case 'field':
					if ( is_array($where['value']) ) {
						$v = $this->data;
						for ( $i=0; isset($v) && $i < count($where['value']); ++$i ) {
							$v = $v[ $where['value'][$i] ];
						}
						$data[$field_name] = empty($v) ? '' : $v;
					} else {
						$data[$field_name] = $this->data[$where['value']];
					}
					break;

				case 'literal':
					$data[$field_name] = $where['value'];
					break;

				case 'callback':
					$data[$field_name] = call_user_func_array( $where['value'], array( $this->data ) );
					break;
			}
		}

		return $data;
	}

}


FeedInput_FeedItem::init();
