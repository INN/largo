<?php

/**
 * @package taxonomy-landing
 *
 * This file is part of Taxonomy Landing for WordPress
 * https://github.com/crowdfavorite/wp-taxonomy-landing
 *
 * Copyright (c) 2009-2012 Crowd Favorite, Ltd. All rights reserved.
 * http://crowdfavorite.com
 *
 * **********************************************************************
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * **********************************************************************
 */

if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }

/**
 * Reorders posts according to custom order
 * Uses postmeta series_[termID]_order values
 */
function largo_series_custom_order ( $sql ) {
	global $wp_query, $opt, $wpdb;

	//only do this if we're a series page
	if ( is_array($opt) && $wp_query->query_vars['taxonomy'] == 'series' ) :

		//get the term object to set the proper meta stuff and whatnot
		$term = get_term_by( 'slug', $wp_query->query_vars['term'], 'series' );

		//custom sort order
		if ( $opt['post_order'] == 'custom' ) {

			$meta_key = 'series_' . $term->term_id . '_order';

			//retool the query
			$sql['join'] = "
				INNER JOIN $wpdb->term_relationships tr ON ($wpdb->posts.ID = tr.object_id)
				LEFT JOIN $wpdb->postmeta AS meta ON ($wpdb->posts.ID = meta.post_id AND meta.meta_key = '{$meta_key}')";
			$sql['where'] = "
				 AND ( tr.term_taxonomy_id IN (".$term->term_id.") )
				 AND $wpdb->posts.post_type = 'post'
				 AND ($wpdb->posts.post_status = 'publish' OR $wpdb->posts.post_status = 'private') ";
			$sql['orderby'] = "ISNULL(meta.meta_value+0) ASC, meta.meta_value+0 ASC, $wpdb->posts.post_date DESC";

		//top stories first
		}	elseif ( strpos( $opt['post_order'], 'top,' ) === 0 ) {

			list( $top, $sort ) = explode( " ", $opt['post_order'] );
			$top_term = get_term_by( 'slug', 'top-story', 'prominence' );

			//retool the query
			$sql['join'] = "
				INNER JOIN $wpdb->term_relationships ON (wpdb_posts.ID = wpdb_term_relationships.object_id)
				LEFT JOIN $wpdb->term_relationships t2 ON (wpdb_posts.ID = t2.object_id)
				AND (t2.term_taxonomy_id = " . $top_term->term_id . ")";
			$sql['where'] = "
				 AND ( wpdb_term_relationships.term_taxonomy_id IN (".$term->term_id.") )
				 AND wpdb_posts.post_type = 'post'
				 AND (wpdb_posts.post_status = 'publish' OR wpdb_posts.post_status = 'private') ";
			$sql['orderby'] = "ISNULL(t2.term_taxonomy_id) ASC, wpdb_posts.post_date $sort";

		}
	endif;
	return $sql;

}
add_filter( 'posts_clauses', 'largo_series_custom_order');