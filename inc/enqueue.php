<?php

/**
 * Enqueue all of our javascript and css files
 *
 * @since 1.0
 */
if ( ! function_exists( 'largo_enqueue_js' ) ) {
	function largo_enqueue_js() {

		//Modernizr and our primary stylesheet
		wp_enqueue_style( 'largo-stylesheet', get_template_directory_uri().'/css/style.css' );  //often overridden by custom-less-variables version
		wp_enqueue_script( 'largo-modernizr', get_template_directory_uri() . '/js/modernizr.custom.js' );

		//the jquery plugins and our main js file
		wp_enqueue_script( 'largoPlugins', get_template_directory_uri() . '/js/largoPlugins.js', array( 'jquery' ), '1.0', true );
		wp_enqueue_script( 'largoCore', get_template_directory_uri() . '/js/largoCore.js', array( 'jquery' ), '1.0', true );

		//Add picturefill and matchmedia -- https://github.com/scottjehl/picturefill
		wp_enqueue_script( 'matchmedia', get_template_directory_uri() . '/js/matchmedia.js', array( ), '1.0', true );
		wp_enqueue_script( 'picturefill', get_template_directory_uri() . '/js/picturefill.js', array( ), '1.0', true );


		//only load the carousel and top stories js and css if those homepage options are selected
		if ( is_home() && of_get_option( 'homepage_top') == 'slider' ) {
			wp_enqueue_script( 'bootstrap-carousel', get_template_directory_uri() . '/js/bootstrap-carousel.min.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_style( 'carousel-styles', get_template_directory_uri() . '/css/carousel.css', false, false, 'screen' );
		}
		if ( is_home() && of_get_option( 'homepage_top') == 'topstories' )
			wp_enqueue_style( 'topstory-styles', get_template_directory_uri() . '/css/top-stories.css', false, false, 'screen' );

		// Ads styles
		wp_enqueue_style( 'ads-styles', get_template_directory_uri() . '/css/ads.css', false, false, 'screen' );

		//only load sharethis on single pages and load jquery tabs for the related content box if it's active
		if ( is_single() ) {
			$utilities = of_get_option( 'article_utilities' );
			if ( of_get_option( 'social_icons_display' ) != 'none' && ( $utilities['sharethis'] === '1' || $utilities['email'] === '1' ) )
				wp_enqueue_script( 'sharethis', get_template_directory_uri() . '/js/st_buttons.js', array( 'jquery' ), '1.0', true );
			if ( of_get_option( 'show_related_content' ) )
				wp_enqueue_script( 'idTabs', get_template_directory_uri() . '/js/jquery.idTabs.js', array( 'jquery' ), '1.0', true );
		}

		//Load the child theme's style.css if we're actually running a child theme of Largo
		$theme = wp_get_theme();
		if ( isset($theme->parent()->Template) && $theme->parent()->Template == 'largo') {
			wp_enqueue_style( 'largo-child-styles', get_stylesheet_directory_uri() . '/style.css', array('largo-stylesheet'));
		}
	}
}
add_action( 'wp_enqueue_scripts', 'largo_enqueue_js' );

/**
 * Determine which size of the banner image to load based on the window width
 *
 * @since 1.0
 */
if ( ! function_exists( 'largo_header_js' ) ) {
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

/**
 * Additional scripts to load in the footer (mostly for various social widgets)
 *
 * @since 1.0
 */
if ( ! function_exists( 'largo_footer_js' ) ) {
	function largo_footer_js() { ?>
		<!--Facebook-->
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>

		<!--Twitter-->
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="http://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

		<!--Google Plus-->
		<script type="text/javascript">
		  (function() {
		    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			po.src = 'https://apis.google.com/js/plusone.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		  })();
		</script>
	<?php
	}
}
add_action( 'wp_footer', 'largo_footer_js' );

/**
 * Add Google Analytics code to the footer, you need to add your GA ID to the theme settings for this to work
 *
 * @since 1.0
 */
if ( ! function_exists( 'largo_google_analytics' ) ) {
	function largo_google_analytics() {
		if ( !is_user_logged_in() ) : // don't track logged in users ?>
			<script>
			    var _gaq = _gaq || [];
			<?php if ( of_get_option( 'ga_id', true ) ) : // make sure the ga_id setting is defined ?>
				_gaq.push(['_setAccount', '<?php echo of_get_option( "ga_id" ) ?>']);
				_gaq.push(['_trackPageview']);
			<?php endif; ?>
			    _gaq.push(
					["largo._setAccount", "UA-17578670-4"],
					["largo._setCustomVar", 1, "SiteName", "<?php bloginfo('name') ?>"],
					["largo._trackPageview"],
					["largo._setDomainName", "<?php echo str_replace( 'http://' , '' , home_url()) ?>"],
					["largo._setAllowLinker", true]
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
add_action( 'wp_footer', 'largo_google_analytics' );

