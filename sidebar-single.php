<?php
/**
 * The Sidebar containing the single widget area.
 */
?>
<div class="widget-area showey-hidey" role="complementary">
	<?php
		if ( ! dynamic_sidebar( 'sidebar-single' ) )
			the_widget( 'largo_follow_Widget', array( 'title' => 'Follow Us' ) );
	?>
</div><!-- #main .widget-area -->