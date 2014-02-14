<?php
/**
 * Template for category archive pages
 */
get_header();
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

					printf(__('<a class="rss-link rss-subscribe-link" href="%1$s">Subscribe <i class="icon-rss"></i></a>', 'largo'), $rss_link );

					if ( $title)
						echo '<h1 class="page-title">' . $title . '</h1>';
					if ( $description )
						echo '<div class="archive-description">' . $description . '</div>';

					// category pages show a list of related terms
					if ( is_category() && largo_get_related_topics_for_category( get_queried_object() ) != '<ul></ul>' ) { ?>
						<div class="related-topics">
							<h5><?php _e('Related Topics:', 'largo'); ?> </h5>
							<?php echo largo_get_related_topics_for_category( get_queried_object() ); ?>
						</div>
				<?php
					}
				?>
			</header>


		<?php
			if ( $paged < 2 ) {
				?>
				<div class="primary-featured-post">
					<?php get_template_part( 'content', 'primary-featured' ); ?>
				</div>
				<div class="secondary-featured-post">
					<div class="row-fluid clearfix"><?php

				for ($i = 1; $i < 5; $i++) {
					the_post();
					get_template_part( 'content', 'secondary-featured' );
				}

			}
		?>
					</div></div>
	<div class="row-fluid clearfix">
		<div class="stories span8" role="main" id="content">
		<?php
				// and finally  go through the loop as usual

				while ( have_posts() ) : the_post();
					get_template_part( 'content', 'archive' );
				endwhile;

				largo_content_nav( 'nav-below' );
			} else {
				get_template_part( 'content', 'not-found' );
			}
		?>
		</div><!--#content-->

		<?php get_sidebar(); ?>
	</div>

</div>

<?php get_footer(); ?>