<?php
/**
 * The template for displaying content in the single.php template
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'hnews item' ); ?> itemscope itemtype="http://schema.org/Article">
	<?php do_action('largo_before_post_header'); ?>
	<header>
 		<h1 class="entry-title" itemprop="headline"><?php the_title(); ?></h1>
 		<h5 class="byline"><?php largo_byline(); ?></h5>

 		<?php
 			if ( of_get_option( 'social_icons_display' ) === 'top' || of_get_option( 'social_icons_display' ) === 'both' )
 				largo_post_social_links();
 		?>
 		<meta itemprop="description" content="<?php echo strip_tags(largo_excerpt( $post, 5, false, '', false ) ); ?>" />
 		<meta itemprop="datePublished" content="<?php echo get_the_date( 'c' ); ?>" />
 		<meta itemprop="dateModified" content="<?php echo get_the_modified_date( 'c' ); ?>" />
 		<?php
 			if ( has_post_thumbnail( $post->ID ) ) {
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' );
				echo '<meta itemprop="image" content="' . $image[0] . '" />';
			}
 		?>
	</header><!-- / entry header -->
	<?php do_action('largo_after_post_header'); ?>
	<div class="entry-content clearfix" itemprop="articleBody">
		<?php largo_entry_content( $post ); ?>
	</div><!-- .entry-content -->
	<?php do_action('largo_after_post_content'); ?>
	<footer class="post-meta bottom-meta">

    <?php if ( of_get_option( 'clean_read' ) === 'footer' ) : ?>
    <div class="clean-read-container clearfix">
 			<a href="#" class="clean-read"><?php _e("View as 'Clean Read'", 'largo') ?></a>
 		</div>
 		<?php endif; ?>

	</footer><!-- /.post-meta -->
	<?php do_action('largo_after_post_footer'); ?>
</article><!-- #post-<?php the_ID(); ?> -->