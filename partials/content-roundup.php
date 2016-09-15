<?php
/*
 * The template for displaying posts from the Roundup post type from INN/link-roundups
 *
 * @package Largo
 * @since 0.5.4
 * @link https://github.com/INN/link-roundups
 * @link https://wordpress.org/plugins/link-roundups/
 */
$tags = of_get_option( 'tag_display' );
$hero_class = largo_hero_class( $post->ID, FALSE );
$values = get_post_custom( $post->ID );
$featured = has_term( 'homepage-featured', 'prominence' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>

	<?php
	// Special treatment for posts that are in the Homepage Featured prominence taxonomy term and have thumbnails or videos.
	$entry_classes = 'entry-content';
	?>
		<div class="<?php echo $entry_classes; ?>">

		<?php if ( largo_has_categories_or_tags() && $tags === 'top' && largo_top_term() ) : ?> 
			<h5 class="top-tag"><?php largo_top_term( $args = array( 'echo' => FALSE ) ); ?></h5>
		<?php endif; ?>

		<h2 class="entry-title">
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute( array( 'before' => __( 'Permalink to', 'largo' ) . ' ' ) )?>" rel="bookmark"><?php the_title(); ?></a>
		</h2>

		<h5 class="byline visuallyhidden"><?php largo_byline(); ?></h5>

		<?php the_content(); ?>

		</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
