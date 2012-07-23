<?php
/**
 * The Sidebar containing the main widget area.
 */
?>
<div class="widget-area" role="complementary">

	<aside id="follow-widget" class="widget no-bg largo-follow clearfix">
		<h3 class="widget-title">Follow Us</h3>
		<a href="https://twitter.com/INN" class="twitter-follow-button" data-width="100%" data-align="left" data-size="large">Follow @INN</a>

		<div class="fb-like" data-href="http://www.facebook.com/investigativenewsnetwork" data-send="false" data-show-faces="false"></div>
	</aside>

	<?php
		if ( ! dynamic_sidebar( 'sidebar-main' ) )
			the_widget( 'Argo_hosts_Widget', array( 'title' => 'Blog Hosts' ) );
	?>
</div><!-- #main .widget-area -->