<?php
/*
 * Set $wp_query so that `next_posts_link` works properly
 */
global $wp_query;
$original_query = $wp_query;
$wp_query = $the_query;
?>
<nav id="<?php echo $nav_id; ?>" class="pager post-nav">
	<div class="load-more">
		<?php $label = apply_filters('largo_next_posts_link', __('Load more ', 'largo') . strtolower($posts_term)); ?>
		<?php next_posts_link($label); ?>
	</div>
	<?php largo_load_more_posts_data($nav_id, $wp_query); ?>
</nav>
<?php
/*
 * Restore $wp_query
 */
$wp_query = $original_query;
