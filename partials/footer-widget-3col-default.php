<div class="span3 widget-area" role="complementary">
	<?php if ( ! dynamic_sidebar( 'footer-1' ) )
		largo_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'depth' => 1  ) );
	?>
</div>

<div class="span6 widget-area" role="complementary">
	<?php if ( ! dynamic_sidebar( 'footer-2' ) ) {

/**
 * @see https://github.com/INN/largo/issues/1467
 */
//		the_widget( 'largo_featured_widget', array(
//				'term' => 'footer-featured',
//				'title' => __('In Case You Missed It', 'largo'),
//				'widget_class' => 'default',
//				'num_posts' => 2,
//				'num_sentences' => 2,
//				'thumb' => 'before'
//			)
//		);
	}
	?>
</div>

<div class="span3 widget-area" role="complementary">
	<?php if ( ! dynamic_sidebar( 'footer-3' ) ) {
		the_widget( 'WP_Widget_Search', array( 'title' => __('Search This Site', 'largo') ) );
		the_widget( 'WP_Widget_Archives', array( 'title' => __('Browse Archives', 'largo' ), 'dropdown' => 1 ) );
	} ?>
</div>
