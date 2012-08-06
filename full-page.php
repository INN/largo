<?php
/**
 * Template Name: Full Width Page
 * Description: Expand the content to 100% and hide the sidebar
 */

get_header(); ?>

<div id="content" class="row-fluid span12" role="main">
	<?php the_post(); ?>
	<?php get_template_part( 'content', 'page' ); ?>

</div><!-- /.grid_12 #content -->
<?php get_footer(); ?>