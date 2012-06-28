<?php
/*
Plugin Name: TGM Example Plugin
Plugin URI: https://github.com/thomasgriffin/TGM-Plugin-Activation
Description: This is an example plugin to going along with the TGM Plugin Activation class.
Author: Thomas Griffin
Author URI: http://thomasgriffinmedia.com/
Version: 1.0.0
License: GNU General Public License v3.0
License URI: http://www.opensource.org/licenses/gpl-license.php
*/

/*  Copyright 2011  Thomas Griffin  (email : thomas@thomasgriffinmedia.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 3, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    
*/


add_action( 'rightnow_end', 'tgm_php_mysql_versions', 9 );
/**
 * tgm_php_mysql_versions function.
 * 
 * This function displays the current server's PHP and MySQL versions right below the WordPress version in the Right Now dashboard widget.
 *
 * @since 1.0.0
 *
 */
function tgm_php_mysql_versions() {
	global $wpdb;
	echo '<p>You are running on <strong>PHP ' . phpversion() . '</strong> and <strong>MySQL ' . $wpdb->db_version() . '</strong>.</p>';
}