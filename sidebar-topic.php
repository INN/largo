<?php
/**
 * The Sidebar containing the topic widget area.
 */
?>
<div class="widget-area" role="complementary">
	<?php
		if ( ! dynamic_sidebar( 'topic-sidebar' ) )
			the_widget( 'Argo_hosts_Widget', array( 'title' => 'Blog Hosts' ) );
	?>
</div><!-- #main .widget-area -->