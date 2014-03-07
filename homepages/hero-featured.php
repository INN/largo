<?php
/**
 * Home Template: Hero with Featured
 * Description: Prominently features the top story along with three other 'Featured on Homepage' items, or by itself if none are specified. Best with Homepage Bottom set to 'blank'
 * Sidebars: Home Bottom Left | Home Bottom Center | Home Bottom Right
 * Right Rail: none
 */

global $largo, $shown_ids, $tags, $post;

?>
<div id="homepage-featured" class="row-fluid clearfix">
	<div class="hero-featured span12">
		<aside id="view-format">
			<h1><?php _e('View', 'largo'); ?></h1>
			<ul>
				<li><a href="#" class="active" data-style="top">Top Stories</a></li>
				<li><a href="#" data-style="list">List</a></li>
			</ul>
		</aside>

		<div class="home-top">
	<?php

		$post = largo_home_single_top();
		$has_featured = largo_have_homepage_featured_posts();

		if( $has_video = get_post_meta( $big_story->ID, 'youtube_url', true ) ): ?>
			<div class="embed-container max-wide">
				<iframe src="http://www.youtube.com/embed/<?php echo substr(strrchr( $has_video, "="), 1 ); ?>?modestbranding=1" frameborder="0" allowfullscreen></iframe>
			</div>
		<?php else: ?>
			<div class="full-hero max-wide"><a href="<?php echo esc_attr( get_permalink( $big_story->ID ) ); ?>"><?php echo get_the_post_thumbnail( $big_story->ID, 'full' ); ?></a></div>
		<?php endif; ?>

		<div id="dark-top" <?php echo (!$has_video) ? 'class="overlay"' : ''; ?>>
			<div class="span10">
				<div class="row-fluid">

					<article class="<?php if ($has_featured) echo 'span8'; ?>">
						<h5 class="top-tag"><?php largo_top_term( array('post'=>$post->ID) ); ?></h5>
						<h2><a href="<?php the_permalink(); ?>"><?php echo the_title(); ?></a></h2>
						<h5 class="byline"><?php _e('By'); ?> <?php largo_author_link( true, $post ); ?></h5>
						<section>
							<?php largo_excerpt( $post, 2, false ); ?>
						</section>
					</article>

					<?php if ( $has_featured ): ?>
					<div class="span4 side-articles">
						<?php
							$shown_ids[] = $post->ID;	//don't repeat the current post
							$query_args = array(
						  	'showposts' 					=> 3,
						    'orderby' 						=> 'date',
						    'order' 							=> 'DESC',
						    'ignore_sticky_posts' => 1,
						    'post__not_in' 				=> $shown_ids,
						    'prominence' 					=> 'homepage-featured'
							);

							$featured = new WP_Query( $query_args );
							// IF should always be true thanks to previous $has_featured check
							if ( $featured->have_posts() ) : while ( $featured->have_posts() ) :
								$featured->next_post();
							  ?>
							  <article class="featured-story">
									<h5 class="top-tag"><?php largo_top_term( 'post='.$featured->post->ID ); ?></h5>
								  <h4 class="related-story"><a href="<?php echo esc_url( get_permalink( $featured->post->ID ) ); ?>"><?php echo get_the_title( $featured->post->ID ); ?></a></h4>
								</article>
							  <?php
							endwhile; endif;
						endif; // $has_featured
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