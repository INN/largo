<?php
/**
 * Home Template: Single
 * Description: Prominently features the top story by itself
 * Sidebars: Home Bottom Left | Home Bottom Center | Home Bottom Right
 * Right Rail: none
 */

global $largo, $shown_ids, $tags;

?>
<div id="homepage-featured" class="row-fluid clearfix">
	<div class="home-single span12">
		<aside id="view-format">
			<?php // @todo Make this check the cookie server-side ?>
			<h1><?php _e('View', 'largo'); ?></h1>
			<ul>
				<li><a href="#" class="active" data-style="top">Top Stories</a></li>
				<li><a href="#" data-style="list">List</a></li>
		</aside>

		<div class="home-top">
	<?php

		$big_story = largo_home_single_top();


		if( $has_video = get_post_meta( $big_story->ID, 'youtube_url', true ) ): ?>
			<div class="embed-container max-wide">
				<iframe src="http://www.youtube.com/embed/<?php echo substr(strrchr( $has_video, "="), 1 ); ?>?modestbranding=1" frameborder="0" allowfullscreen></iframe>
			</div>
		<?php else: ?>
			<div class="full-hero"><a href="<?php echo esc_attr( get_permalink( $big_story->ID ) ); ?>"><?php echo get_the_post_thumbnail( $big_story->ID, 'full' ); ?></a></div>
		<?php endif; ?>

		<div id="dark-top" <?php echo (!$has_video) ? 'class="overlay"' : ''; ?>>
			<div class="span10">
				<div class="row-fluid">
					<article class="">
						<h5 class="top-tag"><?php largo_top_term( array('post'=>$big_story->ID) ); ?></h5>
						<h2><a href="<?php echo esc_attr( get_permalink( $big_story->ID ) ); ?>"><?php echo get_the_title( $big_story->ID ); ?></a></h2>
						<h5 class="byline"><?php _e('By'); ?> <?php largo_author_link( true, $big_story ); ?></h5>
						<section>
							<?php largo_excerpt( $big_story, 2, false ); ?>
						</section>
					</article>
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