<?php
/**
 * The template for displaying primary featured content
 */
global $tags;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('span3'); ?>>
	<?php if ( is_home() && largo_has_categories_or_tags() && $tags === 'top' ) { ?>
 		<h5 class="top-tag"><?php largo_categories_and_tags( 1 ); ?></h5>
 	<?php } ?>

	<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>

		<h2 class="entry-title">
			<a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h2>
</article><!-- #post-<?php the_ID(); ?> -->