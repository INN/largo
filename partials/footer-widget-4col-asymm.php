<div class="span7 row-fluid">
	<div class="span4 widget-area" role="complementary">
		<?php if ( ! dynamic_sidebar( 'footer-1' ) )
			largo_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'depth' => 1  ) );
		?>
	</div>

	<div class="span4 widget-area" role="complementary">
		<?php if ( ! dynamic_sidebar( 'footer-2' ) )
			the_widget( 'largo_footer_featured_widget', array( 'title' => __('In Case You Missed It', 'largo'), 'num_sentences' => 2, 'num_posts' => 2 ) );
		?>
	</div>

	<div class="span4 widget-area" role="complementary">
		<?php if ( ! dynamic_sidebar( 'footer-3' ) ) {
			the_widget( 'WP_Widget_Search', array( 'title' => __('Search This Site', 'largo') ) );
			the_widget( 'WP_Widget_Archives', array( 'title' => __('Browse Archives', 'largo' ), 'dropdown' => 1 ) );
		} ?>
	</div>
</div>

<div class="span5 widget-area" role="complementary">
	<?php if ( ! dynamic_sidebar( 'footer-4' ) ) { ?>
		<p><?php _e('Please add widgets to this content area in the WordPress admin area under appearance > widgets.', 'largo'); ?></p>
	<?php } ?>
</div>
