<!DOCTYPE html>
<!--[if lt IE 7]> <html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
<!--[if IE 7]>    <html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
<!--[if IE 8]>    <html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
<!--[if IE 9]>    <html <?php language_attributes(); ?> class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<?php
	// get the current page url (used for rel canonical and open graph tags)
	global $current_url;
	$current_url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
?>
	<title>
		<?php
			global $page, $paged;
			wp_title( '|', true, 'right' );
			bloginfo( 'name' ); // Add the blog name.

			// Add the blog description for the home/front page.
			$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description && ( is_home() || is_front_page() ) )
				echo " | $site_description";

			// Add a page number if necessary:
			if ( $paged >= 2 || $page >= 2 )
				echo ' | ' . 'Page ' . max( $paged, $page );
		?>
	</title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	wp_head();
?>
</head>

<body <?php body_class(); ?>>
<div id="top"></div>
<?php if ( is_front_page() || is_home() ): ?>
	<div class="global-nav-bg">
		<div class="global-nav">
			<nav id="top-nav" class="span12">
	        	<span class="visuallyhidden">
	        		<a href="#main" title="<?php esc_attr_e( 'Skip to content', 'largo' ); ?>"><?php _e( 'Skip to content', 'largo' ); ?></a>
	        	</span>
	        	<?php
							$top_args = array(
								'theme_location' => 'global-nav',
								'depth'		 => 1,
								'container'	 => false,
							);
							largo_cached_nav_menu($top_args);
						?>
	        	<div class="nav-right">

	        		<?php if ( of_get_option( 'show_header_social') ) { ?>
		        		<ul id="header-social" class="social-icons visible-desktop">
							<?php largo_social_links(); ?>
						</ul>
					<?php } ?>

	        		<?php if ( of_get_option( 'show_donate_button') )
	        			largo_donate_button();
	        		?>

					<div id="header-search">
						<form class="form-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							<div class="input-append">
								<input type="text" placeholder="<?php _e('Search', 'largo'); ?>" class="input-medium appendedInputButton search-query" value="" name="s" /><button type="submit" class="search-submit btn"><?php _e('GO', 'largo'); ?></button>
							</div>
						</form>
					</div>


					<?php if ( INN_MEMBER === TRUE ) { ?>
					<div class="org-logo">
	        			<a href="http://investigativenewsnetwork.org/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/img/inn-logo-80-55.jpg" height="55" alt="INN logo" /></a>
					</div>
					<?php } ?>

	        	</div>
	        </nav>
	    </div> <!-- /.global-nav -->
	</div> <!-- /.global-nav-bg -->
<?php endif; ?>

<div id="page" class="hfeed clearfix">

	<?php // if (function_exists('dynamic_sidebar')) { dynamic_sidebar("Header"); } ?>

	<header id="site-header" class="clearfix" itemscope itemtype="http://schema.org/Organization">
		<?php largo_header(); ?>
	</header>

	<header class="print-header">
		<p><strong><?php echo esc_html( get_bloginfo( 'name' ) ); ?></strong> (<?php echo esc_url( $current_url ); ?>)</p>
	</header>

	<div class="sticky-nav-wrapper">
		<div class="sticky-nav-holder show" data-hide-at-top="<?php echo (is_front_page() || is_home()) ? 'true' : 'false'; ?>"><div class="sticky-nav-container">
			<nav id="sticky-nav" class="sticky-navbar navbar clearfix">
		    <div class="container">
		    	<div class="nav-right">
			      <?php if ( of_get_option( 'show_donate_button') )
	      			largo_donate_button();
	      		?>

						<div id="header-search">
							<form class="form-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<i class="icon-search toggle" title="<?php esc_attr_e('Search', 'largo'); ?>" role="button"></i>
								<div class="input-append">
									<span class="text-input-wrapper"><input type="text" placeholder="<?php esc_attr_e('Search', 'largo'); ?>" class="input-medium appendedInputButton search-query" value="" name="s" /></span><button type="submit" class="search-submit btn"><?php _e('GO', 'largo'); ?></button>
								</div>
							</form>
						</div>
					</div>

		      <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
		      <a class="btn btn-navbar toggle-nav-bar" title="<?php esc_attr_e('More', 'largo'); ?>">
		        <div class="bars">
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
		        </div>
		      </a>

		      <ul class="nav">
		        <li class="home-link"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php largo_home_icon( 'icon-white' ); ?></a></li>
		        <li class="divider-vertical"></li>
					</ul>

		      <div class="nav-shelf">
						<ul class="nav"><?php
							$args = array(
								'theme_location' => 'navbar-categories',
								'depth'		 => 0,
								'container'	 => false,
								'items_wrap' => '%3$s',
								'menu_class' => 'nav',
								'walker'	 => new Bootstrap_Walker_Nav_Menu()
							);
							largo_cached_nav_menu($args);
							?>
							<li class="menu-item-has-childen dropdown">
								<a href="javascript:void(0);" class="dropdown-toggle"><?php
										//try to get the menu name from global-nav
										$menus = get_nav_menu_locations();
										$menu_title = wp_get_nav_menu_object($menus['global-nav'])->name;
										echo ( $menu_title ) ? $menu_title : __('About', 'largo');
									?> <b class="caret"></b>
								</a>
								<?php
									$args = array(
										'theme_location' => 'global-nav',
										'depth'		 => 1,
										'container'	 => false,
										'menu_class' => 'dropdown-menu',
									);
									largo_cached_nav_menu($args);
								?>
							</li>
						</ul>
					</div>
		    </div>
			</nav>
		</div></div>
	</div>

	<?php if ( is_front_page() || is_home() ): ?>
	<nav id="main-nav" class="navbar clearfix">
	  <div class="navbar-inner">
	    <div class="container">

	      <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
	      <a class="btn btn-navbar toggle-nav-bar"  title="<?php esc_attr_e('More', 'largo'); ?>">
	        <div class="bars">
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
	        </div>
	      </a>

	      <div class="nav-shelf">
	      	<ul class="nav">
			    <?php
						$args = array(
							'theme_location' => 'navbar-categories',
							'depth'		 => 0,
							'container'	 => false,
							'items_wrap' => '%3$s',
							'menu_class' => 'nav',
							'walker'	 => new Bootstrap_Walker_Nav_Menu()
						);
						largo_cached_nav_menu($args);
					?>
					</ul>
					<ul class="nav visible-phone">
						<li class="menu-item-has-childen dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle"><?php
									//try to get the menu name from global-nav
									$menus = get_nav_menu_locations();
									$menu_title = wp_get_nav_menu_object($menus['global-nav'])->name;
									echo ( $menu_title ) ? $menu_title : __('About', 'largo');
								?> <b class="caret"></b>
							</a>
							<?php
								$args = array(
									'theme_location' => 'global-nav',
									'depth'		 => 1,
									'container'	 => false,
									'menu_class' => 'dropdown-menu',
								);
								largo_cached_nav_menu($args);
							?>
						</li>
					</ul>
				</div>
	    </div>
	  </div>
	</nav>
	<?php endif; ?>

<?php if ( of_get_option( 'show_dont_miss_menu') ) : ?>
<nav id="secondary-nav" class="clearfix">
  	<div id="topics-bar" class="span12 hidden-phone">
		<?php largo_cached_nav_menu( array( 'theme_location' => 'dont-miss', 'container' => false, 'depth' => 1 ) ); ?>
	</div>
</nav>
<?php endif; ?>

<?php if ( is_front_page() && is_active_sidebar( 'homepage-alert' ) ) :  // using is_front_page() instead of is_home() in case static page is used ?>
<div class="alert-wrapper max-wide">
	<div id="alert-container">
		<?php dynamic_sidebar( 'homepage-alert' ); ?>
	</div>
</div>
<?php endif; ?>

<div id="main" class="row-fluid clearfix">