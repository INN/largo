<?php
/**
 * Home Template: Hero with Series
 * Description: Prominently features the top story along with other posts in its series, or by itself if not in a series. Best with Homepage Bottom set to 'blank'
 * Sidebars: Home Bottom Left | Home Bottom Center | Home Bottom Right
 * Right Rail: none
 */

global $largo, $shown_ids, $tags, $post;

?>
<div id="homepage-featured" class="row-fluid clearfix">
	<div class="hero-series span12">
		<aside id="view-format">
			<h1><?php _e('View', 'largo'); ?></h1>
			<ul>
				<li><a href="#" class="active" data-style="top"><?php _e( 'Top Stories', 'largo' ); ?></a></li>
				<li><a href="#" data-style="list"><?php _e( 'List', 'largo' ); ?></a></li>
			</ul>
		</aside>

		<div class="home-top">
	<?php

		$post = largo_home_single_top();
		$has_series = largo_post_in_series();

		if( $has_video = get_post_meta( $post->ID, 'youtube_url', true ) ): ?>
			<div class="embed-container max-wide">
				<iframe src="<?php echo esc_url( 'http://www.youtube.com/embed/' . substr(strrchr( $has_video, "="), 1 ) . '?modestbranding=1' ); ?>" frameborder="0" allowfullscreen></iframe>
			</div>
		<?php else: ?>
			<div class="full-hero max-wide"><a href="<?php echo esc_attr( get_permalink( $post->ID ) ); ?>"><?php echo get_the_post_thumbnail( $post->ID, 'full' ); ?></a></div>
		<?php endif; ?>

		<div id="dark-top" <?php echo (!$has_video) ? 'class="overlay"' : ''; ?>>
			<div class="span10">
				<div class="row-fluid">

					<article class="<?php if ($has_series) echo 'span8'; ?>">
						<h5 class="top-tag"><?php largo_top_term( array('post'=>$post->ID) ); ?></h5>
						<h2><a href="<?php the_permalink(); ?>"><?php echo the_title(); ?></a></h2>
						<h5 class="byline"><?php _e( 'By', 'largo' ); ?> <?php largo_author_link( true, $post ); ?></h5>
						<section>
							<?php largo_excerpt( $post, 2, false ); ?>
						</section>
					</article>

					<?php if ( $has_series ):
						$feature = largo_get_the_main_feature();
						$feature_posts = largo_get_recent_posts_for_term( $feature, 3, 2 );
					 ?>
					<div class="span4 side-series">
						<h5 class="top-tag"><a class="post-category-link" href="<?php echo get_term_link( $feature ); ?>"><?php echo esc_html( $feature->name ) ?></a></h5>
						<?php foreach ( $feature_posts as $feature_post ):
							$shown_ids[] = $feature_post->ID; ?>
							<h4 class="related-story"><a href="<?php echo esc_url( get_permalink( $feature_post->ID ) ); ?>"><?php echo get_the_title( $feature_post->ID ); ?></a></h4>
						<?php endforeach; ?>
						<h6 class="more"><a href="<?php echo get_term_link( $feature ); ?>"><?php _e('Complete Coverage', 'largo'); ?></a></h6>
						<?php
						endif; // $has_series
						wp_reset_postdata(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>


<div id="home-secondary" class="row-fluid">
	<div class="span12">
		<div class="row-fluid">
			<div class="span4">
				<?php dynamic_sidebar('home-bottom-left'); ?>
			</div>
			<div class="span4">
				<?php dynamic_sidebar('home-bottom-center'); ?>
			</div>
			<div class="span4">
				<?php dynamic_sidebar('home-bottom-right'); ?>
			</div>
		</div>
	</div>
</div>

<?php // The "river" content view ?>
<div id="home-river" class="row-fluid">
	<div class="span10 offset1">
	<h1><?php _e( 'Latest Stories', 'largo' ); ?></h1>

	<?php
			//start at the beginning of the list
			rewind_posts();

			while ( have_posts() ) : the_post();
				get_template_part( 'content', 'river' );
			endwhile;

			largo_content_nav( 'nav-below' );
	?>
	</div>
</div>