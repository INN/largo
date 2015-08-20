<?php
/**
 * The homepage template
 *
 * @package Largo
 * @since 0.1
 */

/**
 * ======== DO NOT EDIT OR CLONE THIS FILE FOR A CHILD THEME =======
 *
 * Largo comes with a built-in homepage template system, documented in homepages/README.md
 * It's generally better to use that system than to have your child theme use its own home.php template
 */

get_header();

/*
 * Collect post IDs in each loop so we can avoid duplicating posts
 * and get the theme option to determine if this is a two column or three column layout
 */
$home_template = largo_get_active_homepage_layout();
$layout_class = of_get_option('home_template');
$tags = of_get_option ('tag_display');

global $largo;
if ($home_template == 'LegacyThreeColumn')
	$span_class = 'span8';
else
	$span_class = ( $largo['home_rail'] ) ? 'span8' : 'span12' ;
?>

<div id="content" class="stories <?php echo $span_class; ?> <?php echo sanitize_html_class(basename($home_template)); ?>" role="main">
<?php
	largo_render_homepage_layout($home_template);

	if ($home_template !== 'LegacyThreeColumn') {
		// sticky posts box if this site uses it
		if ( of_get_option( 'show_sticky_posts' ) ) {
			get_template_part( 'partials/sticky-posts', 'home' );
		}

		// bottom section, we'll either use a two-column widget area or a single column list of recent posts
		if ( of_get_option('homepage_bottom') === 'widgets' ) {
			get_template_part('partials/home-bottom', 'widget-area');
		} else if (of_get_option('homepage_bottom') === 'list') {
			get_template_part('partials/home-post-list');
		}
	}
?>
</div><!-- #content-->
<?php if ($largo['home_rail']) get_sidebar(); ?>
<?php get_footer();
