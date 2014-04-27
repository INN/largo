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

			if ( is_active_sidebar( 'article-bottom' ) && is_single() ){
				do_action('largo_before_sidebar_widgets');
				echo '<div class="article-bottom">';
				dynamic_sidebar( 'article-bottom' );
				echo '</div>';
				do_action('largo_after_sidebar_widgets');
			}

			comments_template( '', true );
		endwhile;
	?>
</div><!--#content-->

<?php get_sidebar(); ?>
<?php get_footer(); ?>