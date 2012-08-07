<?php
/**
 * The default template for displaying content
 *
 * @package WordPress
 */
?>

	<?php if ( is_front_page() && is_sticky() && ! is_paged() ):  ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix sticky'); ?>>
		<?php if ( largo_post_in_series() ):
			$feature = largo_get_the_main_feature();
			$feature_posts = largo_get_recent_posts_for_term( $feature, 3, 1 );
			if ( $feature_posts ):
		?>
			<div class="sticky-related clearfix">
		<?php else: // feature_posts ?>
			<div class="sticky-solo clearfix">
		<?php endif; // feature_posts
		else: // largo_post_has_features ?>
			<div class="sticky-solo clearfix">
		<?php endif; // largo_post_has_features(); ?>
				<div class="sticky-main-feature">

					<?php if ( has_post_thumbnail() ): ?>
						<div class="image-wrap">
							<h4>FEATURED</h4>
							<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
						</div>
					<?php else: ?>
						<h4>FEATURED</h4>
					<?php endif; ?>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php the_excerpt(); ?>
				</div>
				<?php if ( $feature_posts ): ?>
				<ul>
					<li><h4>More from<br /><span class="series-name"><?php echo $feature->name; ?></span></h4></li>
					<?php foreach ( $feature_posts as $feature_post ): ?>
						<li><a href="<?php echo esc_url( get_permalink( $feature_post->ID ) ); ?>"><?php echo get_the_title( $feature_post->ID ); ?></a></li>
					<?php endforeach; ?>
					<?php if ( count( $feature_posts ) == 3 ): ?>
						<li class="sticky-all"><a href="<?php echo esc_url( get_term_link( $feature ) ); ?>">Full coverage <span class="meta-nav">&rarr;</span></a></li>
					<?php endif; ?>
				</ul>
				<?php endif; ?>
			</div>

	<?php else: ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
		<header>
 			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
 			<div class="post-meta">
 				<h5 class="byline"><?php largo_byline(); ?><?php edit_post_link('Edit This Post', ' | <span class="edit-link">', '</span>'); ?></h5>
 			</div>
		</header><!-- / entry header -->

		<div class="entry-content">
			<?php if ( is_search() || is_archive() ) : // Only display Excerpts for Search ?>
				<?php the_excerpt(); ?>
			<?php else : ?>
        		<?php the_content( 'Continue reading <span class="meta-nav">&rarr;</span>' ); ?>
        	<?php endif; ?>

        	<?php if ( largo_has_categories_or_tags() ): ?>
            	<div class="post-meta bottom-meta">
    				 <h5><strong>Filed under:</strong> <?php echo largo_homepage_categories_and_tags(); ?></h5>
            	</div><!-- /.post-meta -->
            <?php endif; ?>
		</div><!-- .entry-content -->

	<?php endif; //non-sticky posts ?>
</article><!-- #post-<?php the_ID(); ?> -->