<?php
/*
 * For single post and pages
 *
 * @package Largo
 */
$custom_sidebar = largo_get_custom_sidebar();
if ($custom_sidebar !== 'none') {
	if (!dynamic_sidebar($custom_sidebar)) { // try the custom sidebar
		if (!dynamic_sidebar('sidebar-single')) { // if custom sidebar is empty, try single sidebar
			if (!dynamic_sidebar('sidebar-main')) // if single sidebar is empty, try main sidebar
				get_template_part('partials/sidebar', 'fallback'); // if main sidebar is empty, use fallback
		}
	}
} else if (!dynamic_sidebar('sidebar-single')) { // try single sidebar
	if (!dynamic_sidebar('sidebar-main')) // if single sidebar is empty, try main sidebar
		get_template_part('partials/sidebar', 'fallback'); // if main sidebar is empty, use fallback
}
