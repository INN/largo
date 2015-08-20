<?php

$bigStoryPost = largo_home_single_top();
largo_mark_post_shown($bigStoryPost->ID); //don't repeat the current post

?>
<div id="homepage-featured" class="row-fluid clearfix">
	<div class="home-single span12">
		<?php echo $viewToggle; // View toggle zone ?>
		<div class="home-top">

			<div <?php post_class( 'full-hero' ); ?>>
				<a href="<?php echo esc_attr(get_permalink($bigStoryPost->ID)); ?>">
					<?php echo get_the_post_thumbnail($bigStoryPost->ID, 'full'); ?>
				</a>
			</div>

			<div id="dark-top" class="overlay">
				<div class="span10">
					<div class="row-fluid">
					<?php if (!empty($moreStories)) { ?>
						<div class="span8">
							<?php echo $bigStory; // Big story zone ?>
						</div>
							<div class="span4 side-<?php echo $templateType; ?>">
							<?php echo $moreStories; // More stories zone (i.e. series or featured) ?>
						</div>
					<?php } else { ?>
						<?php echo $bigStory; // Big story zone ?>
					<?php } ?>
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
		<h1><?php _e('Latest Stories', 'largo'); ?></h1>
		<?php
			//start at the beginning of the list
			rewind_posts();
			while (have_posts()) {
				the_post();
				get_template_part('partials/content', 'river');
			}
			largo_content_nav('nav-below');
		?>
	</div>
</div>
