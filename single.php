<?php
/**
 * The Template for displaying all single posts.
 */

get_header(); ?>

		<div id="content" class="span8" role="main">

				<?php
				while ( have_posts() ) : the_post();
					get_template_part( 'content', 'single' );
					comments_template( '', true );
				endwhile; // end of the loop.
				?>

		</div><!--/.grid_8 #content-->

		<div id="sidebar" class="span4">
			<?php get_sidebar('single'); ?>
		</div><!-- /.grid_4 -->
<?php get_footer(); ?>