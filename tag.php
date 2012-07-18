<?php
/**
 * The template used to display Tag Archive pages
 */

get_header(); ?>

		<div id="content" class="stories span8" role="main">

		<?php if ( have_posts() ) : ?>
			<header class="category-background">

				<h1 class="page-title"><?php single_tag_title(); ?></h1>

				<?php
					$tag_description = tag_description();
					if ( $tag_description )
						echo '<div class="topic-background">' . $tag_description . '</div>';
				?>

			</header>

			<h3 class="recent-posts">Recent posts<a class="rss-link" href="<?php echo esc_url( get_tag_feed_link( get_queried_object_id() ) ); ?>"></a></h3>

			<?php
				/* Start the Loop */
				while ( have_posts() ) : the_post();
					get_template_part( 'content', 'tag' );
				endwhile;
				argo_content_nav( 'nav-below' );

			else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title">Nothing Found></h1>
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
<!-- /====== #ARGO add tag sidebar ========== -->
</aside>
<!-- /.grid_4 -->
<?php get_footer(); ?>
