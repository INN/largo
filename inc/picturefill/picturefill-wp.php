<?php
/*
Plugin Name: Picturefill.WP
Plugin URI: http://github.com/kylereicks/picturefill.js.wp
Description: A wordpress plugin to load responsive/retina images via picturefill.js.
Author: Kyle Reicks
Version: 1.1.3
Author URI: http://github.com/kylereicks/
*/

define('PICTUREFILL_WP_PATH', get_template_directory() . '/inc/picturefill/');
define('PICTUREFILL_WP_URL', plugins_url('/', __FILE__));
define('PICTUREFILL_WP_VERSION', '1.1.3');

require_once(PICTUREFILL_WP_PATH . 'inc/class-picturefill-wp.php');

Picturefill_WP::get_instance();
