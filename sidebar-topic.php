<?php
/**
 * The Sidebar containing the topic widget area.
 */
?>
<div class="widget-area" role="complementary">
	<?php
		if ( ! dynamic_sidebar( 'topic-sidebar' ) )
			the_widget( 'largo_follow_Widget', array( 'title' => 'Follow Us' ) );
	?>
</div><!-- #main .widget-area -->