<?php
/*
 * The default template for displaying content
 *
 * @package Largo
 */
$tags = of_get_option( 'tag_display' );
$hero_class = largo_hero_class( $post->ID, FALSE );
$values = get_post_custom( $post->ID );
$featured = has_term( 'homepage-featured', 'prominence' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>

	<?php
		// Special treatment for posts that are in the Homepage Featured prominence taxonomy term and have thumbnails or videos.
		if ( $featured && ( has_post_thumbnail() || $values['youtube_url'] ) ) {
	?>
		<header>
			<div class="hero span12 <?php echo $hero_class; ?>">
			<?php
				if ( $youtube_url = $values['youtube_url'][0] ) {
					echo '<div class="embed-container">';
					largo_youtube_iframe_from_url( $youtube_url );
					echo '</div>';
				} elseif( has_post_thumbnail() ){
					echo('<a href="' . get_permalink() . '" title="' . the_title_attribute( array( 'before' => __( 'Permalink to', 'largo' ) . ' ', 'echo' => false )) . '" rel="bookmark">');
					the_post_thumbnail( 'rect_thumb' );
					echo('</a>');
				}
			?>
			</div>
		</header>
	<?php
		} // end Homepage Featured thumbnail block
		$entry_classes = 'entry-content';
		if ( $featured ) $entry_classes .= ' span10 with-hero';
		echo '<div class="' . $entry_classes . '">';

		if ( largo_has_categories_or_tags() && $tags === 'top' && largo_top_term() ) { ?>
			<h5 class="top-tag"><?php largo_top_term( $args = array( 'echo' => FALSE ) ) ?></h5>
		<?php }

		if ( !$featured ) {
			echo '<div class="has-thumbnail '.$hero_class.'"><a href="' . get_permalink() . '">' . get_the_post_thumbnail() . '</a></div>';
		}
	?>

	 	<h2 class="entry-title">
	 		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute( array( 'before' => __( 'Permalink to', 'largo' ) . ' ' ) )?>" rel="bookmark"><?php the_title(); ?></a>
	 	</h2>

	 	<h5 class="byline"><?php largo_byline(); ?></h5>

		<?php largo_excerpt( $post, 5, null, null, true, false ); ?>

		<?php if ( !is_home() && largo_has_categories_or_tags() && $tags === 'btm' ) { ?>
			<h5 class="tag-list"><strong><?php _e('More about:', 'largo'); ?></strong> <?php largo_categories_and_tags( 8 ); ?></h5>
		<?php } ?>

		</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
