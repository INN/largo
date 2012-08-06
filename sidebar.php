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
		endif;
	?>
</div><!-- #main .widget-area -->