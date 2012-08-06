<?php
/**
 * The Sidebar containing the single widget area.
 */
?>
<div class="widget-area showey-hidey" role="complementary">
	<?php
		if ( ! dynamic_sidebar( 'sidebar-single' ) ) :
			the_widget( 'largo_follow_widget', array( 'title' => 'Follow Us' ) );
			the_widget( 'largo_about_widget', array( 'title' => 'About This Site' ) );
		endif;
	?>
</div><!-- #main .widget-area -->