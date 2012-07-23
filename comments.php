<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to argo_comment() which is
 * located in the functions.php file.
 */
?>
	<div id="comments" class="clearfix">
	<?php if ( post_password_required() ) : ?>
		<p class="nopassword">This post is password protected. Enter the password to view any comments.</p>
	</div><!-- #comments -->
	<?php
			/* Stop the rest of comments.php from being processed,
			 * but don't kill the script entirely -- we still have
			 * to fully load the template.
			 */
			return;
		endif;
	?>

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h2 id="comments-title">
			<?php
				printf( _n( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number() ),
					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above">
			<div class="nav-previous"><?php previous_comments_link( '&larr; Older Comments' ); ?></div>
			<div class="nav-next"><?php next_comments_link( 'Newer Comments &rarr;' ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

		<ol class="commentlist">
			<?php
				/* Loop through and list the comments. Tell wp_list_comments()
				 * to use argo_comment() to format the comments.
				 * If you want to overload this in a child theme then you can
				 * define argo_comment() and that will be used instead.
				 * See argo_comment() in argo/functions.php for more.
				 */
				wp_list_comments( array( 'callback' => 'argo_comment' ) );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below">
			<div class="nav-previous"><?php previous_comments_link( '&larr; Older Comments' ); ?></div>
			<div class="nav-next"><?php next_comments_link( 'Newer Comments &rarr;' ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

	<?php
		/* If there are no comments and comments are closed, let's leave a little note, shall we?
		 * But we don't want the note on pages or post types that do not support comments.
		 */
		elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="nocomments notice">Comments are closed.</p>
	<?php endif; ?>

	<?php comment_form(); ?>

</div><!-- #comments -->
