<?php
/**
 * The template for displaying primary featured content
 */
global $tags;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix row-fluid'); ?>>

	<div class="span4">
		<?php if ( is_home() && largo_has_categories_or_tags() && $tags === 'top' ) { ?>
	 		<h5 class="top-tag"><?php largo_categories_and_tags( 1 ); ?></h5>
	 	<?php } ?>

		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
	</div>

	<div class="span8">
		<header>
	 		<h2 class="entry-title">
	 			<a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
	 		</h2>

	 		<h5 class="byline"><?php largo_byline(); ?></h5>
		</header><!-- / entry header -->

		<div class="entry-content">
			<?php largo_excerpt( $post, 5, true, '', true, false ); ?>
		</div><!-- .entry-content -->
	</div>
</article><!-- #post-<?php the_ID(); ?> -->