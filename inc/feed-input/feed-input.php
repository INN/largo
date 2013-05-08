<?php
/*
Plugin Name: Feed Input
Plugin URI: http://
Description: Pull RSS and Atom feeds into posts on your WordPress site
Version: 0.1.1
Author: Seamus Leahy
Author URI: http://seamusleahy.com
License: MIT
*/

if ( !defined(__DIR__) ) {
	define( __DIR__, dirname(__FILE__));
}

require_once __DIR__ .'/feedinput_fieldfilters.class.php';
require_once __DIR__ .'/feedinput_feeditem.class.php';
require_once __DIR__ .'/feedinput_feedset.class.php';
require_once __DIR__ .'/feedinput_manager.class.php';
require_once __DIR__ .'/api.php';

require_once __DIR__ . '/feedinput_adminpage.class.php';

//require_once __DIR__ .'/test.php';