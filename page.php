<?php
/**
 * The template for displaying all pages.
 */
get_header();
?>

<div id="content" class="span8" role="main">
	<?php
		the_post();
		get_template_part( 'content', 'page' );
	?>
</div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>