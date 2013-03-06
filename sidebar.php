<?php
/**
 * The Sidebar
 */
?>
<div class="widget-area<?php if ( is_single() && of_get_option( 'showey-hidey' ) ) echo ' showey-hidey'; ?>" role="complementary">
	<?php
		if ( is_singular() && is_active_sidebar( 'sidebar-single' ) ) {
			dynamic_sidebar( 'sidebar-single' );
		} elseif ( ( is_archive() || is_tax() ) && of_get_option( 'use_topic_sidebar' ) && is_active_sidebar( 'topic-sidebar' ) ) {
			dynamic_sidebar( 'topic-sidebar' );
		} elseif ( ! dynamic_sidebar( 'sidebar-main' ) ) {
				the_widget( 'largo_about_widget', array( 'title' => __('About This Site', 'largo') ) );
				the_widget( 'largo_follow_widget', array( 'title' => __('Follow Us', 'largo') ) );
				if ( of_get_option( 'donate_link' ) )
					the_widget( 'largo_donate_widget', array(
						'title' 		=> __('Support ' . get_bloginfo('name'), 'largo'),
						'cta_text' 		=> __('We depend on your support. A generous gift in any amount helps us continue to bring you this service.', 'largo'),
						'button_text' 	=> __('Donate Now', 'largo'),
						'button_url' 	=> esc_url( of_get_option( 'donate_link' ) ),
						'widget_class' 	=> 'default'
						)
					);
				the_widget( 'largo_sidebar_featured_widget', array(
						'title' 		=> __('We Recommend', 'largo'),
						'num_posts'		=> 5,
						'num_sentences' => 2,
						'widget_class' 	=> 'default'
					)
				);
				if ( is_home() && INN_MEMBER === TRUE )
					the_widget( 'largo_INN_RSS_widget', array(
						'title' 		=> __('Stories From Other INN Members', 'largo'),
						'num_posts'		=> 3
						 )
					);
		}

		//Custom hook for loading contextual widgets (e.g. Job Board, Business Directory)
		do_action('largo_sidebar');
	?>
</div><!-- .widget-area -->