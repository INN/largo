<?php

if ((is_single() || is_singular()) && !largo_is_sidebar_required())
	return;

$span_class = largo_sidebar_span_class();

do_action('largo_before_sidebar'); ?>
<aside id="sidebar" class="<?php echo $span_class; ?> nocontent">
	<?php do_action('largo_before_sidebar_content'); ?>
	<div class="widget-area" role="complementary">
		<?php
			do_action('largo_before_sidebar_widgets');

			if (is_archive() && !is_date())
				get_template_part('partials/sidebar', 'archive');
			else if (is_single() || is_singular())
				get_template_part('partials/sidebar', 'single');
			else
				get_template_part('partials/sidebar');

			do_action('largo_after_sidebar_widgets');
		?>
	</div>
	<?php do_action('largo_after_sidebar_content'); ?>
</aside>
<?php do_action('largo_after_sidebar');
