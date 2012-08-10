<?php
/**
 * The template for displaying content in the single.php template
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header>
 		<h1 class="entry-title"><?php the_title(); ?></h1>
 		<div class="post-meta">
 			<h5 class="byline"><?php largo_byline(); ?> | <span class="comments-link"><?php comments_popup_link( 'Leave a Comment', '<strong>1</strong> Comment ', ' <strong>%</strong> Comments' ); ?></span><?php edit_post_link('Edit This Post', ' | <span class="edit-link">', '</span>'); ?></h5>
 		</div>
 		<div class="post-social top">
 			<div class="left">
 				<span class="twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-url="<?php echo the_permalink(); ?>" data-text="<?php echo the_title(); ?>" <?php if ( of_get_option( 'twitter_link' ) ) : ?>data-via="<?php echo twitter_url_to_username( of_get_option( 'twitter_link' ) ); ?>"<?php endif; ?> data-count="none">Tweet</a></span>
 				<span class="facebook"><fb:like href="<?php echo the_permalink(); ?>" send="false" layout="button_count" show_faces="false" action="recommend" font=""></fb:like></span>
 			</div>
 			<div class="right">
 				<span class='st_sharethis' displayText='Share'></span>
 				<span class='st_email' displayText='Email'></span>
 				<span class="print"><a href="#" onclick="window.print()" title="print this article" rel="nofollow"><i class="icon-print"></i> Print</a></span>
 			</div>
 		</div>

	</header><!-- / entry header -->

	<div class="entry-content clearfix">

		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<div class="post-social bottom">
 			<div class="left">
 				<span class="twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-url="<?php echo the_permalink(); ?>" data-text="<?php echo the_title(); ?>" <?php if ( of_get_option( 'twitter_link' ) ) : ?>data-via="<?php echo twitter_url_to_username( of_get_option( 'twitter_link' ) ); ?>"<?php endif; ?> data-count="none">Tweet</a></span>
 				<span class="facebook"><fb:like href="<?php echo the_permalink(); ?>" send="false" layout="button_count" show_faces="false" action="recommend" font=""></fb:like></span>
 			</div>
 			<div class="right">
 				<span class='st_sharethis' displayText='Share'></span>
 				<span class='st_email' displayText='Email'></span>
 				<span class="print"><a href="#" onclick="window.print()" title="print this article" rel="nofollow"><i class="icon-print"></i> Print</a></span>
 			</div>
 		</div>
	<footer class="post-meta bottom-meta">

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
<?php if ( largo_show_author_box( $post->ID ) ) : ?>
<div class="author-box clearfix">
	<h4>About <?php echo esc_attr( get_the_author() ); ?><span><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>" rel="author" title="See all posts by <?php the_author_meta('display_name'); ?>">More by this author</a></span></h4>

	<?php if (has_gravatar( get_the_author_meta('user_email') ) ) :
			echo get_avatar( get_the_author_meta('ID'), 96 );
		endif;
	?>

	<?php if ( get_the_author_meta( 'description' ) ) : ?>
		<p><?php the_author_meta( 'description' ); ?></p>
	<?php endif; ?>

	<ul class="social-links">
		<?php if ( get_the_author_meta( 'user_email' ) ) : ?>
		<li class="email">
			<a href="mailto:<?php echo esc_attr( get_the_author_meta( 'user_email' ) ); ?>" title="e-mail <?php echo esc_attr( get_the_author() ); ?>"><i class="icon-envelope icon-white"></i> email</a>
		</li>
		<?php endif; ?>

		<?php if ( get_the_author_meta( 'fb' ) ) : ?>
		<li class="facebook">
			<div class="fb-subscribe" data-href="<?php echo esc_url( get_the_author_meta( 'fb' ) ); ?>" data-layout="button_count" data-show-faces="false" data-width="225"></div>
		</li>
		<?php endif; ?>

		<?php if ( get_the_author_meta( 'twitter' ) ) : ?>
		<li class="twitter">
			<a href="<?php echo esc_url( get_the_author_meta( 'twitter' ) ); ?>" class="twitter-follow-button" data-show-count="false" data-lang="en">Follow @twitterapi</a>
		</li>
		<?php endif; ?>

		<?php if ( get_the_author_meta( 'googleplus' ) ) : ?>
		<li class="gplus">
			<a href="<?php echo esc_url( get_the_author_meta( 'googleplus' ) ); ?>" title="<?php echo esc_attr( get_the_author() ); ?> on Google+" rel="me"><img src="<?php bloginfo( 'template_directory' ); ?>/img/gplus-19.png" alt="Google+" /></a>
		</li>
		<?php endif; ?>
	</ul>
</div>
<?php endif; ?>

<!-- Related posts -->
<?php
if ( of_get_option( 'show_related_content' ) ) :
	if ( $rel_topics = largo_get_post_related_topics( 6 ) ) :
?>
	<div id="related-posts" class="idTabs row-fluid clearfix">
		<ul id="related-post-nav" class="span4">
			<li><h4>More Posts About</h4></li>
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
	<div class="previous"><?php previous_post_link( '<h5>Older Post</h5> %link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'largo' ) . '</span> %title' ); ?></div>
	<div class="next"><?php next_post_link( '<h5>Newer Post</h5> %link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'largo' ) . '</span>' ); ?></div>
</nav><!-- #nav-below -->


