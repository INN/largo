<?php
global $shown_ids;

$sticky = get_option( 'sticky_posts' );
if (empty($sticky))
	return;

$args = array(
	'posts_per_page' => 1,
	'post__in'  => $sticky,
	'ignore_sticky_posts' => 1
);
$query = new WP_Query( $args );

if ( $query->have_posts() ) {
	while ( $query->have_posts() ) {
	   	$query->the_post();
	   	$shown_ids[] = get_the_ID();

		if ( $sticky && $sticky[0] && ! is_paged() ) { ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix sticky entry-content '); ?>>

			<?php if ( largo_post_in_series() ) {
				// if the sticky post is part of a series, see if there are any other posts in that series
				$feature = largo_get_the_main_feature();
				$feature_posts = largo_get_recent_posts_for_term( $feature, 3, 1 );
			} ?>

			<div class="sticky-solo">

				<h5 class="top-tag"><?php _e('Featured', 'largo'); ?></h5>

				<div class="sticky-main-feature row-fluid">


					<?php // if we have a thumbnail image, show it
					if ( has_post_thumbnail() ) { ?>
						<div class="image-wrap span3 hidden-phone <?php largo_hero_class(get_the_ID()); ?>">
							<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
						</div>
					<?php } // end thumbnail ?>

					<div class="<?php echo (has_post_thumbnail())? "span9" : "span12"; ?>">

						<?php if ( has_post_thumbnail() ) { ?>
							<div class="image-wrap visible-phone <?php largo_hero_class(get_the_ID()); ?>">
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
							</div>
						<?php } ?>

						<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<h5 class="byline"><?php largo_byline(); ?></h5>

						<div class="entry-content">
						<?php
							largo_excerpt( $post, 2, false );
							$shown_ids[] = get_the_ID();

						if ( $feature_posts ) { //if the sticky post is in a series, show up to 3 other posts in that series ?>
							<div class="sticky-features-list">
								<h4><?php _e('More from', 'largo'); ?> <span class="series-name"><?php echo esc_html( $feature->name ); ?></span></h4>
								<ul>
									<?php
										foreach ( $feature_posts as $feature_post ):
											printf( '<li><a href="%1$s">%2$s</a></li>',
												esc_url( get_permalink( $feature_post->ID ) ),
												esc_attr( get_the_title( $feature_post->ID ) )
											);
										endforeach;
									?>
								</ul>
								<?php
								if ( count( $feature_posts ) == 3 )
											printf( '<p class="sticky-all"><a href="%1$s">%2$s &raquo;</a></p>',
												esc_url( get_term_link( $feature ) ),
												__( 'Full Coverage', 'largo' )
											);
								?>
							</div>
						<?php } // feature_posts ?>
						</div>
					</div>
				</div> <!-- end sticky-main-feature -->
			</div> <!-- end sticky-solo or sticky-related -->
		</article>
	<?php } // is_paged
	}
}
