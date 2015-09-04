<?php
/**
 * Legacy three column homepage template
 */
?>
<div id="content-main" class="span8">
<?php

	get_template_part('homepages/templates/top-stories');

	// sticky posts box if this site uses it
	if (of_get_option('show_sticky_posts'))
		get_template_part('partials/sticky-posts');

	// bottom section, we'll either use a two-column widget area or a single column list of recent posts
	if (of_get_option('homepage_bottom') === 'widgets') {
		get_template_part('partials/home', 'bottom-widget-area');
	} else {
		get_template_part('partials/home-post-list');
	} ?>
</div>

<div id="left-rail" class="span4">
<?php
	if (largo_is_sidebar_registered_and_active('homepage-left-rail'))
		dynamic_sidebar('homepage-left-rail');
	else { ?>
		<p><?php _e('Please add widgets to this content area in the WordPress admin area under appearance > widgets.', 'largo'); ?></p>
<?php } ?>
</div>
