<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 */
?>

	</div> <!-- #main .container_12 -->

</div><!-- #page -->

<div class="footer-bg clearfix">
	<footer id="site-footer">
			<div id="supplementary" class="row-fluid">
				<?php get_sidebar( 'footer' ); ?>
			</div>
			<div id="boilerplate" class="row-fluid clearfix">
				<p><?php largo_copyright_message(); ?></p>
				<p class="back-to-top"><a href="#top">Back to top &uarr;</a></p>
				<?php wp_nav_menu( array( 'theme_location' => 'footer-bottom', 'container' => false, 'depth' => 1  ) ); ?>
				<p class="footer-credit">This site built with <a href="http://largoproject.org">Project Largo</a> from the <a href="http://investigativenewsnetwork.org">Investigative News Network</a> and proudly powered by <a href="http://wordpress.org" rel="nofollow">WordPress</a>.
			</div><!-- /#boilerplate -->
	</footer>
</div>
<?php wp_footer(); ?>

</body>
</html>