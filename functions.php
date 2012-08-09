<?php
/**
 * Largo functions and definitions
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
	$content_width = 771;

if ( !function_exists( 'optionsframework_init' ) ) {
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/options-framework/' );
	require_once dirname( __FILE__ ) . '/inc/options-framework/options-framework.php';
}
require_once( TEMPLATEPATH . '/inc/users.php' );
require_once( TEMPLATEPATH . '/inc/sidebars.php' );
require_once( TEMPLATEPATH . '/inc/widgets.php' );
require_once( TEMPLATEPATH . '/inc/nav-menus.php' );
require_once( TEMPLATEPATH . '/inc/open-graph.php' );
require_once( TEMPLATEPATH . '/inc/taxonomies.php' );
require_once( TEMPLATEPATH . '/inc/editor.php' );
require_once( TEMPLATEPATH . '/inc/post-meta.php' );
require_once( TEMPLATEPATH . '/inc/images.php' );
require_once( TEMPLATEPATH . '/inc/related-content.php' );
require_once( TEMPLATEPATH . '/inc/featured-content.php' );
require_once( TEMPLATEPATH . '/inc/special-functionality.php' );
require_once( TEMPLATEPATH . '/inc/largo-plugin-init.php' );

/**
 * Tell WordPress to run largo_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'largo_setup' );

if ( ! function_exists( 'largo_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override largo_setup() in a child theme, add your own largo_setup() to your child theme's
 * functions.php file.
 *
 * @uses add_editor_style() To style the visual editor.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links, and Post Formats.
 */
function largo_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style('/css/editor-style.css');

	// Add default posts and comments RSS feed links to <head>.
	add_theme_support( 'automatic-feed-links' );

}
endif; // largo_setup

/**
 * Sets the post excerpt length to 35 words.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function largo_excerpt_length( $length ) {
	return 35;
}
add_filter( 'excerpt_length', 'largo_excerpt_length' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 */
function largo_continue_reading_link() {
	return ' <a href="'. esc_url( get_permalink() ) . '">' . 'Continue reading <span class="meta-nav">&rarr;</span>' . '</a>';
}

function largo_auto_excerpt_more( $more ) {
	return ' &hellip;' . largo_continue_reading_link();
}
add_filter( 'excerpt_more', 'largo_auto_excerpt_more' );


function largo_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= largo_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'largo_custom_excerpt_more' );

/**
 * Display navigation to next/previous pages when applicable
 */
function largo_content_nav( $nav_id ) {
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
function largo_pagination( $range = 6 ) {
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
		<ul class="largo-pag clearfix">
		<li class="largo-previous"><?php previous_posts_link( '&larr; Newer posts' ); ?></li>

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
			<li class="largo-next"><?php next_posts_link( 'Older posts &rarr;' ); ?></li>
		</ul>
	</nav><!-- .post-nav -->
 <?php
}

if ( ! function_exists( 'largo_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own eleven_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function largo_comment( $comment, $args, $depth ) {
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
endif; // ends check for largo_comment()

// print the copyright message in the footer

function largo_copyright_message() {
    $msg = of_get_option( 'copyright_msg' );
    if ( ! $msg )
    	$msg = 'Copyright %s';
    printf( $msg, date( 'Y' ) );
}

 /**
 * Enqueue JS for the footer
 */
function largo_enqueue_js() {
	wp_enqueue_script( 'text_placeholder', get_bloginfo('template_url') . '/js/jquery.textPlaceholder.js', array( 'jquery' ), '1.0', true );
	if ( get_option( 'show_related_content', true ) )
		wp_enqueue_script( 'idTabs', get_bloginfo('template_url') . '/js/jquery.idTabs.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'bootstrap', get_bloginfo('template_url') . '/js/bootstrap.min.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'largoCore', get_bloginfo('template_url') . '/js/largoCore.js', array( 'jquery' ), '1.0', true );
	if ( is_single() )
		wp_enqueue_script( 'sharethis', get_bloginfo('template_url') . '/js/st_buttons.js', array( 'jquery' ), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'largo_enqueue_js' );

function largo_header_js() {
	//decides which size of the banner image to load based on the window width ?>
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
			};
			return header_img;
		};
		var banner_img_src = whichHeader();
	</script>
<?php
}
add_action( 'wp_head', 'largo_header_js' );

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
add_action( 'wp_enqueue_scripts', 'largo_footer_js' );

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
	<?php
	endif;
}
add_action( 'wp_footer', 'largo_google_analytics' );