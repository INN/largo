<?php
/* Homepage blog river/list */

global $shown_ids;

$args = array(
	'paged' => $paged,
	'post_status' => 'publish',
	'posts_per_page' => 10,
	'post__not_in' => $shown_ids,
	'ignore_sticky_posts' => true
);

if ( of_get_option( 'num_posts_home' ) ) {
	$args['posts_per_page'] = of_get_option( 'num_posts_home', 10 );
}
if ( of_get_option( 'cats_home' ) ) {
	$args['cat'] = of_get_option( 'cats_home', '' );
}

$query = new WP_Query($args);

if ( $query->have_posts() ) {
	$counter = 1;
	while ($query->have_posts()) : $query->the_post();
		//if the post is in the array of post IDs already on this page, skip it. Just a double-check
		if ( in_array( get_the_ID(), $shown_ids))
			continue;
		else {
			$shown_ids[] = get_the_ID();
			do_action( 'largo_before_home_list_post', $post, $query );
			get_template_part('partials/content', 'home');
			do_action( 'largo_after_home_list_post', $post, $query );
			do_action( 'largo_loop_after_post_x', $counter, $context = 'home' );
			$counter++;
		}
	endwhile;
	largo_content_nav( 'nav-below' );
} else {
	get_template_part( 'partials/content', 'not-found' );
}
