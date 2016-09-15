<?php

if ( ! function_exists( 'largo_enqueue_js' ) ) {
	/**
	 * Enqueue our core javascript and css files
	 *
	 * @since 1.0
	 * @global LARGO_DEBUG
	 */
	function largo_enqueue_js() {
		/*
		 * Use minified assets if LARGO_DEBUG is false.
		 */
		$suffix = (LARGO_DEBUG)? '' : '.min';
		$version = largo_version();

		// Our primary stylesheet. Often overridden by custom-less-variables version.
		wp_enqueue_style(
			'largo-stylesheet',
			get_template_directory_uri() . '/css/style' . $suffix . '.css',
			null,
			$version
		);

		// Core JS includes some utilities, initializes carousels, search form behavior,
		// popovers, responsive header image, etc.
		wp_enqueue_script(
			'largoCore',
			get_template_directory_uri() . '/js/largoCore' . $suffix . '.js',
			array( 'jquery' ),
			$version,
			true
		);

		// Navigation-related JS
		wp_enqueue_script(
			'largo-navigation',
			get_template_directory_uri() . '/js/navigation' . $suffix . '.js',
			array( 'largoCore' ),
			$version,
			true
		);

		// Largo configuration object for use in frontend JS
		wp_localize_script(
			'largoCore', 'Largo', array(
			'is_home' => is_home(),
			'is_single' => is_single() || is_singular(),
			'sticky_nav_options' => array(
				'sticky_nav_display' => (bool) of_get_option( 'sticky_nav_display', 0 ),
				'main_nav_hide_article' => (bool) of_get_option( 'main_nav_hide_article', 0 ),
				'nav_overflow_label' => of_get_option( 'nav_overflow_label', 'More' )
			)
		));

		/*
		 * The following files are already minified:
		 *
		 * - modernizr.custom.js
		 * - largoPlugins.js
		 * - jquery.idTabs.js
		 */
		wp_enqueue_script(
			'largo-modernizr',
			get_template_directory_uri() . '/js/modernizr.custom.js',
			null,
			$version
		);
		wp_enqueue_script(
			'largoPlugins',
			get_template_directory_uri() . '/js/largoPlugins.js',
			array( 'jquery' ),
			$version,
			true
		);

	}
}
add_action( 'wp_enqueue_scripts', 'largo_enqueue_js' );

if ( ! function_exists( 'largo_enqueue_child_theme_css' ) ) {
	/**
	 * Enqueue Largo child theme CSS
	 *
	 * @since 0.5.4
	 */
	function largo_enqueue_child_theme_css() {
		//Load the child theme's style.css if we're actually running a child theme of Largo
		$theme = wp_get_theme();

		if (is_object($theme->parent())) {
			wp_enqueue_style( 'largo-child-styles', get_stylesheet_directory_uri() . '/style.css', array('largo-stylesheet'));
		}
	}
	add_action( 'wp_enqueue_scripts', 'largo_enqueue_child_theme_css' );
}

/**
 * Enqueue our admin javascript and css files
 *
 * @global LARGO_DEBUG
 */
function largo_enqueue_admin_scripts() {

	// Use minified assets if LARGO_DEBUG isn't true.
	$suffix = (LARGO_DEBUG)? '' : '.min';
	wp_enqueue_style( 'largo-admin-widgets', get_template_directory_uri().'/css/widgets-php' . $suffix . '.css' );
	wp_enqueue_script( 'largo-admin-widgets', get_template_directory_uri() . '/js/widgets-php' . $suffix . '.js', array( 'jquery' ), '1.0', true );
}
add_action( 'admin_enqueue_scripts', 'largo_enqueue_admin_scripts' );

if ( ! function_exists( 'largo_header_js' ) ) {
	/**
	 * Determine which size of the banner image to load based on the window width
	 *
	 * TODO: this should probably use picturefill for this instead
	 *
	 * @since 1.0
	 */
	function largo_header_js() { ?>
		<script>
			function whichHeader() {
				var screenWidth = document.documentElement.clientWidth,
				header_img;
				if (screenWidth <= 767) {
					header_img = '<?php echo of_get_option( 'banner_image_sm' ); ?>';
				} else if (screenWidth > 767 && screenWidth <= 979) {
					header_img = '<?php echo of_get_option( 'banner_image_med' ); ?>';
				} else {
					header_img = '<?php echo of_get_option( 'banner_image_lg' ); ?>';
				}
				return header_img;
			}
			var banner_img_src = whichHeader();
		</script>
	<?php
	}
}
add_action( 'wp_enqueue_scripts', 'largo_header_js' );

if ( ! function_exists( 'largo_footer_js' ) ) {
	/**
	 * Additional scripts to load in the footer (mostly for various social widgets)
	 *
	 * @since 1.0
	 */
	function largo_footer_js() {

		if ( largo_facebook_widget::is_rendered() || largo_follow_widget::is_rendered() ) { ?>
			<!--Facebook-->
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/<?php echo get_locale() ?>/all.js#xfbml=1";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
		<?php }

		/*
		 * Load Facebook Tracking Pixel if defined in Theme Options
		 *
		 * Function loads Facebook's JavaScript (circa September 2015) for
		 * conversion tracking and send the default event.
		 *
		 * @link https://developers.facebook.com/docs/ads-for-websites/drive-conversions
		 * @since 0.5.4
		 */
		$fb_pixel_id = of_get_option( 'fb_tracking_pixel' );
		if( !empty($fb_pixel_id) ) { ?>
			<script>(function() {
			var _fbq = window._fbq || (window._fbq = []);
			if (!_fbq.loaded) {
				var fbds = document.createElement('script');
				fbds.async = true;
				fbds.src = '//connect.facebook.net/<?php echo get_locale() ?>/fbds.js';
				var s = document.getElementsByTagName('script')[0];
				s.parentNode.insertBefore(fbds, s);
				_fbq.loaded = true;
			}
			_fbq.push(['addPixelId', '<?php echo $fb_pixel_id; ?>']);
			})();
			window._fbq = window._fbq || [];
			window._fbq.push(['track', 'PixelInitialized', {}]);
			</script>
			<!-- Fallback for environments not friendly to script -->
			<noscript>
				<img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id=<?php echo $fb_pixel_id; ?>&amp;ev=PixelInitialized" />
			 </noscript>
		 <?php /* END tracking pixel code */ }

		if ( largo_twitter_widget::is_rendered() || largo_follow_widget::is_rendered() ) { ?>
			<!--Twitter-->
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		<?php }

		/*
		 * Load Facebook Tracking Pixel if defined in Theme Options
		 *
		 * Function loads Facebook's JavaScript (circa September 2015) for
		 * conversion tracking and send the default event.
		 *
		 * @link https://developers.facebook.com/docs/ads-for-websites/drive-conversions
		 * @since 0.5.4
		 */
		$fb_pixel_id = of_get_option( 'fb_tracking_pixel' );
		if( !empty($fb_pixel_id) ) { ?>
			<script>
				(function() {
					var _fbq = window._fbq || (window._fbq = []);
					if (!_fbq.loaded) {
						var fbds = document.createElement('script');
						fbds.async = true;
						fbds.src = '//connect.facebook.net/<?php echo get_locale() ?>/fbds.js';
						var s = document.getElementsByTagName('script')[0];
						s.parentNode.insertBefore(fbds, s);
						_fbq.loaded = true;
					}
					_fbq.push(['addPixelId', '<?php echo $fb_pixel_id; ?>']);
				})();
				window._fbq = window._fbq || [];
				window._fbq.push(['track', 'PixelInitialized', {}]);
			</script>
			<!-- Fallback for environments not friendly to script -->
			<noscript>
				<img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id=<?php echo $fb_pixel_id; ?>&amp;ev=PixelInitialized" />
			</noscript>
		<?php /* END tracking pixel code */ }
	}
}
add_action( 'wp_footer', 'largo_footer_js' );

if ( ! function_exists( 'largo_google_analytics' ) ) {
	/**
	 * Add Google Analytics code to the footer, you need to add your GA ID to the theme settings for this to work
	 *
	 * @since 1.0
	 */
	function largo_google_analytics() {
		if ( !current_user_can('edit_posts') ) : // don't track editors ?>
			<script>
			    var _gaq = _gaq || [];
			<?php if ( of_get_option( 'ga_id', true ) ) : // make sure the ga_id setting is defined ?>
				_gaq.push(['_setAccount', '<?php echo of_get_option( "ga_id" ) ?>']);
				_gaq.push(['_trackPageview']);
			<?php endif; ?>
				<?php if (defined('INN_MEMBER') && INN_MEMBER) { ?>
				_gaq.push(
					["inn._setAccount", "UA-17578670-2"],
					["inn._setCustomVar", 1, "MemberName", "<?php bloginfo('name') ?>"],
					["inn._trackPageview"]
				);
				<?php } ?>
			    _gaq.push(
					["largo._setAccount", "UA-17578670-4"],
					["largo._setCustomVar", 1, "SiteName", "<?php bloginfo('name') ?>"],
					["largo._setDomainName", "<?php echo parse_url( home_url(), PHP_URL_HOST ); ?>"],
					["largo._setAllowLinker", true],
					["largo._trackPageview"]
				);

			    (function() {
				    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				})();
			</script>
	<?php endif;
	}
}
add_action( 'wp_head', 'largo_google_analytics' );
