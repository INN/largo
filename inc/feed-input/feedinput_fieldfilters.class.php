<?php
/**
 * Collection of callbacks to use with converting feed item data into
 * a post.
 */
class FeedInput_FieldFilters {
	
	/**
	 * Generate the post_name from the item's title
	 */
	static function post_name( $data ) {
		return sanitize_title( $data['title'] );
	}


	/**
	 * Try to match the first author with a WordPress user.
	 */
	static function post_author( $data ) {
		global $wpdb;

		if ( !empty( $data['authors'][0]['name']) || !empty( $data['authors'][0]['email']) ) {
			$where = array();
			if ( !empty( $data['authors'][0]['name']) ) {
				$where[] = $wpdb->prepare( "user_nicename = '%s'", $data['authors'][0]['name'] );
				$where[] = $wpdb->prepare( "user_login = '%s'", $data['authors'][0]['name'] );
			}
			if ( !empty( $data['authors'][0]['email']) ) {
				$where[] = $wpdb->prepare( "user_email = '%s'", $data['authors'][0]['email'] );
			}
			$query = "SELECT user.ID FROM {$wpdb->users} AS user WHERE " . implode( ' OR ', $where ) . ' LIMIT 0, 1';
			$user = $wpdb->get_row( $query );

			if ( !empty( $user->ID ) ) {
				return $user->ID;
			}
		}

		return 0;
	}


	/**
	 * Convert the categories into tags
	 */
	static function tax_input( $data, $taxonomy='post_tag' ) {
		if ( empty($taxonomy) || empty($data) ) {
			return array();
		}

		$terms = array();
		foreach ( $data['categories'] as $term ) {
			$terms[] = $term['label'];
		}

		return array( "$taxonomy" => $terms );
	}
}