<?php
/*
Plugin Name: Taxonomy Landing Pages
Plugin URI: https://github.com/crowdfavorite/wp-taxonomy-landing
Description: Allow separately designed landing pages for taxonomy archives.
Version: 1.1.2
Author: Crowd Favorite
Author URI: http://crowdfavorite.com
*/

/**
 * @package taxonomy-landing
 *
 * This file is part of Taxonomy Landing for WordPress
 * http://github.com/crowdfavorite/wp-taxonomy-landing
 *
 * Copyright (c) 2009-2012 Crowd Favorite, Ltd. All rights reserved.
 * http://crowdfavorite.com
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * **********************************************************************
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * **********************************************************************
 */

// ini_set('display_errors', '1'); ini_set('error_reporting', E_ALL);

if (!defined('LARGO_TEMPLATE_LANDING_VERSION')) {
	define('LARGO_TEMPLATE_LANDING_VERSION', '1.1.2');

	load_plugin_textdomain( 'cf-tax-landing' );

	include('functions/cftl-admin.php');
	include('functions/cftl-intercept-queries.php');
	include('functions/cftl-series-order.php');

} // End CF_TEMPLATE_LANDING_VERSION check