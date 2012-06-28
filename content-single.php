<?php
/**
 * The template for displaying content in the single.php template
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header>
		<div class="post-meta top-meta">
				<ul class="meta-gestures clearfix">
        			<li><?php argo_posted_on(); ?></li>
    				<li class="meta-comments"><span class="comments-link"><?php comments_popup_link( 'Comment', '<strong>1</strong> Comment ', ' <strong>%</strong> Comments' ); ?></span></li>
    			</ul>
    		</div><!-- /.post-meta -->
 		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- / entry header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<div class="post-meta bottom-meta">
            
            <?php if ( argo_has_custom_taxonomy( get_the_ID() ) ): ?>
				<ul class="labels clearfix">
					<li id="term-view">View more:</li>
            		<?php argo_the_post_labels( get_the_ID() ); ?>
        		</ul>
        	<?php endif; ?>

        <?php if ( argo_has_categories_or_tags() ): ?>
    		<h5>Filed under: <?php echo argo_the_categories_and_tags(); ?></h5>
    	<?php endif; ?>
          
            </div><!-- /.post-meta -->
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->

<!-- Related posts -->
<?php
if ( get_option( 'show_related_content', true ) ) :
	if ( $rel_topics = argo_get_post_related_topics( 6 ) ) :
?>
	<div id="related-posts" class="idTabs clearfix">
		<ul id="related-post-nav" class="grid2">
			<li><h4>MORE POSTS ABOUT</h4></li>
			<?php foreach ( $rel_topics as $count => $topic ) : ?>
			<li><a href="#rp<?php echo $count; ?>"><?php echo $topic->name; ?></a></li>
			<?php endforeach; ?>
		</ul>
	<div class="items grid6">
		<?php foreach ( $rel_topics as $count => $topic ): ?>
			<div id="rp<?php echo $count; ?>">  
				<?php $rel_posts = argo_get_recent_posts_for_term( $topic, 3 ); ?>
				<ul>
					<?php $top_post = array_shift( $rel_posts ); ?>
					<li class="top-related clearfix">
						<h3><a href="<?php echo get_permalink( $top_post->ID ); ?>" title="<?php echo esc_attr($topic->name); ?>"><?php echo $top_post->post_title; ?></a></h3>

						<?php if ( has_post_thumbnail( $top_post->ID ) ) { ?>
							<img src="<?php echo argo_get_post_thumbnail_src( $top_post, '60x60' ); ?>" alt="related" width="60" height="60" />
						<?php } ?>
						<p><?php echo argo_get_excerpt( $top_post ); ?> <a href="<?php echo esc_url( get_permalink( $top_post->ID ) ); ?>" title="<?php echo esc_attr($topic->name); ?>"><b>Read More</b></a></p>
					</li>
					<?php foreach ( $rel_posts as $rel_post ): ?>
					<li><a href="<?php echo esc_url( get_permalink( $rel_post->ID ) ); ?>" title="<?php echo esc_attr($topic->name); ?>"><?php echo $rel_post->post_title; ?></a></li>
					<?php endforeach; ?>
				</ul>
				<p><a href="<?php echo esc_url( get_term_link( $topic ) ); ?>" title="<?php echo esc_attr($topic->name); ?>" target="_blank"><strong>view all <?php echo $topic->name; ?> posts</strong></a></p>
			</div> <!-- /#rpX -->
		<?php endforeach; ?>
	</div> <!-- /.items -->
</div> <!-- /#related-posts -->
<?php
	endif; // if ( $rel_topics )
endif; ?>
