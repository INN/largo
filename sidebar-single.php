<?php
/**
 * The Sidebar containing the single widget area.
 */
?>
<div class="widget-area" role="complementary">
	<?php
		if ( ! dynamic_sidebar( 'sidebar-single' ) )
			the_widget( 'Argo_hosts_Widget', array( 'title' => 'Blog Hosts' ) );
	?>
</div><!-- #main .widget-area -->