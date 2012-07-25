<?php
/**
 * The Sidebar containing the main widget area.
 */
?>
<div class="widget-area" role="complementary">

	<?php
		if ( ! dynamic_sidebar( 'sidebar-main' ) )
			the_widget( 'largo_follow_Widget', array( 'title' => 'Follow Us' ) );
	?>
</div><!-- #main .widget-area -->