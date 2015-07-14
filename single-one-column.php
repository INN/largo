<?php
/**
 * Single Post Template: One Column (Standard Layout)
 * Template Name: One Column (Standard Layout)
 * Description: Shows the post but does not load any sidebars.
 */

global $shown_ids;

add_filter( 'body_class', function( $classes ) {
	$classes[] = 'normal';
	return $classes;
} );

get_header();
?>

<div id="content" role="main">
	<?php
		while ( have_posts() ) : the_post();
			
			$shown_ids[] = get_the_ID();
			
			$partial = ( is_page() ) ? 'page' : 'single';
			
			get_template_part( 'partials/content', $partial );

			if ( $partial === 'single' ) {
				if ( is_active_sidebar( 'article-bottom' ) ) {

					do_action( 'largo_before_post_bottom_widget_area' );

					echo '<div class="article-bottom">';
					dynamic_sidebar( 'article-bottom' );
					echo '</div>';

					do_action( 'largo_after_post_bottom_widget_area' );

				}

				do_action(' largo_before_comments' );

				comments_template( '', true );

				do_action( 'largo_after_comments' );
			}

		endwhile;
	?>
</div>

<?php do_action( 'largo_after_content' ); ?>

<?php get_footer();
