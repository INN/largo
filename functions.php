<?php
/**
 * Argo functions and definitions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'eleven_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 */

 /**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 620;

if ( !function_exists( 'optionsframework_init' ) ) {
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/options-framework/' );
	require_once dirname( __FILE__ ) . '/inc/options-framework/options-framework.php';
}
require_once( TEMPLATEPATH . '/inc/users.php' );
require_once( TEMPLATEPATH . '/inc/sidebars.php' );
require_once( TEMPLATEPATH . '/inc/widgets.php' );
require_once( TEMPLATEPATH . '/inc/nav-menus.php' );
require_once( TEMPLATEPATH . '/inc/taxonomies.php' );
require_once( TEMPLATEPATH . '/inc/editor.php' );
require_once( TEMPLATEPATH . '/inc/images.php' );
require_once( TEMPLATEPATH . '/inc/related-content.php' );
require_once( TEMPLATEPATH . '/inc/featured-content.php' );
require_once( TEMPLATEPATH . '/inc/special-functionality.php' );
require_once( TEMPLATEPATH . '/inc/tgm-plugin-init.php' );


/**
 * Tell WordPress to run argo_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'argo_setup' );

if ( ! function_exists( 'argo_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override argo_setup() in a child theme, add your own argo_setup() to your child theme's
 * functions.php file.
 *
 * @uses add_editor_style() To style the visual editor.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links, and Post Formats.
 */
function argo_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Add default posts and comments RSS feed links to <head>.
	add_theme_support( 'automatic-feed-links' );

	// The next four constants set how argo supports custom headers via the TwentyEleven theme
	add_theme_support( 'custom-header');

	// The default header text color
	define( 'HEADER_TEXTCOLOR', '333' );

	// Default image, which child themes should include.
	define( 'HEADER_IMAGE', get_stylesheet_directory_uri() . '/img/headers/largo-text.png' );

	// The height and width of your custom header.
	// Add a filter to argo_header_image_width and argo_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'argo_header_image_width', 1170 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'argo_header_image_height', 120 ) );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See argo_admin_header_style(), below.
	add_custom_image_header( 'argo_header_style', 'argo_admin_header_style', 'argo_admin_header_image' );

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( array(
		'wheel' => array(
			'url' => '%s/img/headers/largo-text.png',
			'thumbnail_url' => '%s/img/headers/default-logo-thumbnail.png',
			/* translators: header image description */
			'description' => 'Wheel',
		),
	) );
}
endif; // argo_setup

if ( ! function_exists( 'argo_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @since argo 1.0
 */
function argo_header_style() {

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	if ( HEADER_TEXTCOLOR == get_header_textcolor() )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == get_header_textcolor() ) :
	?>
		#site-title,
		#site-description {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // argo_header_style

if ( ! function_exists( 'argo_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in argo_setup().
 *
 * @since argo 1.0
 */
function argo_admin_header_style() {
?>
<style type="text/css">
	.appearance_page_custom-header #branding {
		border: none;
	}

	#branding {
		width: 460px;
		height: 140px;
		position: relative;
	}

	#branding h1,
	#desc {
		font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
	}

	#branding h1 {
		margin: 0;
	}

	#branding h1 a {
		font-size: 42px;
		line-height: 1;
		text-decoration: none;
	}

	#ch-desc{
		font-size: 24px;
		line-height: 1;
	}

	.brand-image #ch-name {
		padding-top: 30px;
	}

	.brand-image #ch-name, .brand-image #ch-desc {
		margin-left: 70px;
	}

	.brand-image img {
		position: absolute;
		top: 0;
		left: 0;
		z-index: -1;
	}

	<?php
		// If the user has set a custom color for the text use that
		if ( get_header_textcolor() != HEADER_TEXTCOLOR ) :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>

</style>
<?php
}
endif; // argo_admin_header_style

if ( ! function_exists( 'argo_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in argo_setup().
 *
 * @since argo 1.0
 */
function argo_admin_header_image() { ?>

	<?php
		// Has the text been hidden?
		$header_image = get_header_image();
		if ( 'blank' == get_header_textcolor() || ! $header_image ) :
	?>
	<div id="branding">
	<?php
		else :
	?>
		<div id="branding" class="brand-image">
	<?php endif; ?>


		<?php
		if ( 'blank' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) || '' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) )
			$style = ' style="display:none;"';
		else
			$style = ' style="color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';"';
		?>
		<h1 id="ch-name"><a <?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="ch-desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>

		<?php
			// Check to see if the header image has been removed
			$header_image = get_header_image();
			if ( ! empty( $header_image ) ) :
		?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }
endif; // argo_admin_header_image

// For posts in the last 24 hours, show time since posted instead of the regular date and time

function largo_time() {
	// Change to the date after a certain time
	$time_difference = current_time('timestamp') - get_the_time('U');
	if($time_difference < 86400) {
		return '<span class="time-ago">' .human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago</span>';
	} else {
		return get_the_date();
	};
}

// Print the byline

function largo_byline() {
	printf( '<span class="by-author"><span class="sep">By:</span> <span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span></span> | <time class="entry-date" datetime="%4$s" pubdate>%5$s</time>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( 'View all posts by %s', get_the_author() ) ),
		esc_html( get_the_author() ),
		esc_attr( get_the_date( 'c' ) ),
		largo_time()
	);
}

// print the copyright message in the footer

function largo_copyright_message() {
    $msg = of_get_option( 'copyright_msg' );
    if ( ! $msg )
    	$msg = 'Copyright %s';
    printf( $msg, date( 'Y' ) );
}

/**
 * Sets the post excerpt length to 35 words.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function argo_excerpt_length( $length ) {
	return 35;
}
add_filter( 'excerpt_length', 'argo_excerpt_length' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 */
function argo_continue_reading_link() {
	return ' <a href="'. esc_url( get_permalink() ) . '">' . 'Continue reading <span class="meta-nav">&rarr;</span>' . '</a>';
}

function argo_auto_excerpt_more( $more ) {
	return ' &hellip;' . argo_continue_reading_link();
}
add_filter( 'excerpt_more', 'argo_auto_excerpt_more' );


function argo_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= argo_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'argo_custom_excerpt_more' );

/**
 * Display navigation to next/previous pages when applicable
 */
function argo_content_nav( $nav_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>

<nav id="<?php echo $nav_id; ?>" class="pager post-nav">
	<div class="next"><?php previous_posts_link( 'Newer posts &rarr;' ); ?></div>
	<div class="previous"><?php next_posts_link( '&larr; Older posts' ); ?></div>
</nav><!-- .post-nav -->


	<?php endif;
}

/**
* A pagination function
* @param integer $range: The range of the slider, works best with even numbers
* Used WP functions:
* get_pagenum_link($i) - creates the link, e.g. http://site.com/page/4
* previous_posts_link(' < '); - returns the Previous page link
* next_posts_link(' > '); - returns the Next page link
*/
function argo_pagination( $range = 6 ) {
	// $paged - number of the current page
	global $paged, $wp_query;

	$max_page = $wp_query->max_num_pages;

	// We need the pagination only if there are more than 1 page
	if ( $max_page <= 1 )
		return;

    if ( ! $paged )
      $paged = 1;
?>

    <nav>
		<ul class="argo-pag clearfix">
		<li class="argo-previous"><?php previous_posts_link( '&larr; Newer posts' ); ?></li>

				<?php if ( $max_page > $range ) {
				// When closer to the beginning
					if ( $paged < $range ) {
						for ( $i = 1; $i <= ( $range + 1 ); $i++ ) {
							echo "<li><a href='" . esc_url( get_pagenum_link( $i ) ) ."'";
							if( $i == $paged )
								echo " class='current'";
							echo ">$i</a></li>";
						}
					}
					// When closer to the end
					elseif ( $paged >= ( $max_page - ceil( ( $range / 2 ) ) ) ) {
						for ( $i = $max_page - $range; $i <= $max_page; $i++ ) {
							echo "<li><a href='" . esc_url( get_pagenum_link( $i ) ) ."'";
							if( $i == $paged )
								echo " class='current'";
							echo ">$i</a></li>";
						}
					}
					// Somewhere in the middle
					elseif ( $paged >= $range && $paged < ( $max_page - ceil( ( $range / 2 ) ) ) ) {
						for ( $i = ( $paged - ceil( $range / 2 ) ); $i <= ( $paged + ceil( ( $range / 2 ) ) ); $i++ ) {
							echo "<li><a href='" . esc_url( get_pagenum_link( $i ) ) ."'";
							if( $i == $paged )
								echo " class='current'";
							echo ">$i</a></li>";
						}
					}
				}
    			// Less pages than the range, no sliding effect needed
				else {
					for( $i = 1; $i <= $max_page; $i++ ){
						echo "<li><a href='" . esc_url( get_pagenum_link( $i ) ) ."'";
						if( $i == $paged )
							echo " class='current'";
						echo ">$i</a></li>";
					}
				} ?>
			<li class="argo-next"><?php next_posts_link( 'Older posts &rarr;' ); ?></li>
		</ul>
	</nav><!-- .post-nav -->
 <?php
}

if ( ! function_exists( 'argo_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own eleven_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function argo_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p>Pingback: <?php comment_author_link(); ?><?php edit_comment_link( 'Edit', '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 68;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 39;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( '%1$s on %2$s <span class="says">said:</span>',
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( '%1$s at %2$s', get_comment_date(), get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( 'Edit', '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation">Your comment is awaiting moderation.</em>
					<br />
				<?php endif; ?>

			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => 'Reply <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for argo_comment()

 /**
 * Enqueue JS for the footer
 */
function argo_enqueue_js() {

	wp_enqueue_script( 'text_placeholder', get_bloginfo('template_url') . '/js/jquery.textPlaceholder.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'bootstrap_dropdown', get_bloginfo('template_url') . '/js/bootstrap-dropdown.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'bootstrap_collapse', get_bloginfo('template_url') . '/js/bootstrap-collapse.js', array( 'jquery' ), '1.0', true );

	if ( get_option( 'show_related_content', true ) )
		wp_enqueue_script( 'idTabs', get_bloginfo('template_url') . '/js/jquery.idTabs.js', array( 'jquery' ), '1.0', true );
}
add_action('wp_enqueue_scripts', 'argo_enqueue_js' );

add_action( 'wp_footer', 'argo_footer_js' );
	function argo_footer_js() { ?>

		<script type="text/javascript">
			jQuery(document).ready(function($) {
				//html5 placeholders
				$("input[placeholder]").textPlaceholder();

				$(window).scroll(function(){
					if (($(window).scrollTop() > 50)) {
						$('#left-nav').fadeIn(500);
					} else if (($(window).scrollTop() < 50)) {
						$('#left-nav').fadeOut(500);
					}
				});

				$('#left-nav').hover(function() {
			       		$(this).animate({width: '20%',overflow: 'visible'}, { duration: 200, queue: false });
			       		$('#page').animate({opacity: '0.7'}, 100);
			        }, function() {
			        	$(this).animate({width: '4%',overflow: 'hidden'}, { duration: 200, queue: false });
			        	$('#page').animate({opacity: '1'}, 100);
			    });

			    // dim sidebar
			    $(window).scroll(function(){
					if (($(window).scrollTop() > 300)) {
						$('.showey-hidey').animate({opacity: '0.5'}, 100);
					} else if (($(window).scrollTop() < 300)) {
						$('.showey-hidey').animate({opacity: '1'}, 100);
					}
				});
				$('.showey-hidey').hover(function() {
			       		$(this).animate({opacity: '1'}, 100);
			        }, function() {
			        	if (($(window).scrollTop() < 300)) {
			        		$(this).animate({opacity: '1'}, 100);
			        	} else {
			        		$(this).animate({opacity: '0.5'}, 100);
			        	};
			    });

			});

		</script>

		<!--
		Social Media API Calls
		-->

		<!--Facebook-->
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=375948615804689";
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


	<?php }

add_action( 'wp_footer', 'largo_google_analytics' );

// add Google Analytics code to the footer, you need to add your GA ID to the theme settings for this to work

function largo_google_analytics() {

	if ( get_option( 'ga_id', true ) // make sure the ga_id setting is defined
		&& ( !is_user_logged_in() ) ) : // don't track logged in users
	?>
	<script>
	    var _gaq = _gaq || [];
	    _gaq.push(['_setAccount', '<?php echo of_get_option( "ga_id" ) ?>']);
	    _gaq.push(['_trackPageview']);

	    (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
	<?php endif;
}

//hide the admin bar
	add_filter( 'show_admin_bar', '__return_false' );