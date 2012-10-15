<?php
/**
 * The template for displaying content in the single.php template
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'hnews item' ); ?>>
	<header>
 		<h1 class="entry-title"><?php the_title(); ?></h1>
 		<h5 class="byline"><?php largo_byline(); ?> | <span class="comments-link"><?php comments_popup_link( 'Leave a Comment', '<strong>1</strong> Comment ', ' <strong>%</strong> Comments' ); ?></span><?php edit_post_link('Edit This Post', ' | <span class="edit-link">', '</span>'); ?></h5>
 		<?php get_template_part( 'largo-social' ); ?>
	</header><!-- / entry header -->

	<div class="entry-content clearfix">
		<?php largo_entry_content( $post ); ?>
	</div><!-- .entry-content -->

	<footer class="post-meta bottom-meta">
 		<?php get_template_part( 'largo-social' ); ?>

        <?php if ( largo_has_custom_taxonomy( get_the_ID() ) ): ?>
			<div class="labels clearfix">
            	<h4>More In This Series:</h4>
            	<?php largo_the_post_labels( get_the_ID() ); ?>
        	</div>
        <?php endif; ?>

        <?php if ( largo_has_categories_or_tags() ): ?>
    		<div class="tags clearfix">
    			<h5>Filed Under:</h5>
    			<ul>
    				<?php echo largo_the_categories_and_tags(); ?>
    			</ul>
    		</div>
    	<?php endif; ?>
    </footer><!-- /.post-meta -->
</article><!-- #post-<?php the_ID(); ?> -->

<!-- Author bio and social links -->
<?php if ( largo_show_author_box( $post->ID ) )
	get_template_part( 'largo-author-box' ); ?>

<!-- Related posts -->
<?php if ( of_get_option( 'show_related_content' ) ) :
	if ( $rel_topics = largo_get_post_related_topics( 6 ) ) :
?>
	<div id="related-posts" class="idTabs row-fluid clearfix">
		<ul id="related-post-nav" class="span4">
			<li><h4>More About</h4></li>
			<?php foreach ( $rel_topics as $count => $topic ) : ?>
			<li><a href="#rp<?php echo $count; ?>"><?php echo $topic->name; ?></a></li>
			<?php endforeach; ?>
		</ul>
		<div class="related-items span8">
			<?php foreach ( $rel_topics as $count => $topic ): ?>
				<div id="rp<?php echo $count; ?>">
					<?php $rel_posts = largo_get_recent_posts_for_term( $topic, 3 ); ?>
					<ul>
						<?php $top_post = array_shift( $rel_posts ); ?>
						<li class="top-related clearfix">
							<h3><a href="<?php echo get_permalink( $top_post->ID ); ?>" title="<?php echo esc_attr($topic->name); ?>"><?php echo $top_post->post_title; ?></a></h3>

							<?php if ( has_post_thumbnail( $top_post->ID ) ) { ?>
								<img src="<?php echo largo_get_post_thumbnail_src( $top_post, '60x60' ); ?>" alt="related" width="60" height="60" />
							<?php } ?>
							<p><?php echo largo_get_excerpt( $top_post ); ?> <a href="<?php echo esc_url( get_permalink( $top_post->ID ) ); ?>" title="<?php echo esc_attr($topic->name); ?>"></a></p>
						</li>
						<?php foreach ( $rel_posts as $rel_post ): ?>
						<li><a href="<?php echo esc_url( get_permalink( $rel_post->ID ) ); ?>" title="<?php echo esc_attr($topic->name); ?>"><?php echo $rel_post->post_title; ?></a></li>
						<?php endforeach; ?>
					</ul>
					<p><a href="<?php echo esc_url( get_term_link( $topic ) ); ?>" title="<?php echo esc_attr($topic->name); ?>" target="_blank"><strong>View all <?php echo $topic->name; ?> posts &rarr;</strong></a></p>
				</div> <!-- /#rpX -->
			<?php endforeach; ?>
		</div> <!-- /.items -->
	</div> <!-- /#related-posts -->
<?php
	endif; // if ( $rel_topics )
endif; ?>

<nav id="nav-below" class="pager post-nav clearfix">
	<div class="previous"><?php previous_post_link( '<h5>Previous Story</h5> %link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'largo' ) . '</span> %title' ); ?></div>
	<div class="next"><?php next_post_link( '<h5>Next Story</h5> %link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'largo' ) . '</span>' ); ?></div>
</nav><!-- #nav-below -->


