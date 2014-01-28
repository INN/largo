<?php
/**
 * Home Template: Hero with Series
 * Description: Prominently features the top story along with other posts in its series
 * Sidebars: Home Bottom Left | Home Bottom Center | Home Bottom Right
 * Right Rail: none
 */

global $largo, $shown_ids, $tags;
?>
<div id="homepage-featured" class="row-fluid clearfix">
	<div class="hero-series span12">
		<aside id="view-format">
			<?php // @todo Make this check the cookie server-side ?>
			<h1><?php _e('View', 'largo'); ?></h1>
			<ul>
				<li><a href="#" class="active" data-style="top">Top Stories</a></li>
				<li><a href="#" data-style="list">List</a></li>
		</aside>

		<div class="home-top">
	<?php
		$topstory = largo_get_featured_posts( array(
			'tax_query' => array(
				array(
					'taxonomy' 	=> 'prominence',
					'field' 	=> 'slug',
					'terms' 	=> 'top-story'
				)
			),
			'showposts' => 1
		) );
		if ( $topstory->have_posts() ) : while ( $topstory->have_posts() ) :
				$topstory->the_post();
				$shown_ids[] = get_the_ID();
				if( $has_video = get_post_meta( $post->ID, 'youtube_url', true ) ) { ?>
					<div class="embed-container max-wide">
						<iframe src="http://www.youtube.com/embed/<?php echo substr(strrchr( $has_video, "="), 1 ); ?>?modestbranding=1" frameborder="0" allowfullscreen></iframe>
					</div>
				<?php } else { ?>
					<div class="full-hero max-wide"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'full' ); ?></a></div>
				<?php } ?>
				<div id="dark-top" <?php echo (!$has_video) ? 'class="overlay"' : ''; ?>>
					<div class="span10">
						<div class="row-fluid">
							<article class="span8">
								<h5 class="top-tag"><?php largo_top_term(); ?></h5>
								<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<h5 class="byline"><?php _e('By'); ?> <?php largo_author_link(); ?></h5>
								<section>
									<?php largo_excerpt( $post, 2, false ); ?>
								</section>
							</article>
							<div class="span4">
							<?php
								if ( largo_post_in_series() ):
									$feature = largo_get_the_main_feature();
									$feature_posts = largo_get_recent_posts_for_term( $feature, 3, 2 );
									echo "<!-- ";
									print_r($feature);
									print_r($feature_posts);
									echo "-->";
									if ( $feature_posts ):
										?>
										<h5 class="top-tag"><a class="post-category-link" href="<?php echo get_term_link( $feature ); ?>"><?php echo $feature->name ?></a></h5>
										<?php foreach ( $feature_posts as $feature_post ):
											$shown_ids[] = $feature_post->ID;
										?>
											<h4 class="related-story"><a href="<?php echo esc_url( get_permalink( $feature_post->ID ) ); ?>"><?php echo get_the_title( $feature_post->ID ); ?></a></h4>
										<?php endforeach; ?>
										<h6 class="more"><a href="<?php echo get_term_link( $feature ); ?>"><?php _e('Complete Coverage', 'largo'); ?></a></h6>
									<?php
									endif;
								endif; ?>

							<?php
								// if we didn't get any series posts
								if ( count($shown_ids) == 1 ) :

								  $query_args = array(
						        'showposts' 			=> 3,
						        'orderby' 				=> 'date',
						        'order' 				=> 'DESC',
						        'ignore_sticky_posts' 	=> 1,
						        'post__not_in' => $shown_ids,
						        'prominence' => 'homepage-featured'
							    );

							    $featured = new WP_Query( $query_args );
							    if ( $featured->have_posts() ) : while ( $featured->have_posts() ) :
							    	$featured->next_post();
							    	?>
							    	<div class="featured-story">
								    	<h5 class="top-tag"><?php largo_top_term( 'post='.$featured->post->ID ); ?></h5>
								    	<h4 class="related-story"><a href="<?php echo esc_url( get_permalink( $featured->post->ID ) ); ?>"><?php echo get_the_title( $featured->post->ID ); ?></a></h4>
								    	<?php /*
								    	<h5 class="byline"><?php _e('By'); ?> <?php largo_author_link(); ?></h5>
								    	*/ ?>
							    	</div>
							    <?php
							    endwhile; endif;

								endif;
								?>
							</div>
						</div>
					</div>
		<?php
		endwhile; endif; ?>
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
				get_template_part( 'content', 'home' );
			endwhile;

			largo_content_nav( 'nav-below' );
	?>
	</div>
</div>