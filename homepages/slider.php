<?php
/**
 * Home Template: Slider
 * Description: An animated carousel of featured stories with large images. Not recommended but available for backward compatibility.
 * Sidebars: Homepage Left Rail (An optional widget area that, when enabled, appears to the left of the main content area on the homepage)
 */
?><div id="homepage-slider" class="carousel slide clearfix">
	<div class="carousel-inner">
		<div class="top-story item active">
			<?php
			global $shown_ids;
			$items = 0;
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
				while ( $topstory->have_posts() ) : $topstory->the_post(); $shown_ids[] = get_the_ID(); ?>
					<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'large' ); ?></a>
				    <div class="carousel-caption">
				    	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				        <h5 class="byline"><?php largo_byline(); ?></h5>
				        <?php largo_excerpt( $post, 4, false ); ?>
				    </div>
				<?php
					$items++;
				endwhile;
			endif; // end more featured posts ?>
		</div>
		<?php $substories = largo_get_featured_posts( array(
			'tax_query' => array(
				array(
					'taxonomy' 	=> 'prominence',
					'field' 	=> 'slug',
					'terms' 	=> 'homepage-featured'
				)
			),
			'showposts'		=> 4,
			'post__not_in' 	=> $shown_ids
		) );
		if ( $substories->have_posts() ) :
			while ( $substories->have_posts() ) : $substories->the_post(); $shown_ids[] = get_the_ID(); ?>
				<div class="item">
					<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'large' ); ?></a>
					<div class="carousel-caption">
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<h5 class="byline"><?php largo_byline(); ?></h5>
						<?php largo_excerpt( $post, 4, false ); ?>
					</div>
				</div>
			<?php $items++;
			endwhile;
		endif; ?>
	</div>
	<?php if ( $items > 1 ) { ?>
	<!-- Carousel nav -->
	<a class="carousel-control left" href="#homepage-slider" data-slide="prev">&lsaquo;</a>
	<a class="carousel-control right" href="#homepage-slider" data-slide="next">&rsaquo;</a>
	<?php } ?>
</div>