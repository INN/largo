<?php
/**
 * The template for displaying 404 pages.
**/
get_header(); ?>

<div id="content" class="span8" role="main">
	<?php get_template_part( 'content', 'not-found' ); ?>
</div><!--#content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>