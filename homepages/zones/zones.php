<?php

/**
 * Returns markup for the homepage view toggle
 **/
function homepage_view_toggle() {
	ob_start();
?>
	<aside id="view-format">
		<h1><?php _e('View', 'largo'); ?></h1>
		<ul>
			<li><a href="#" class="active" data-style="top">Top Stories</a></li>
			<li><a href="#" data-style="list">Recent stories</a></li>
	</aside>
<?php
	$ret = ob_get_contents();
	ob_end_clean();

	return $ret;
}

/**
 * Returns markup for the headline of the top story
 **/
function homepage_big_story_headline($moreLink=false) {
	$bigStoryPost = largo_home_single_top();
	ob_start();
?>
	<article>
		<h5 class="top-tag"><?php largo_top_term(array('post'=> $bigStoryPost->ID)); ?></h5>
		<h2><a href="<?php echo get_permalink($bigStoryPost->ID); ?>"><?php echo $bigStoryPost->post_title; ?></a></h2>
		<h5 class="byline"><?php largo_byline(true, true, $bigStoryPost); ?></h5>
		<section>
			<?php if (empty($moreLink)) {
					largo_excerpt($bigStoryPost, 2, false);
				} else {
					largo_excerpt($bigStoryPost, 2, true, __('Continue&nbsp;Reading&nbsp;&rarr;', 'largo'), true, false);
				} ?>
		</section>
	</article>
<?php
	wp_reset_postdata();
	$ret = ob_get_contents();
	ob_end_clean();
	return $ret;
}

/**
 * Returns a short list (3 posts) of stories in the same series as the main feature
 **/
function homepage_series_stories_list() {
	$feature = largo_get_the_main_feature(largo_home_single_top());

	$min = 2;
	$max = 3;

	/**
	 * Filter the minimum number of posts to show in a series list in the
	 * HomepageSingleWithSeriesStories homepage list.
	 *
	 * This is used in the query for the series list of posts in the same series
	 * as the main feature. If fewer than this number of posts exist, the list
	 * is hidden and the headline dominates the full box.
	 * 
	 * Default value is 2.
	 *
	 * @since 0.5.1
	 *
	 * @param int  $var minimum number of posts that can show.
	 */
	$min = apply_filters('largo_homepage_series_stories_list_minimum',$min);


	/**
	 * Filter the maximum number of posts to show in a series list in the
	 * HomepageSingleWithSeriesStories homepage list.
	 *
	 * This is used in the query for the series list of posts in the same series
	 * as the main feature. This is the maximum number of posts that will display
	 * in the list.
	 * 
	 * Default value is 3.
	 *
	 * @since 0.5.1
	 *
	 * @param int  $var minimum number of posts that can show.
	 */
	$max = apply_filters('largo_homepage_series_stories_list_maximum',$max);

	$series_posts = largo_get_recent_posts_for_term($feature, $max, $min);

	ob_start();
	if (!empty($feature)) {
?>
	<h5 class="top-tag"><a class="post-category-link" href="<?php echo get_term_link($feature); ?>">
		<?php echo __("More in", "largo") . " " . esc_html($feature->name) ?></a></h5>
			<?php foreach ($series_posts as $series_post) {
				largo_mark_post_shown($series_post->ID); ?>
				<h4 class="related-story"><a href="<?php echo esc_url(get_permalink($series_post->ID)); ?>">
					<?php echo get_the_title($series_post->ID); ?></a></h4>
			<?php } ?>
			<p class="more"><a href="<?php echo get_term_link($feature); ?>">
				<?php _e('Complete Coverage', 'largo'); ?></a></p>
<?php
	}
	wp_reset_postdata();
	$ret = ob_get_contents();
	ob_end_clean();
	return $ret;
}

function homepage_feature_stories_list() {
	$max = 3;

	/**
	 * Filter the maximum number of posts to show in the featured stories list
	 * on the HomepageSingleWithFeatured homepage template.
	 *
	 * This is used in the query for the series list of posts in the same series
	 * as the main feature. This is the maximum number of posts that will display
	 * in the list.
	 * 
	 * Default value is 3.
	 *
	 * @since 0.5.1
	 *
	 * @param int  $var minimum number of posts that can show.
	 */
	$max = apply_filters('largo_homepage_feature_stories_list_maximum',$max);

	ob_start();
	$featured_stories = largo_home_featured_stories($max);
	foreach ($featured_stories as $featured) {
		largo_mark_post_shown($featured->ID);
?>
		<article class="featured-story">
			<h5 class="top-tag"><?php largo_top_term('post=' . $featured->ID); ?></h5>
			<h4 class="related-story"><a href="<?php echo esc_url(get_permalink($featured->ID)); ?>">
				<?php echo $featured->post_title; ?></a></h4>
		</article>
<?php
	}
	$ret = ob_get_contents();
	ob_end_clean();
	return $ret;
}
