<?php global $layout; ?>
<div id="homepage-bottom" class="clearfix">
<?php if ( ! dynamic_sidebar( 'homepage-bottom' ) ) : ?>
	<p><?php _e('Please add widgets to this content area in the WordPress admin area under appearance > widgets.', 'largo'); ?></p>
<?php endif; ?>
</div>
