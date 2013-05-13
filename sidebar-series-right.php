<?php
/**
 * The Sidebar for Series Landing Pages (righthand column)
 */
global $post;
$sidebar_slug = $post->post_name . "_right";

if ( is_active_sidebar( $sidebar_slug ) ) :

?>
<aside id="sidebar" class="span4">
	<?php do_action('largo_before_sidebar_content'); ?>
	<div class="widget-area<?php if ( of_get_option( 'showey-hidey' ) ) echo ' showey-hidey'; ?>" role="complementary">
		<?php
			do_action('largo_before_sidebar_widgets');
			dynamic_sidebar( $sidebar_slug );

			//Question: Do we also want to show the main sidebar?
			//dynamic_sidebar( 'sidebar-main' );

			do_action('largo_after_sidebar_widgets');
		?>
	</div><!-- .widget-area -->
	<?php do_action('largo_after_sidebar_content'); ?>
</aside>
<?php
endif;