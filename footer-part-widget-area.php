<?php
/**
 * The Footer widget areas.
 */
 $layout = of_get_option('footer_layout');
 if ( $layout === '3col-equal') {
 	$layout_spans = array( 'span4', 'span4', 'span4' );
 } elseif ($layout === '4col') {
	 $layout_spans = array( 'span3', 'span3', 'span3' );
 } else {
	 $layout_spans = array( 'span3', 'span6', 'span3' );
 }
?>

<div class="<?php echo $layout_spans[0]; ?> widget-area" role="complementary">
	<?php if ( ! dynamic_sidebar( 'footer-1' ) )
		largo_cached_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'depth' => 1  ) );
	?>
</div>

<div class="<?php echo $layout_spans[1]; ?> widget-area" role="complementary">
	<?php if ( ! dynamic_sidebar( 'footer-2' ) )
		the_widget( 'largo_footer_featured_widget', array( 'title' => __('In Case You Missed It', 'largo'), 'num_sentences' => 2, 'num_posts' => 2 ) );
	?>
</div>

<div class="<?php echo $layout_spans[2]; ?> widget-area" role="complementary">
	<?php if ( ! dynamic_sidebar( 'footer-3' ) ) {
		the_widget( 'WP_Widget_Search', array( 'title' => __('Search This Site', 'largo') ) );
		the_widget( 'WP_Widget_Archives', array( 'title' => __('Browse Archives', 'largo' ), 'dropdown' => 1 ) );
	}
	if ( $layout != '4col') { ?>
		<ul id="ft-social" class="social-icons">
			<?php largo_social_links(); ?>
		</ul>
	<?php } ?>
</div>

<?php if ($layout === '4col') { ?>
<div class="span3 widget-area" role="complementary">
	<?php if ( ! dynamic_sidebar( 'footer-4' ) ) { ?>
		<p><?php _e('Please add widgets to this content area in the WordPress admin area under appearance > widgets.', 'largo'); ?></p>
	<?php } ?>
	<ul id="ft-social" class="social-icons">
		<?php largo_social_links(); ?>
	</ul>
</div>
<?php } ?>