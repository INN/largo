<?php
/**
 * The Footer widget areas.
 */
?>

<div class="span3 widget-area" role="complementary">
	<?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'depth' => 1  ) ); ?>
</div> <!-- /.grid_2 -->

<div class="span6 widget-area" role="complementary">
	<?php if ( ! dynamic_sidebar( 'footer-featured-posts' ) )
		the_widget( 'largo_footer_featured_widget', array( 'title' => "In Case You Missed It", 'num_sentences' => 2, 'num_posts' => 2 ) );
	?>
</div> <!-- /.grid_6 -->

<div class="span3 widget-area" role="complementary">
	<?php if ( ! dynamic_sidebar( 'footer-widget-area' ) ) : ?>

	<div id="searchform-footer">
		<h3 class="widget-title">Search This Site</h3>
		<?php get_search_form(); ?>
	</div>

	<div id="ft-archive">
    	<h3 class="widget-title">Browse Archives</h3>
		<select name="archive-dropdown" onChange='document.location.href=this.options[this.selectedIndex].value;'>
			<option value="">Select Month</option>
			<?php wp_get_archives( array( 'type' => 'monthly', 'format' => 'option', 'show_post_count' => 1 ) ); ?>
		</select>
	</div>

	<?php endif; // end sidebar widget area ?>

	<ul id="ft-social">
		<?php largo_social_links(); ?>
	</ul>

</div> <!-- /.span3 -->
