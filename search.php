<?php
/**
 * The template for displaying Search Results pages.
 */

get_header(); ?>

		<div id="content" class="span8" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="search-term">Your Search for <?php the_search_query(); ?> returned <strong><?php printf( _n( '%s result', '%s results', $wp_query->found_posts ), number_format_i18n( $wp_query->found_posts ) ); ?> results</strong></h1>
				</header>

				<?php
				/* Start the Loop */
				while ( have_posts() ) : the_post();
					get_template_part( 'content', 'search' );
				endwhile;
    			argo_pagination(); ?>
				<!-- /.search-pagination -->

			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title">Nothing Found</h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p>Sorry, but nothing matched your search criteria. Please try again with some different keywords.</p>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>

			</div><!--/.grid_8 #content-->

<div id="sidebar" class="span4">
<?php get_sidebar(); ?>
</div><!-- /.grid_4 -->
<?php get_footer(); ?>