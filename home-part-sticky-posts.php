<?php
global $ids;
//$query = new WP_Query( 'p=' . $sticky[0] );

$sticky = get_option( 'sticky_posts' );
$args = array(
	'posts_per_page' => 1,
	'post__in'  => $sticky,
	'ignore_sticky_posts' => 1
);
$query = new WP_Query( $args );

if ( $query->have_posts() ) {
	while ( $query->have_posts() ) : $query->the_post();
		if ( $sticky && $sticky[0] && ! is_paged() ) {
?>

			<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix sticky '); ?>>

<?php
			// if the sticky post is part of a series, see if there are any other posts in that series
			if ( largo_post_in_series() ) {
				$feature = largo_get_the_main_feature();
				$feature_posts = largo_get_recent_posts_for_term( $feature, 3, 1 );
			}

			// if there are related posts, modify the sticky posts wrapper div so we have room to show them
			if ( $feature_posts ) {
?>
				<div class="sticky-related row-fluid clearfix">
					<div class="sticky-main-feature span8">
<?php
			// otherwise we'll just show the single sticky post
			} else {
?>
				<div class="sticky-solo row-fluid clearfix">
					<div class="sticky-main-feature span12">

<?php
			} // end feature_posts

			// if we have a thumbnail image, show it
			if ( has_post_thumbnail() ) {
?>
						<div class="image-wrap">
							<h4><?php _e('FEATURED', 'largo'); ?></h4>
							<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
						</div>
<?php
			} else {
?>
						<h4 class="no-image"><?php _e('FEATURED', 'largo'); ?></h4>
<?php
			} // end thumbnail
?>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php
							largo_excerpt( $post, 4, false );
							$ids[] = get_the_ID();
						?>
					</div> <!-- end sticky-main-feature -->

<?php
			//if the sticky post is in a series, show up to 3 other posts in that series
			if ( $feature_posts ) {
?>
					<div class="sticky-features-list span4">
						<ul>
							<li><h4><?php _e('More from', 'largo'); ?><br /><span class="series-name"><?php echo $feature->name; ?></span></h4></li>
							<?php
								foreach ( $feature_posts as $feature_post ):
									printf( '<li><a href="%1$s">%2$s</a></li>',
										esc_url( get_permalink( $feature_post->ID ) ),
										esc_attr( get_the_title( $feature_post->ID ) )
									);
								endforeach;
								if ( count( $feature_posts ) == 3 )
									printf( __('<li class="sticky-all"><a href="%1$s">Full&nbsp;Coverage&nbsp;&rarr;</a></li>', 'largo'),
										esc_url( get_term_link( $feature ) )
									);
							?>
						</ul>
					</div>
<?php
			} // feature_posts
?>
				</div> <!-- end sticky-solo or sticky-related -->
			</article>
<?php
		} // is_paged
	endwhile;
}
?>