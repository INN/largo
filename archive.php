<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 */

get_header(); ?>

		<div id="content" class="stories span8" role="main">

		<?php if ( have_posts() ) : ?>

		<?php
			/* Queue the first post, that way we know
			* what author we're dealing with (if that is the case).
			*
			* We reset this later so we can run the loop
			* properly with a call to rewind_posts().
			*/
			the_post();
		?>

		<div id="archive-intro">
			<?php if ( !is_author() ): ?>
				<nav class="archive-dropdown">
					<select name="archive-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'><option value="">Select Month</option>
					<?php wp_get_archives( array('type' => 'monthly', 'format' => 'option' ) ); ?>
					</select>
				</nav>
			<?php endif; ?>
			<h3>
			<?php if ( is_month() ) : ?>
			<?php printf( 'Monthly Archives: <span>%s</span>', get_the_date('F Y') ); ?>
			<?php elseif ( is_year() ) : ?>
			<?php printf( 'Yearly Archives: <span>%s</span>', get_the_date('Y') ); ?>
			<?php elseif ( is_author() ) : ?>
			<?php printf( 'Author Archives: %s', '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' ); ?>
			<?php else : ?>
			Blog Archives
			<?php endif; ?>
			</h3>

		<?php if ( is_author() && get_the_author_meta( 'description' ) ): ?>
			<p><?php the_author_meta( 'description' ); ?></p>
		<?php endif; ?>
		</div>

				<?php
					/* Since we called the_post() above, we need to
					 * rewind the loop back to the beginning that way
					 * we can run the loop properly, in full.
					 */
					rewind_posts();
				?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'archive' ); ?>

				<?php endwhile; ?>

				<?php argo_content_nav( 'nav-below' ); ?>

			<?php else : ?>

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

			</div><!--/ #content .grid_8-->

<aside id="sidebar" class="span4">
<?php get_sidebar(); ?>
</aside>
<!-- /.grid_4 -->
<?php get_footer(); ?>