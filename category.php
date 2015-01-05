<?php
/**
 * Template for category archive pages
 */
get_header();
global $tags;
?>

<div class="clearfix">

		<?php if ( have_posts() ) {

			// queue up the first post so we know what type of archive page we're dealing with
			the_post();
			$title = single_cat_title( '', false );
			$description = category_description();
			$rss_link =  get_category_feed_link( get_queried_object_id() );
			$posts_term = of_get_option( 'posts_term_plural', 'Stories' );
		?>
			<header class="archive-background clearfix">
				<?php

					/*
					 * Show a label for the list of recent posts
					 * again, tailored to the type of page we're looking at
					 */

					printf( '<a class="rss-link rss-subscribe-link" href="%1$s">%2$s <i class="icon-rss"></i></a>', $rss_link, __( 'Subscribe', 'largo' ) );

					echo '<h1 class="page-title">' . $title . '</h1>';
					echo '<div class="archive-description">' . $description . '</div>';

					// category pages show a list of related terms
					if ( defined('SHOW_CATEGORY_RELATED_TOPICS') && SHOW_CATEGORY_RELATED_TOPICS ) {
						if ( is_category() && largo_get_related_topics_for_category( get_queried_object() ) != '<ul></ul>' ) { ?>
							<div class="related-topics">
								<h5><?php _e('Related Topics:', 'largo'); ?> </h5>
								<?php echo largo_get_related_topics_for_category( get_queried_object() ); ?>
							</div>
					<?php
						}
					}
				?>
			</header>


		<?php
			if ( $paged < 2 ) {
				?>
				<div class="primary-featured-post">
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix row-fluid'); ?>>
					<?php if ( has_post_thumbnail() ) { ?>
						<div class="span4">
							<?php if ( is_home() && largo_has_categories_or_tags() && $tags === 'top' ) { ?>
						 		<h5 class="top-tag"><?php largo_categories_and_tags( 1 ); ?></h5>
						 	<?php } ?>

							<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'rect_thumb' ); ?></a>
						</div>

						<div class="span8">
					<?php } else { ?>
						<div class="span12">
					<?php } ?>
							<header>
						 		<h2 class="entry-title">
						 			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute( array( 'before' => __( 'Permalink to', 'largo' ) . ' ' ) )?>" rel="bookmark"><?php the_title(); ?></a>
						 		</h2>

						 		<h5 class="byline"><?php largo_byline(); ?></h5>
							</header><!-- / entry header -->

							<div class="entry-content">
								<?php largo_excerpt( $post, 5, true, '', true, false ); ?>
							</div><!-- .entry-content -->
						</div>
					</article><!-- #post-<?php the_ID(); ?> -->
				</div>
				<div class="secondary-featured-post">
					<div class="row-fluid clearfix"><?php

				for ($i = 1; $i < 5; $i++) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class('span3'); ?>>
						<?php if ( is_home() && largo_has_categories_or_tags() && $tags === 'top' ) { ?>
					 		<h5 class="top-tag"><?php largo_categories_and_tags( 1 ); ?></h5>
					 	<?php } ?>

						<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'rect_thumb' ); ?></a>

							<h2 class="entry-title">
								<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute( array( 'before' => __( 'Permalink to', 'largo' ) . ' ' ) )?>" rel="bookmark"><?php the_title(); ?></a>
							</h2>
					</article><!-- #post-<?php the_ID(); ?> -->
				<?php
				endfor;
			}
		?>
					</div></div>
	<div class="row-fluid clearfix">
		<div class="stories span8" role="main" id="content">
		<?php
				// and finally  go through the loop as usual

				while ( have_posts() ) : the_post();
					get_template_part( 'partials/content', 'archive' );
				endwhile;

				largo_content_nav( 'nav-below' );
			} else {
				get_template_part( 'partials/content', 'not-found' );
			}
		?>
		</div><!--#content-->

		<?php get_sidebar(); ?>
	</div>

</div>

<?php get_footer();
