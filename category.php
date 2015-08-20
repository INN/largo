<?php
/**
 * Template for category archive pages
 *
 * @package Largo
 * @since 0.4
 */
get_header();

global $tags, $paged, $post, $shown_ids;

$title = single_cat_title('', false);
$description = category_description();
$rss_link = get_category_feed_link(get_queried_object_id());
$posts_term = of_get_option('posts_term_plural', 'Stories');
?>

<div class="clearfix">
	<header class="archive-background clearfix">
		<a class="rss-link rss-subscribe-link" href="<?php echo $rss_link; ?>"><?php echo __( 'Subscribe', 'largo' ); ?> <i class="icon-rss"></i></a>
		<h1 class="page-title"><?php echo $title; ?></h1>
		<div class="archive-description"><?php echo $description; ?></div>
		<?php get_template_part('partials/archive', 'category-related'); ?>
	</header>

	<?php

	$featured_posts = largo_get_featured_posts_in_category( $wp_query->query_vars['category_name'] );
	if ( $paged < 2 ) {
		if ( count( $featured_posts ) > 0 ) {
			foreach ( $featured_posts as $idx => $featured_post ) {
				$shown_ids[] = $featured_post->ID;
				if ( $idx == 0 ) { ?>
					<div class="primary-featured-post">
						<?php largo_render_template(
							'partials/archive',
							'category-primary-feature',
							array( 'featured_post' => $featured_post )
						); ?>
					</div>
					<div class="secondary-featured-post">
						<div class="row-fluid clearfix">
			<?php } else {
					largo_render_template(
						'partials/archive',
						'category-secondary-feature',
						array( 'featured_post' => $featured_post )
					);
				}
			}
		} ?>
						</div>
					</div>
<?php } ?>
</div>

<div class="row-fluid clearfix">
	<div class="stories span8" role="main" id="content">
		<?php if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				//$shown_ids[] = get_the_ID();
				get_template_part( 'partials/content', 'archive' );
			}
			largo_content_nav( 'nav-below' );
		} else {
			get_template_part( 'partials/content', 'not-found' );
		} ?>
	</div>
	<?php get_sidebar(); ?>
</div>

<?php get_footer();
