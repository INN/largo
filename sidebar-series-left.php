<?php
/**
 * The Sidebar for Series Landing Pages (lefthand column)
 */
global $post;
$sidebar_slug = $post->post_name . "_left";

if ( is_active_sidebar( $sidebar_slug ) ) :
?>
	<aside id="sidebar-left" class="span3">
		<div class="widget-area<?php if ( of_get_option( 'showey-hidey' ) ) echo ' showey-hidey'; ?>" role="complementary">
			<?php
				dynamic_sidebar( $sidebar_slug );
			?>
		</div><!-- .widget-area -->
	</aside>
<?php
endif;