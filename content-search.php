<?php
/**
 * The template for displaying search results
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
	<header>
 		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
	</header><!-- / entry header -->

	<div class="entry-content">
		<?php largo_excerpt( $post, 3, false ); ?>


	</div><!-- .entry-content -->

	<?php if($post->post_type == 'post') { ?>
		<footer class="post-meta bottom-meta">
			<h5 class="byline"><?php largo_byline(); ?></h5>
		</footer>
	<?php } ?>
</article><!-- #post-<?php the_ID(); ?> -->