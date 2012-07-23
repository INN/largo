<?php
/**
 * The Footer widget areas.
 */
?>

<div class="span2 widget-area" role="complementary">

	<?php
	if ( ! dynamic_sidebar( 'footer-1' ) )
		the_widget( 'Argo_follow_Widget', array( 'title' => 'Follow us' ) );
	?>

</div> <!-- /.grid_2 -->

<div class="span6 widget-area" role="complementary">

	<?php
	if ( ! dynamic_sidebar( 'footer-2' ) )
		the_widget( 'Argo_more_featured_Widget', array( 'title' => "Don't Miss" ) );
	?>

</div> <!-- /.grid_6 -->

<div class="span4 widget-area" role="complementary">
	<?php if ( ! dynamic_sidebar( 'footer-3' ) ) : ?>

	<div id="searchform-footer">
		<?php get_search_form(); ?>
	</div>

	<div id="ft-archive">
    	<h2 class="widget-title">Browse archives by date</h3>
		<select name="archive-dropdown" onChange='document.location.href=this.options[this.selectedIndex].value;'>
			<option value="">Select Month</option>
			<?php wp_get_archives( array( 'type' => 'monthly', 'format' => 'option', 'show_post_count' => 1 ) ); ?>
		</select>
	</div>

<?php endif; // end sidebar widget area ?>
</div> <!-- /.grid_4 -->
