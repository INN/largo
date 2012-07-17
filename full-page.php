<?php
/**
 * Template Name: Full Width Page
 * Description: A Page Template that spans 940px
 */

get_header(); ?>

<div id="content" class="row-fluid span12" role="main">
	<?php the_post(); ?>
	<?php get_template_part( 'content', 'page' ); ?>

</div><!-- /.grid_12 #content -->
<?php get_footer(); ?>