<?php
/**
 * Template for various archive pages (category, tag, term, date, etc.
 */
get_header();
?>

<div class="clearfix">

		<?php if ( have_posts() || largo_have_featured_posts() ) { ?>

		<?php

			// queue up the first post so we know what type of archive page we're dealing with
			the_post();

			/*
			 * Display some different stuff in the header
			 * depending on what type of archive page we're looking at
			 */

			// if it's an author page, show the author box with their bio, social links, etc.
			if ( is_author() ) {
				get_template_part( 'largo-author-box' );

			// for categories, tags, and custom taxonomies we show the term name and description
			} elseif ( is_category() || is_tag() || is_tax() ) {
				if ( is_category() ) {
					$title = single_cat_title( '', false );
					$description = category_description();
				} elseif ( is_tag() ) {
					$title = single_tag_title( '', false );
					$description = tag_description();
				} elseif ( is_tax() ) {
					$title = single_term_title( '', false );
					$description = term_description();
				}
		?>
			<header class="archive-background clearfix">
				<?php

					/*
					 * Show a label for the list of recent posts
					 * again, tailored to the type of page we're looking at
					 */
					$posts_term = of_get_option( 'posts_term_plural', 'Stories' );

					if ( is_author() ) {
						$rss_link =  get_author_feed_link( get_the_author_meta('ID') );
					} elseif ( is_category() ) {
						$rss_link =  get_category_feed_link( get_queried_object_id() );
					} elseif ( is_tag() ) {
						$rss_link =  get_tag_feed_link( get_queried_object_id() );
					} elseif ( is_tax() ) {
						$queried_object = get_queried_object();
						$term_id = intval( $queried_object->term_id );
						$tax = $queried_object->taxonomy;
						$rss_link = get_term_feed_link( $term_id, $tax );
					}

					if ( $rss_link ) {
						printf(__('<a class="rss-link rss-subscribe-link" href="%1$s">Subscribe <i class="icon-rss"></i></a>', 'largo'), $rss_link );
					}

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

		<?php

			// if it's a date archive we'll show the date dropdown in lieu of a description
			} elseif ( is_date() ) {
		?>
					<nav class="archive-dropdown">
						<select name="archive-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'><option value=""><?php _e('Select Month', 'largo'); ?></option>
						<?php wp_get_archives( array('type' => 'monthly', 'format' => 'option' ) ); ?>
						</select>
					</nav>
		<?php
			} // endif
		?>
			</header>


		<?php
			if ( $paged < 2 && ( is_category() || is_tag() || is_tax() ) ) {
				get_template_part( 'index-primary-featured-posts' );
				get_template_part( 'index-secondary-featured-posts' );
			}
		?>
	<div class="row-fluid clearfix">
		<div class="stories span8" role="main" id="content">
		<?php
				// and finally wind the posts back so we can go through the loop as usual
				rewind_posts();

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