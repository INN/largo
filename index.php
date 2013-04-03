<?php
/**
 * Default template file.
 * Used in the rare event that none of the other templates handle a particular page type
 */
get_header();
?>

<div id="content" class="stories span8" role="main">
	<?php
		if ( have_posts() ) {
			while ( have_posts() ) : the_post();
				get_template_part( 'content', 'index' );
			endwhile;
			largo_content_nav( 'nav-below' );
		} else {
			get_template_part( 'content', 'not-found' );
		}
	?>
</div><!--#content-->

<?php get_sidebar(); ?>
<?php get_footer(); ?>