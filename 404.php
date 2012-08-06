<?php
/**
 * The template for displaying 404 pages.
**/
get_header(); ?>

<div id="content" class="span8" role="main">
	<h1 class="entry-title">Not Found</h1>
	<p>Sorry. We can't find the page you were looking for. Maybe searching will help:</p>
	<?php get_search_form(); ?>
</div><!-- /.grid_8 #content -->
<aside id="sidebar" class="span4">
<?php get_sidebar('single'); ?>
</aside>
<!-- /.grid_4 -->
<?php get_footer(); ?>