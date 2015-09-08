<?php

/*
 * Catch-all sidebar partial
 *
 * @ignore
 * @package Largo
 */
$custom_sidebar = largo_get_custom_sidebar();
if (!dynamic_sidebar($custom_sidebar)) {
	if (!dynamic_sidebar('sidebar-main'))
		get_template_part('partials/sidebar', 'fallback');
}
