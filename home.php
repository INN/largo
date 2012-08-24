<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 */

get_header(); ?>

			<div id="content" class="stories span8" role="main">

			<?php
				global $ids;
				$ids = array();
			?>

			<?php if (of_get_option('homepage_layout') == 'newsy') { ?>
				<div id="homepage-featured" class="row-fluid clearfix">

					<div class="top-story span8">
						<?php $topstory = largo_get_featured_posts( array(
							'tax_query' => array(
								array(
									'taxonomy' => 'prominence',
									'field' => 'slug',
									'terms' => 'top-story'
								)
							),
							'showposts' => 1
							) );
			          	if ( $topstory->have_posts() ) : ?>
			             	 <?php while ( $topstory->have_posts() ) : $topstory->the_post(); $ids[] = get_the_ID(); ?>
			                 	<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'large' ); ?></a>
			                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			                    <h5 class="byline"><?php largo_byline(); ?><?php edit_post_link('Edit This Post', ' | <span class="edit-link">', '</span>'); ?></h5>
			                    <?php the_excerpt(); ?>

			                    <?php if ( largo_post_in_series() ):
									$feature = largo_get_the_main_feature();
									$feature_posts = largo_get_recent_posts_for_term( $feature, 1, 1 );
									if ( $feature_posts ):
								?>
									<?php foreach ( $feature_posts as $feature_post ): ?>
											<h4 class="related-story">RELATED: <a href="<?php echo esc_url( get_permalink( $feature_post->ID ) ); ?>"><?php echo get_the_title( $feature_post->ID ); ?></a></h4>
									<?php endforeach;
									endif;
								endif;
			                endwhile;
			            endif; // end more featured posts ?>
					</div>
					<div class="sub-stories span4">
						<?php $substories = largo_get_featured_posts( array(
							'tax_query' => array(
								array(
									'taxonomy' => 'prominence',
									'field' => 'slug',
									'terms' => 'homepage-featured'
								)
							),
							'showposts'		=> 6,
							'post__not_in' 	=> $ids
							) );
			          	if ( $substories->have_posts() ) : ?>
			             	 <?php $count = 1; ?>
			             	 <?php while ( $substories->have_posts() ) : $substories->the_post(); $ids[] = get_the_ID(); ?>

			                      	<?php if ($count <= 3) : ?>
			                      		<div class="story">
			                      			<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			                      			<?php the_excerpt(); ?>
			                      		</div>
			                      	<?php elseif ($count == 4) : ?>
			                      		<h4 class="subhead">More Headlines</h4>
			                      		<h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
			                      	<?php else : ?>
			                      		<h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
			                      	<?php endif; ?>

			                  	<?php $count++; ?>
				            <?php endwhile; ?>
				    	<?php endif; // end more featured posts ?>
					</div>
				</div>
			<?php } else if (of_get_option('homepage_layout') == 'slider') { ?>
				<div id="homepage-slider" class="carousel slide clearfix">
				 	<!-- Carousel items -->
				 	<div class="carousel-inner">
					 	<div class="top-story item active">
							<?php $topstory = largo_get_featured_posts( array(
								'tax_query' => array(
									array(
										'taxonomy' => 'prominence',
										'field' => 'slug',
										'terms' => 'top-story'
									)
								),
								'showposts' => 1
								) );
				          	if ( $topstory->have_posts() ) : ?>
				             	 <?php while ( $topstory->have_posts() ) : $topstory->the_post(); $ids[] = get_the_ID(); ?>
				                 	<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
				                    <div class="carousel-caption">
				                    	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				                    	<h5 class="byline"><?php largo_byline(); ?><?php edit_post_link('Edit This Post', ' | <span class="edit-link">', '</span>'); ?></h5>
				                    	<?php the_excerpt(); ?>
				                    </div>
				                <?php endwhile;
				            endif; // end more featured posts ?>
						</div>
						<?php $substories = largo_get_featured_posts( array(
							'tax_query' => array(
								array(
									'taxonomy' => 'prominence',
									'field' => 'slug',
									'terms' => 'homepage-featured'
								)
							),
							'showposts'		=> 4,
							'post__not_in' 	=> $ids
							) );
			          	if ( $substories->have_posts() ) :
			          		while ( $substories->have_posts() ) : $substories->the_post(); $ids[] = get_the_ID(); ?>
					          	<div class="item">
					          		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
						            <div class="carousel-caption">
						            	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						                <h5 class="byline"><?php largo_byline(); ?><?php edit_post_link('Edit This Post', ' | <span class="edit-link">', '</span>'); ?></h5>
						                <?php the_excerpt(); ?>
						            </div>
							 	</div>
							 <?php endwhile; ?>
				    	<?php endif; ?>
				 	</div>
				 	<!-- Carousel nav -->
				 	<a class="carousel-control left" href="#homepage-slider" data-slide="prev">&lsaquo;</a>
				 	<a class="carousel-control right" href="#homepage-slider" data-slide="next">&rsaquo;</a>
				</div>
			<?php } ?>

			<?php if ( have_posts() ) {
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
										<?php the_excerpt(); ?>
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
							<?php if ( is_search() || is_archive() ) : // Only display Excerpts for Search ?>
								<?php the_excerpt(); ?>
							<?php else : ?>
				        		<?php the_content( 'Continue reading <span class="meta-nav">&rarr;</span>' ); ?>
				        	<?php endif; ?>

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