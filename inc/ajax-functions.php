<?php

/*
 * Enqueue script for "load more posts" functionality
 */
if (!function_exists('largo_load_more_posts_enqueue_script')) {
	function largo_load_more_posts_enqueue_script() {
		global $wp_query;

		wp_enqueue_script(
			'load-more-posts',
			get_template_directory_uri() . '/js/load-more-posts.js',
			array('jquery'), null, true
		);
		wp_localize_script(
			'load-more-posts', 'LMP', array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'paged' => (!empty($wp_query->query_vars['paged']))? $wp_query->query_vars['paged'] : 0,
				'query' => $wp_query->query
			)
		);
	}
	add_action('wp_enqueue_scripts', 'largo_load_more_posts_enqueue_script');
}

/*
 * Renders markup for a page of posts and sends it back over the wire.
 */
if (!function_exists('largo_load_more_posts')) {
	function largo_load_more_posts() {
		$paged = $_POST['paged'];
		$context = (isset($_POST['query']))? $_POST['query'] : array();

		$args = array_merge(array(
			'paged' => $paged,
			'post_status' => 'publish',
			'posts_per_page' => 10,
			'ignore_sticky_posts' => true
		), $context);

		if ( of_get_option('num_posts_home') )
			$args['posts_per_page'] = of_get_option('num_posts_home');
		if ( of_get_option('cats_home') )
			$args['cat'] = of_get_option('cats_home');
		$query = new WP_Query($args);

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) : $query->the_post();
				get_template_part( 'partials/content', 'home' );
			endwhile;
		}
		wp_die();
	}
	add_action('wp_ajax_nopriv_load_more_posts', 'largo_load_more_posts');
	add_action('wp_ajax_load_more_posts', 'largo_load_more_posts');
}
