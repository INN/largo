<?php
/**
 * The Sidebar
 */
$span_class = is_single() ? "span2" : "span4" ;

do_action('largo_before_sidebar');
?>
<aside id="sidebar" class="<?php echo $span_class; ?>">
	<?php do_action('largo_before_sidebar_content'); ?>
	<div class="widget-area<?php if ( is_single() && of_get_option( 'showey-hidey' ) ) echo ' showey-hidey'; ?>" role="complementary">
		<?php
			do_action('largo_before_sidebar_widgets');

			$custom_sidebar = false;
			//get a custom sidebar if appropriate
			if ( is_singular() ) {
				$custom_sidebar = get_post_meta(get_the_ID(), 'custom_sidebar', true);
			} else if ( is_archive() ) {
				$term = get_queried_object();
				$custom_sidebar = largo_get_term_meta( $term->taxonomy, $term->term_id, 'custom_sidebar', true );
			}

			//load custom sidebar if appropriate
			if ( $custom_sidebar ) {
				if ($custom_sidebar !== 'none' && $custom_sidebar !== 'default' ) {
					dynamic_sidebar($custom_sidebar);
				} else if ( is_archive() || $custom_sidebar == 'default' ) {
					dynamic_sidebar( 'sidebar-main' );
				}
			//else load single sidebar if it has things
			} elseif ( is_singular() && is_active_sidebar( 'sidebar-single' )) {
				dynamic_sidebar( 'sidebar-single' );

			//load archive/topic sidebar if activated
			} elseif ( ( is_archive() || is_tax() ) && of_get_option( 'use_topic_sidebar' ) && is_active_sidebar( 'topic-sidebar' ) ) {
				dynamic_sidebar( 'topic-sidebar' );

			//load some widgets if the main sidebar is empty
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

			do_action('largo_after_sidebar_widgets');
		?>
	</div><!-- .widget-area -->
	<?php do_action('largo_after_sidebar_content'); ?>
</aside>
<?php
do_action('largo_after_sidebar');