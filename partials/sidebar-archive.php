<?php
/*
 * For archive/taxonomy pages
 *
 * @package Largo
 */
$custom_sidebar = largo_get_custom_sidebar();
if (!dynamic_sidebar($custom_sidebar)) { // try custom sidebar
	// if custom sidebar is empty, try to get the topic sidebar
	if ((is_archive() || is_tax()) && of_get_option('use_topic_sidebar') && is_active_sidebar('topic-sidebar')) {
		if (!dynamic_sidebar('topic-sidebar')) {
			if (!dynamic_sidebar('sidebar-main')) // if topic sidebar empty, try the main sidebar
					get_template_part('partials/sidebar', 'fallback'); // if the main sidebar is emtpy, use fallback
		}
	} else {
		if (!dynamic_sidebar('sidebar-main')) // if topic sidebar is inactive, try the main sidebar
			get_template_part('partials/sidebar', 'fallback'); // if the main sidebar is empty, use fallback
	}
}
