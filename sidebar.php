<?php
/**
 * The Sidebar containing the main widget area.
 */
?>
<div class="widget-area" role="complementary">
	<?php
		if ( ! dynamic_sidebar( 'sidebar-main' ) ) :
			the_widget( 'largo_follow_widget', array( 'title' => 'Follow Us' ) );
			the_widget( 'largo_about_widget', array( 'title' => 'About This Site' ) );
			if ( of_get_option( 'donate_link' ) )
				the_widget( 'largo_donate_widget', array(
					'title' => 'Support ' . get_bloginfo('name'),
					'cta_text' => 'We depend on your support. A generous gift in any amount helps us continue to bring you this service.',
					'button_text' => 'Donate Now',
					'button_url' => esc_url( of_get_option( 'donate_link' ) ),
					'widget_class' => 'default'
					)
				);
			the_widget( 'largo_sidebar_featured_widget', array(
					'title' => 'We Recommend',
					'num_posts' => 5,
					'widget_class' => 'default'
				)
			);
		endif;
		the_widget( 'largo_INN_RSS_widget', array( 'title' => 'Recent Stories from INN Members' ) );
	?>
</div><!-- #main .widget-area -->