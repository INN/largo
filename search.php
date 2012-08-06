<?php
/**
 * The template for displaying Search Results pages.
 */

get_header(); ?>

		<div id="content" class="stories search-results span8" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="clearfix">
					<form class="form-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<div>
							<input type="text" placeholder="Search" class="searchbox search-query" value="<?php the_search_query(); ?>" name="s" />
							<input type="submit" value="GO" name="search submit" class="search-submit btn">
						</div>
					</form>
				</header>

				<h3 class="recent-posts clearfix">Your search for <span class="search-term"><?php the_search_query(); ?></span> returned <?php printf( _n( '%s result', '%s results', $wp_query->found_posts ), number_format_i18n( $wp_query->found_posts ) ); ?></h3>

				<?php
				/* Start the Loop */
				while ( have_posts() ) : the_post();
					get_template_part( 'content', 'search' );
				endwhile;
    			largo_content_nav( 'nav-below' ); ?>

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