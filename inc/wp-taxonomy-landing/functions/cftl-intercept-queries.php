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

function cftl_find_override_page(&$query_obj) {
	$override_query = array(
		'post_type' => 'cftl-tax-landing',
		'post_status' => 'publish',
		'numberposts' => 1,
		'tax_query' => $query_obj->tax_query->queries
	);
	if (is_array($override_query['tax_query'])) {
		foreach (array_keys($override_query['tax_query']) as $key) {
			if (is_array($override_query['tax_query'][$key])) {
				$override_query['tax_query'][$key]['include_children'] = false;
			}
		}
	}
	$landings = get_posts($override_query);
	if (!is_array($landings) || empty($landings)) {
		return false;
	}
	foreach ($query_obj->tax_query->queries as $tax_query) {
		if (is_object_in_term($landings[0]->ID, $tax_query['taxonomy'], $tax_query['terms'])) {
			return $landings[0];
		}
	}
	return false;
}

function cftl_intercept_get_posts(&$query_obj) {
	global $cftl_previous, $wp_rewrite;
	if (!$query_obj->is_main_query()) {
		return;
	}
	remove_action('pre_get_posts', 'cftl_intercept_get_posts');

	/**
	 * NOTE: We need the explicit is_tax, is_tag, is_category checks below as
	 * is_tax is not set on tag or category queries. (as of WP 3.4)
	 */
	if (!$query_obj->is_feed && ($query_obj->is_tax || $query_obj->is_tag || $query_obj->is_category)) {
		$landing = false;
		$qv = $query_obj->query_vars;
		$a = is_object($wp_rewrite);
		$b = $wp_rewrite->using_permalinks();
		if (is_object($wp_rewrite) && $wp_rewrite->using_permalinks()) {
			// Permalink rewrites kill paged=1
			$landing = cftl_find_override_page($query_obj);
		}

		if (!isset($qv['paged']) || (int) $qv['paged'] >= 1) {
			// Only handle the landing page, not an explicit call to page 1
			if ($landing) {
				add_filter('redirect_canonical', 'cftl_maintain_paged');
			}
			return;
		}

		if (!$landing) {
			$landing = cftl_find_override_page($query_obj);
		}

		if (!$landing) {
			return;
		}

		$cftl_previous['query'] = $query_obj->query;
		$cftl_previous['query_vars'] = $query_obj->query_vars;
		$cftl_previous['queried_object'] = $query_obj->get_queried_object();
		$query = 'post_type=cftl-tax-landing&p=' . absint($landing->ID);
		$query_obj->parse_query($query);

		add_filter('redirect_canonical', 'cftl_abort_redirect_canonical');

		$page_template = get_post_meta($landing->ID, '_wp_page_template', true);
		if (!empty($page_template)) {
			add_filter('template_include', 'cftl_intercept_template_loader');
		}
	}
}

function cftl_abort_redirect_canonical($redirect_url) {
	return false;
}

if (!is_admin()) {
	add_action('pre_get_posts', 'cftl_intercept_get_posts');
}

function cftl_unparse_url($parsed_url) {
	$scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
	$host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
	$port = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
	$user = isset($parsed_url['user']) ? $parsed_url['user'] : '';
	$pass = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
	$pass = ($user || $pass) ? "$pass@" : '';
	$path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
	$query = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
	$fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
	return sprintf('%s%s%s%s%s%s%s%s', $scheme, $user, $pass, $host, $port,
		$path, $query, $fragment);
}


function cftl_maintain_paged($redirect_url) {
	global $wp_rewrite;
	if (get_query_var('paged') == 1) {
		$redirect_parts = parse_url($redirect_url);
		$redirect_parts['path'] = trailingslashit($redirect_parts['path']) . user_trailingslashit($wp_rewrite->pagination_base . '/1', 'paged');
		$redirect_url = cftl_unparse_url($redirect_parts);
	}
	return $redirect_url;
}

// As of WP3.4, _wp_page_template is _forced_ to only be for pages.  Override
// that behavior for landing pages (which aren't quite pages)
// Modified from get_page_template in wp-includes/template.php
function cftl_get_page_template($template, $default_template = null) {
	$id = get_queried_object_id();
	$slug_template = get_page_template_slug();
	$pagename = get_query_var('pagename');

	if (!$pagename && $id) {
		// If a static page is set as the front page, $pagename will not be set. Retrieve it from the queried object
		$post = get_queried_object();
		$pagename = $post->post_name;
	}

	$templates = array();
	if ($slug_template && 0 === validate_file($slug_template)) {
		$templates[] = $template;
	}
	if ($pagename) {
		$templates[] = "page-$pagename.php";
	}
	if ($id) {
		$templates[] = "page-$id.php";
	}
	if ($template && 0 === validate_file($template)) {
		$templates[] = $template;
	}
	if (!in_array('page.php', $templates)) {
		$templates[] = 'page.php';
	}
	if (!empty($default_template)) {
		$templates[] = basename($default_template);
	}

	return get_query_template('page', $templates);
}

function cftl_intercept_template_loader($template) {
	global $post;
	if (isset($post) && is_object($post) && 'cftl-tax-landing' == $post->post_type) {
		$post_template = get_post_meta($post->ID, '_wp_page_template', true);
		if (empty($post_template)) {
			return $template;
		}
		$template = cftl_get_page_template($post_template, $template);
		remove_filter('template_include', 'cftl_intercept_template_loader');
		$template = apply_filters( 'template_include', $template);
		add_filter('template_include', 'cftl_intercept_template_loader');
	}
	return $template;
}

add_filter('template_include', 'cftl_intercept_template_loader');

