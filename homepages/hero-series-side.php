<?php
/**
 * Home Template: Hero on the side with Series
 * Description: Prominently features the top story along with other posts in its series along the right side
 * Sidebars: Home Bottom Left | Home Bottom Center | Home Bottom Right
 * Right Rail: none
 */

global $largo, $shown_ids, $tags;

?>
<div id="homepage-featured" class="row-fluid clearfix">
	<div class="hero-series-side span12">
		<aside id="view-format">
			<?php // @todo Make this check the cookie server-side ?>
			<h1><?php _e('View', 'largo'); ?></h1>
			<ul>
				<li><a href="#" class="active" data-style="top">Top Stories</a></li>
				<li><a href="#" data-style="list">List</a></li>
		</aside>

		<div class="home-top">
	<?php

		$post_states = largo_home_hero_side_series();
		if ( empty( $post_states ) ) {
			$big_story = null;
			$series_stories = array();
			$featured_stories = array();
			$series_stories_term = null;
		} else {
			extract( $post_states ); // $big_story, $series_stories, $featured_stories, $series_stories_term
		}

		if( $has_video = get_post_meta( $big_story->ID, 'youtube_url', true ) ): ?>
			<div class="embed-container max-wide">
				<iframe src="http://www.youtube.com/embed/<?php echo substr(strrchr( $has_video, "="), 1 ); ?>?modestbranding=1" frameborder="0" allowfullscreen></iframe>
			</div>
		<?php else: ?>
			<div class="full-hero <?php echo empty( $featured_stories ) ? 'two-third-width' : 'one-third-width'; ?>"><a href="<?php echo esc_attr( get_permalink( $big_story->ID ) ); ?>"><?php echo get_the_post_thumbnail( $big_story->ID, ( empty( $featured_stories ) ? 'two-third-full' : 'third-full') ); ?></a></div>
		<?php endif; ?>

		<div id="dark-top" <?php echo (!$has_video) ? 'class="overlay"' : ''; ?>>
			<div class="overlay-pane <?php echo empty( $featured_stories ) ? 'span4' : 'span8' ?>">
				<div class="row-fluid">
					<div class="<?php echo empty( $featured_stories ) ? 'span12' : 'span6' ?>">
						<article>
							<h5 class="top-tag"><?php largo_top_term( array('post'=>$big_story->ID) ); ?></h5>
							<h2><a href="<?php echo esc_attr( get_permalink( $big_story->ID ) ); ?>"><?php echo get_the_title( $big_story->ID ); ?></a></h2>
							<h5 class="byline"><?php _e('By'); ?> <?php largo_author_link( true, $big_story ); ?></h5>
							<section>
								<?php largo_excerpt( $big_story, 2, true, __('Continue&nbsp;Reading&nbsp;&rarr;', 'largo'), true, false ); ?>
							</section>
						</article>

						<?php if ( !empty($series_stories) ): ?>
						<div class="series-stories">
							<h3><a href="<?php echo get_term_link( $series_stories_term ); ?>"><?php _e('Explore:', 'largo'); ?></a></h3>

							<ul>
							<?php foreach ( $series_stories as $series_story ): ?>
							<li>
								<a href="<?php echo get_permalink( $series_story->ID ); ?>"><?php post_type_icon( array( 'id' => $series_story->ID ) ); ?><?php echo get_the_title( $series_story->ID ); ?></a></h4>
							</li>
							<?php endforeach; ?>
							</ul>
						</div>
						<?php endif; ?>
					</div>

					<?php if ( !empty( $featured_stories ) ): ?>
					<div class="span6 featured-stories">
						<?php foreach ( $featured_stories as $featured_story ): setup_postdata($featured_story); ?>
						<article>
							<h5 class="top-tag"><?php largo_top_term( array( 'post' => $featured_story->ID ) ); ?></h5>
							<h4><a href="<?php echo get_permalink( $featured_story->ID ); ?>"><?php echo get_the_title( $featured_story->ID ); ?></a></h4>
							<h5 class="byline"><?php _e('By'); ?> <?php largo_author_link( true, $featured_story ); ?></h5>
							<section>
								<?php largo_excerpt( $featured_story, 2, true ); ?>
							</section>
						</article>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
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
				get_template_part( 'content', 'home' );
			endwhile;

			largo_content_nav( 'nav-below' );
	?>
	</div>
</div>