<?php
/**
 * The homepage template
 */
get_header();

/*
 * Collect post IDs in each loop so we can avoid duplicating posts
 * and get the theme option to determine if this is a two column or three column layout
 */
$ids = array();
$layout = of_get_option('homepage_layout');
$tags = of_get_option ('tag_display');
?>

<div id="content" class="stories span8" role="main">

	<?php if ( $layout === '3col' ) { ?>
	<div id="content-main" class="span8">
	<?php }
	// get the optional homepage top section (if set)
	if ( of_get_option('homepage_top') === 'topstories' ) {
		get_template_part( 'home-part-topstories' );
	} else if ( of_get_option('homepage_top' ) === 'slider') {
		get_template_part( 'home-part', 'slider' );
	}

	// sticky posts box if this site uses it
	if ( of_get_option( 'show_sticky_posts' ) ) {
		get_template_part( 'home-part', 'sticky-posts' );
	}

	// bottom section, we'll either use a two-column widget area or a single column list of recent posts
	if ( of_get_option( 'homepage_bottom') === 'widgets' ) {
		get_template_part( 'home-part', 'bottom-widget-area' );
	} else {
		$args = array(
			'paged'			=> $paged,
			'post_status'	=> 'publish',
			'posts_per_page'=> 10,
			'post__not_in' 	=> $ids
			);
		if ( of_get_option('num_posts_home') )
			$args['posts_per_page'] = of_get_option('num_posts_home');
		if ( of_get_option('cats_home') )
			$args['cat'] = of_get_option('cats_home');
		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) : $query->the_post();
				//if the post is in the array of post IDs already on this page, skip it
				if ( in_array( get_the_ID(), $ids ) ) {
					continue;
				} else {
					$ids[] = get_the_ID();
					get_template_part( 'content', 'home' );
				}
			endwhile;
			largo_content_nav( 'nav-below' );
		} else {
			get_template_part( 'content', 'not-found' );
		}
	}

	if ( $layout === '3col' ) { ?>
	</div>
	<div id="left-rail" class="span4">
	<?php if ( ! dynamic_sidebar( 'homepage-left-rail' ) ) : ?>
		<p><?php _e('Please add widgets to this content area in the WordPress admin area under appearance > widgets.', 'largo'); ?></p>
	<?php endif; ?>
	</div>
	<?php } ?>

</div><!-- #content-->

<?php get_sidebar(); ?>
<?php get_footer(); ?>