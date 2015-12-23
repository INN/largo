<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the id=main and id=page divs and all content after.
 *
 * @package Largo
 * @since 0.1
 */
?>
	</div> <!-- #main -->

</div><!-- #page -->

<?php
	if ( is_active_sidebar( 'before-footer' ) ) {
		get_template_part( 'partials/footer', 'before-footer-widget-area' );
	}

    /**
     * Fires before the Largo footer content.
     *
     * @since 0.4
     */
	do_action( 'largo_before_footer' );
?>

<div class="footer-bg clearfix nocontent">
	<footer id="site-footer">

		<?php
		    /**
		     * Fires before the Largo footer widgets appear.
		     *
		     * @since 0.4
		     */
			do_action( 'largo_before_footer_widgets' );

			get_template_part( 'partials/footer', 'widget-area' );

		    /**
		     * Fires before the Largo footer boilerplate content.
		     *
		     * @since 0.4
		     */
			do_action( 'largo_before_footer_boilerplate' );

			get_template_part( 'partials/footer', 'boilerplate' );

		    /**
		     * Fires just before the Largo footer content ends.
		     *
		     * @since 0.4
		     */
			do_action( 'largo_before_footer_close' );
		?>

	</footer>
</div>

<?php
	/**
	 * Fires after the Largo footer content.
	 *
	 * @since 0.4
	 */
	do_action( 'largo_after_footer' );

	wp_footer();
?>

</body>
</html>
