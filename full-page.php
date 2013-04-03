<?php
/**
 * Template Name: Full Width Page
 * Single Post Template: Full-width (no sidebar)
 * Description: Shows the post but does not load any sidebars, allowing content to span full container width.
 */
get_header();
?>

<div id="content" class="span12" role="main">
	<?php
		while ( have_posts() ) : the_post();
			if ( is_page() ) {
				get_template_part( 'content', 'page' );
			} else {
				get_template_part( 'content', 'single' );
				comments_template( '', true );
			}
		endwhile; // end of the loop.
	?>
</div><!--#content-->

<?php get_footer(); ?>