<?php
/**
 * The template for displaying Author pages.
 */

get_header(); ?>

		<div id="content" class="stories author-page span8" role="main">

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

		<div class="author-box clearfix">
			<h1><?php echo esc_attr( get_the_author() ); ?></h1>

			<?php if (has_gravatar( get_the_author_meta('user_email') ) ) :
					echo get_avatar( get_the_author_meta('ID'), 96 );
				endif;
			?>

			<?php if ( get_the_author_meta( 'description' ) ) : ?>
				<p><?php the_author_meta( 'description' ); ?></p>
			<?php endif; ?>

			<ul class="social-links">
				<?php if ( get_the_author_meta( 'user_email' ) ) : ?>
				<li class="email">
					<a href="mailto:<?php echo esc_attr( get_the_author_meta( 'user_email' ) ); ?>" title="e-mail <?php echo esc_attr( get_the_author() ); ?>"><i class="icon-envelope icon-white"></i> email</a>
				</li>
				<?php endif; ?>

				<?php if ( get_the_author_meta( 'fb' ) ) : ?>
				<li class="facebook">
					<div class="fb-subscribe" data-href="<?php echo esc_url( get_the_author_meta( 'fb' ) ); ?>" data-layout="button_count" data-show-faces="false" data-width="225"></div>
				</li>
				<?php endif; ?>

				<?php if ( get_the_author_meta( 'twitter' ) ) : ?>
				<li class="twitter">
					<a href="<?php echo esc_url( get_the_author_meta( 'twitter' ) ); ?>" class="twitter-follow-button" data-show-count="false" data-lang="en">Follow @twitterapi</a>
				</li>
				<?php endif; ?>

				<?php if ( get_the_author_meta( 'googleplus' ) ) : ?>
				<li class="gplus">
					<a href="<?php echo esc_url( get_the_author_meta( 'googleplus' ) ); ?>" title="<?php echo esc_attr( get_the_author() ); ?> on Google+" rel="me"><img src="<?php bloginfo( 'template_directory' ); ?>/img/gplus-19.png" alt="Google+" /></a>
				</li>
				<?php endif; ?>
			</ul>
		</div>

		<h3 class="recent-posts clearfix">Recent posts<a class="rss-link" href="<?php echo esc_url( get_author_feed_link( get_the_author_meta('ID') ) ); ?>"></a></h3>

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