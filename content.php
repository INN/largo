<?php
/**
 * The default template for displaying content
 *
 * @package WordPress
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( is_front_page() && is_sticky() && ! is_paged() ):  ?>
		<?php if ( argo_post_has_features() ):
			$feature = argo_get_the_main_feature();
			$feature_posts = argo_get_recent_posts_for_term( $feature, 3, 1 );
			if ( $feature_posts ):
		?>
			<div class="sticky-related clearfix">
				<dl>
					<dt>More from <?php echo $feature->name; ?>:</dt>
					<?php foreach ( $feature_posts as $feature_post ): ?>
						<dd><a href="<?php echo esc_url( get_permalink( $feature_post->ID ) ); ?>"><?php echo get_the_title( $feature_post->ID ); ?></a></dd>
					<?php endforeach; ?>
					<?php if ( count( $feature_posts ) == 3 ): ?>
						<dd class="sticky-all"><a href="<?php echo esc_url( get_term_link( $feature ) ); ?>">Full coverage <span class="meta-nav">&rarr;</span></a></dd>
					<?php endif; ?>
				</dl>
		<?php else: // feature_posts ?>
			<div class="sticky-solo clearfix">
		<?php endif; // feature_posts
		else: // argo_post_has_features ?>
			<div class="sticky-solo clearfix">
		<?php endif; // argo_post_has_features(); ?>

			<h5>FEATURED</h5>
			<?php if ( has_post_thumbnail() ): ?>
				<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
			<?php endif; ?>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php the_excerpt(); ?>
			</div>
	<?php else: ?>

		<header>
 			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
 			<div class="post-meta">
 				<h5 class="byline"><?php largo_byline(); ?></h5>
 			</div>
		</header><!-- / entry header -->



		<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
		<div class="entry-content">
            <?php the_content( 'Continue reading <span class="meta-nav">&rarr;</span>' ); ?>

           <?php if ( argo_has_categories_or_tags() ): ?>
            	<div class="post-meta bottom-meta">
    				 <h5><strong>Filed under:</strong> <?php echo argo_homepage_categories_and_tags(); ?></h5>
            	</div><!-- /.post-meta -->
          <?php endif; ?>
		</div><!-- .entry-content -->
		<?php endif; ?>
	<?php endif; //non-sticky posts ?>
</article><!-- #post-<?php the_ID(); ?> -->