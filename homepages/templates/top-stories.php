<?php
/**
 * Home Template: Top Stories
 * Description: A newspaper-like layout highlighting one Top Story on the left and others to the right. A popular layout choice!
 * Sidebars: Homepage Left Rail (An optional widget area that, when enabled, appears to the left of the main content area on the homepage)
 */

global $largo, $shown_ids, $tags;
?>
<div id="homepage-featured" class="row-fluid clearfix">
	<?php if ( is_active_sidebar('homepage-left-rail') ) { ?>
	<div class="top-story span12">
	<?php } else { ?>
	<div class="top-story span8">
	<?php }
		$topstory = largo_get_featured_posts( array(
			'tax_query' => array(
				array(
					'taxonomy' 	=> 'prominence',
					'field' 	=> 'slug',
					'terms' 	=> 'top-story'
				)
			),
			'showposts' => 1
		) );
		if ( $topstory->have_posts() ) :
			while ( $topstory->have_posts() ) : $topstory->the_post(); $shown_ids[] = get_the_ID();

				if( $has_video = get_post_meta( $post->ID, 'youtube_url', true ) ) { ?>
					<div class="embed-container">
						<iframe src="http://www.youtube.com/embed/<?php echo substr(strrchr( $has_video, "="), 1 ); ?>?modestbranding=1" frameborder="0" allowfullscreen></iframe>
					</div>
				<?php } else { ?>
					<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'large' ); ?></a>
				<?php } ?>

				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			    <h5 class="byline"><?php largo_byline(); ?></h5>
			    <?php largo_excerpt( $post, 4, false ); ?>
			    <?php if ( largo_post_in_series() ):
					$feature = largo_get_the_main_feature();
					$feature_posts = largo_get_recent_posts_for_term( $feature, 1, 1 );
					if ( $feature_posts ):
						foreach ( $feature_posts as $feature_post ): ?>

							<h4 class="related-story"><?php _e('Related:', 'largo'); ?> <a href="<?php echo esc_url( get_permalink( $feature_post->ID ) ); ?>"><?php echo get_the_title( $feature_post->ID ); ?></a></h4>
						<?php endforeach;
					endif;
				endif;
			endwhile;
		endif; // end top story ?>
	</div>

	<?php if ( !is_active_sidebar('homepage-left-rail') ) { ?>
	<div class="sub-stories span4">
		<?php
		$showposts = 6;
		$showposts = apply_filters('largo_homepage_topstories_post_count', $showposts);
		$substories = largo_get_featured_posts( array(
			'tax_query' => array(
				array(
					'taxonomy' 	=> 'prominence',
					'field' 	=> 'slug',
					'terms' 	=> 'homepage-featured'
				)
			),
			'showposts'		=> $showposts,
			'post__not_in' 	=> $shown_ids
		) );
		if ( $substories->have_posts() ) :
			$count = 1;
			while ( $substories->have_posts() ) : $substories->the_post(); $shown_ids[] = get_the_ID();
				if ($count <= 3) : ?>
					<div class="story">
			        	<?php if ( largo_has_categories_or_tags() && $tags === 'top' ) : ?>
			        		<h5 class="top-tag"><?php largo_top_term(); ?></h5>
			        	<?php endif; ?>
			        	<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			        	<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
			            <?php largo_excerpt( $post, 3, false ); ?>
			        </div>
			    <?php elseif ($count == 4) : ?>
			        <h4 class="subhead"><?php _e('More Headlines', 'largo'); ?></h4>
			        <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
			    <?php else : ?>
			        <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
			    <?php endif;
				$count++;
			endwhile;
		endif; // end more featured posts ?>
	</div>
	<?php } ?>
</div>
