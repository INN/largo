<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 */
?>

	</div> <!-- #main .container_12 -->

	<div id="site-footer" class="clearfix">
		<footer>
			<div id="supplementary" class="row-fluid">
			<?php
				/* A sidebar in the footer? Yep. You can can customize
				 * your footer with three columns of widgets.
				 */
				get_sidebar( 'footer' );
			?>

				<div id="boilerplate" class="row-fluid">
				    <p><?php argo_copyright_message(); ?></p>
				    <?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'depth' => 1  ) ); ?>
					<p class="back-to-top"><a href="#page">Back to top &uarr;</a></p>
				</div><!-- /#boilerplate -->

			</div><!-- /.container_12 -->
		</footer>
	</div><!-- #site-footer -->
</div><!-- #page -->
<?php wp_footer(); ?>


	<!-- google analytics, you have to add your ID in theme settings for this to work -->

	<?php if ( get_option( 'ga_id', true ) // make sure the ga_id setting is defined
		&& ( !is_user_logged_in() ) ) : // don't track logged in users
	?>
	<script>

	    var _gaq = _gaq || [];
	    _gaq.push(['_setAccount', '<?php echo get_option( "ga_id" ) ?>']);
	    _gaq.push(['_trackPageview']);

	    (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();

	</script>
	<?php endif; ?>

	<!-- end:google analytics -->

</body>
</html>