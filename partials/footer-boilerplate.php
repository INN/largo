<div id="boilerplate">
	<div class="row-fluid clearfix">
		<div class="span6">
			<ul id="footer-social" class="social-icons">
				<?php largo_social_links(); ?>
			</ul>
			<div class="footer-bottom clearfix">

				<!-- If you enjoy this theme and use it on a production site we would appreciate it if you would leave the credit in place. Thanks :) -->
				<p class="footer-credit"><?php largo_copyright_message(); ?></p>
				<?php do_action('largo_after_footer_copyright'); ?>
				<?php largo_nav_menu(
					array(
						'theme_location' => 'footer-bottom',
						'container' => false,
						'depth' => 1
					) );
				?>
			</div>
		</div>

		<div class="span6 right">
			<?php if (INN_MEMBER) { ?>
				<?php inn_logo(); ?>
			<?php } ?>
			<p class="footer-credit <?php echo ( !INN_MEMBER ? 'footer-credit-padding-inn-logo-missing' : ''); ?>"><?php printf( __('Built with the <a href="%s">Largo WordPress Theme</a> from the <a href="%s">Institute for Nonprofit News</a>.', 'largo'),
					'http://largoproject.org',
					'http://inn.org'
				 );
			?></p>
		</div>
	</div>

	<p class="back-to-top visuallyhidden"><a href="#top"><?php _e('Back to top', 'largo'); ?> &uarr;</a></p>
</div>
