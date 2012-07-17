<?php
/**
 * The template for displaying content type archives
 */

get_header(); ?>

		<div id="content" class="stories span8" role="main">

			<?php if ( have_posts() ) : ?>

			<div class="category-background">
				<p class="subscribe"><a href="<?php echo esc_url( get_term_feed_link( get_queried_object() ) ); ?>">Follow this topic</a></p>
				<h1 class="page-title"><?php single_term_title(); ?></h1>

				<?php
					$term_description = term_description();
					if ( $term_description )
						echo '<div class="taxonomy-description">' . $term_description . '</div>';
				?>
			</div> <!-- /.category-background -->

			<h3 class="recent-posts">Recent posts</h3>

			<?php
				/* Start the Loop */
				while ( have_posts() ) : the_post();
					get_template_part( 'content', 'taxonomy' );
				endwhile;
				argo_content_nav( 'nav-below' );

			else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title">Nothing Found</h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p>Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.</p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>

		</div>
		<!-- /.grid_8 #content -->
<aside id="sidebar" class="span4">
<?php get_sidebar('topic'); ?>
</aside>
<!-- /.grid_4 -->
<?php get_footer(); ?>
