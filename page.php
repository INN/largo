<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 */

get_header(); ?>

<div id="content" class="span8" role="main">
	<?php the_post(); ?>
	<?php get_template_part( 'content', 'page' ); ?>

</div><!-- /.grid_8 #content -->
<aside id="sidebar" class="span4">
<?php get_sidebar('single'); ?>
</aside>
<!-- /.grid_4 -->
<?php get_footer(); ?>