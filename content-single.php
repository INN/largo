<?php
/**
 * The template for displaying content in the single.php template
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'hnews item' ); ?>>
	<header>
 		<h1 class="entry-title"><?php the_title(); ?></h1>
 		<h5 class="byline">
 			<?php largo_byline(); ?> |
 			<span class="comments-link"><?php comments_popup_link( __('Leave a Comment', 'largo'), __('<strong>1</strong> Comment ', 'largo'), __(' <strong>%</strong> Comments', 'largo') ); ?></span>
 			<?php edit_post_link( __('Edit This Post', 'largo'), ' | <span class="edit-link">', '</span>'); ?>
 			<?php if ( of_get_option( 'clean_read' ) === 'byline' ) : ?>
 				<a href="#" class="clean-read"><?php _e("View as 'Clean Read'", 'largo') ?></a>
 			<?php endif; ?>
 		</h5>
 		<?php
 			if ( of_get_option( 'social_icons_display' ) === 'top' || of_get_option( 'social_icons_display' ) === 'both' )
 				largo_post_social_links();
 		?>
	</header><!-- / entry header -->

	<div class="entry-content clearfix">
		<?php largo_entry_content( $post ); ?>
	</div><!-- .entry-content -->

	<footer class="post-meta bottom-meta">
 		<?php
 			if ( of_get_option( 'social_icons_display' ) === 'btm' || of_get_option( 'social_icons_display' ) === 'both' )
 				largo_post_social_links();
 		?>

 		<!-- Other posts in this series -->
 		<?php if ( largo_post_in_series() ): ?>
			<div class="labels clearfix">
            	<h5><?php _e('More In This Series', 'largo'); ?></h5>
            	<?php largo_the_series_list(); ?>
        	</div>
        <?php endif; ?>

        <!-- Post tags -->
        <?php if ( largo_has_categories_or_tags() && of_get_option( 'show_tags' ) ): ?>
    		<div class="tags clearfix">
    			<h5><?php _e('Filed Under:', 'largo'); ?></h5>
    			<ul>
    				<?php largo_categories_and_tags( of_get_option( 'tag_limit' ), true, true, true, '', 'li' ); ?>
    			</ul>
    		</div>
    	<?php endif; ?>

    	<?php if ( of_get_option( 'clean_read' ) === 'footer' ) : ?>
    	<div class="clean-read-container clearfix">
 				<a href="#" class="clean-read"><?php _e("View as 'Clean Read'", 'largo') ?></a>
    	</div>
 			<?php endif; ?>

		<?php
		// Author bio and social links
		if ( largo_show_author_box() )
			get_template_part( 'largo-author-box' );

		// Related posts
		if ( of_get_option( 'show_related_content' ) )
			get_template_part( 'largo-related-posts' );
		?>


	</footer><!-- /.post-meta -->
</article><!-- #post-<?php the_ID(); ?> -->

<?php if ( of_get_option( 'show_next_prev_nav_single' ) )
	largo_content_nav( 'single-post-nav-below' );
?>

