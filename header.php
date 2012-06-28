<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 */
?><!DOCTYPE html>
<!--[if lt IE 7]> <html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
<!--[if IE 7]>    <html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
<!--[if IE 8]>    <html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
<!--[if IE 9]>    <html <?php language_attributes(); ?> class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . 'Page ' . max( $paged, $page );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />


<link rel="stylesheet" href="<?php bloginfo( 'template_directory' ); ?>/css/master.css" />
<noscript>
<link rel="stylesheet" href="<?php bloginfo( 'template_directory' ); ?>/css/mobile.min.css" />
</noscript>
<script>
// Edit to suit your needs.
var ADAPT_CONFIG = {
  // Where is your CSS?
  path: '<?php bloginfo( 'template_directory' ); ?>/css/',

  // false = Only run once, when page first loads.
  // true = Change on window resize and page tilt.
  dynamic: true,

  // First range entry is the minimum.
  // Last range entry is the maximum.
  // Separate ranges by "to" keyword.
  range: [
    '0px    to 760px  = mobile.min.css',
    '760px  to 980px  = 720.min.css',
    '980px  to 1280px = 960.min.css',
    '1280px to 1600px = 1200.min.css',
    '1600px to 1940px = 1560.min.css',
    '1940px to 2540px = 1920.min.css',
    '2540px           = 2520.min.css'
  ]
};
</script>
<script src="<?php bloginfo( 'template_directory' ); ?>/js/adapt.min.js"></script>




<?php
	wp_enqueue_style( 'argo-stylesheet', get_bloginfo( 'stylesheet_url' ) );
	wp_enqueue_script( 'argo-modernizr', get_template_directory_uri() . '/js/modernizr.custom.55609.js' );

	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed">
   
   <div class="global-nav-bg"> 
		<div class="global-nav container_12">
			<nav class="grid_12">
        		<span class="visuallyhidden">
        			<a href="#main" title="Skip to content">Skip to content</a>
        		</span>
        		<?php wp_nav_menu( array( 'theme_location' => 'global-nav', 'container' => false, 'depth' => 1 ) ); ?>
        	</nav>
        </div>
       <!-- /.global-nav -->
	</div> <!-- /.global-nav-bg -->

	<div id="header"><header>
	
	<div class="container_12 clearfix">
			<?php
				$header_image = get_header_image();
				// Has the text been hidden?
				if ( 'blank' == get_header_textcolor() || '' == get_header_textcolor()):
					$style = ' style="display:none;"';
			?>
				<div id="branding" class="grid_12">
			<?php 
				// Has the header image been hidden?
				elseif ( ! $header_image ) :
			?>
				<div id="branding" class="grid_12">
			<?php
				else :
				$style = ' style="color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';"';
			?>
				<div id="branding" class="grid_12 brand-image">
			<?php endif; ?>
			
			    <?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'h2'; ?>
				<<?php echo $heading_tag; ?> id="site-title" <?php echo $style; ?>>
        		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
            	<?php bloginfo('name'); ?>
        		</a>
    		</<?php echo $heading_tag; ?>>
    		<h2 id="site-description" <?php echo $style; ?>><?php bloginfo('description'); ?></h2>
    		
			<?php
				// Check to see if the header image should be displayed
				if ( $header_image ) :
			?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="<?php esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
				</a>
			<?php endif; ?>
			
		</div><!-- end .grid_12 -->
		
	</div> <!--/ .container_12 -->

	</header></div>
<!-- ============= / #header  ============= -->
	<div id="main-nav" class="container_12">
		<nav class="grid_12">
        <?php wp_nav_menu( array( 'theme_location' => 'categories', 'container' => false , 'menu_id' => 'topnav', 'walker' => new Argo_Categories_Walker, 'depth' => 1 ) ); ?>
		</nav><!-- /#main-nav -->
		
    </div> <!-- /main-nav -->
    
<div id="main" class="container_12 clearfix">