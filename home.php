<?php get_header(); ?>

			<div id="content" class="stories span8" role="main">

			<?php
				global $ids;
				$ids = array();

				// get the optional homepage top section (if set)
				if (of_get_option('homepage_layout') == 'topstories') {
					get_template_part( 'home-part-topstories' );
				} else if (of_get_option('homepage_layout') == 'slider') {
					get_template_part( 'home-part-slider' );
				}

				// now for the rest of the homepage content
				if ( have_posts() ) {
					while ( have_posts() ) : the_post();
						if ( is_sticky() && ! is_paged() ) { ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix sticky '); ?>>
						<?php if ( largo_post_in_series() ):
							$feature = largo_get_the_main_feature();
							$feature_posts = largo_get_recent_posts_for_term( $feature, 3, 1 );
							if ( $feature_posts ):
						?>
							<div class="sticky-related row-fluid clearfix">
								<div class="sticky-main-feature span8">
						<?php else: // feature_posts ?>
							<div class="sticky-solo row-fluid clearfix">
								<div class="sticky-main-feature span12">
						<?php endif; // feature_posts
						else: // largo_post_has_features ?>
							<div class="sticky-solo row-fluid clearfix">
								<div class="sticky-main-feature span12">
						<?php endif; // largo_post_has_features(); ?>


									<?php if ( has_post_thumbnail() ): ?>
										<div class="image-wrap">
											<h4>FEATURED</h4>
											<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
										</div>
									<?php else: ?>
										<h4 class="no-image">FEATURED</h4>
									<?php endif; ?>
										<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
										<?php largo_excerpt( $post, 4, '' ); ?>
										<?php $ids[] = get_the_ID(); ?>
								</div>
								<?php if ( $feature_posts ): ?>
								<div class="sticky-features-list span4">
									<ul>
										<li><h4>More from<br /><span class="series-name"><?php echo $feature->name; ?></span></h4></li>
										<?php foreach ( $feature_posts as $feature_post ): ?>
											<li><a href="<?php echo esc_url( get_permalink( $feature_post->ID ) ); ?>"><?php echo get_the_title( $feature_post->ID ); ?></a></li>
										<?php endforeach; ?>
										<?php if ( count( $feature_posts ) == 3 ): ?>
											<li class="sticky-all"><a href="<?php echo esc_url( get_term_link( $feature ) ); ?>">Full coverage <span class="meta-nav">&rarr;</span></a></li>
										<?php endif; ?>
									</ul>
								</div>
								<?php endif; ?>
							</div>

					<?php } else if (in_array(get_the_ID(),$ids)) {
							continue;
						} else {
							$ids[] = get_the_ID();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
						<header>
				 			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				 			<div class="post-meta">
				 				<h5 class="byline"><?php largo_byline(); ?><?php edit_post_link('Edit This Post', ' | <span class="edit-link">', '</span>'); ?></h5>
				 			</div>
						</header><!-- / entry header -->

						<div class="entry-content">
							<?php largo_excerpt( $post, 5 ); ?>
				        	<?php if ( largo_has_categories_or_tags() ): ?>
				            	<div class="post-meta bottom-meta">
				    				 <h5><strong>Filed under:</strong> <?php echo largo_homepage_categories_and_tags(); ?></h5>
				            	</div><!-- /.post-meta -->
				            <?php endif; ?>
						</div><!-- .entry-content -->

					<?php } //non-sticky posts ?>
					</article><!-- #post-<?php the_ID(); ?> -->
			<?php endwhile;
				largo_content_nav( 'nav-below' );
			 } else { ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title">Nothing Found</h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p>Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.</p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php } ?>

			</div><!--/.grid_8 #content-->

			<div id="sidebar" class="span4">
				<?php get_sidebar(); ?>
			</div><!-- /.grid_4 -->
<?php get_footer(); ?>