<?php
/**
 * The Template for displaying all single posts.
 */
get_header();
?>

<div id="content" class="span8" role="main">
	<?php
		while ( have_posts() ) : the_post();
			get_template_part( 'content', 'single' );
			comments_template( '', true );
		endwhile;
	?>
</div><!--#content-->

<?php get_sidebar(); ?>
<?php get_footer(); ?>