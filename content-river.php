<?php
/**
 * The default template for displaying content
 */
global $tags;
$is_featured = has_term('homepage-featured', 'prominence');
$youtube_url = isset( $values['youtube_url'] ) ? esc_attr( $values['youtube_url'][0] ) : '';

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>

	<?php if ( $is_featured && ( has_post_thumbnail() || $youtube_url ) ) : ?>
	<header>
		<?php
			$hero_class = ( $youtube_url ) ? "is-image" : "is-video" ;
		?>
		<div class="hero span12 <?php echo $hero_class; ?>">
		<?php
		  $values = get_post_custom( get_the_id() );

			if($youtube_url){

				// get embed ID
				parse_str( parse_url( $youtube_url, PHP_URL_QUERY ), $var_array );
				$youtubeID = $var_array['v'];

				?>
					<div class="video-container">
						<iframe  src="//www.youtube.com/embed/<?php echo $youtubeID ?>" frameborder="0" allowfullscreen></iframe>
					</div>
				<?php
			} elseif(has_post_thumbnail()){
				the_post_thumbnail( 'full');
			}
		?>
		</div>
	</header>
	<div class="span10 with-hero entry-content">

 		<?php if ( is_home() && largo_has_categories_or_tags() && $tags === 'top' ) { ?>
	 		<h5 class="top-tag"><?php largo_top_term(); ?></h5>
	 	<?php } ?>

 		<h2 class="entry-title">
 			<a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
 		</h2>

 		<h5 class="byline"><?php largo_byline(); ?></h5>

		<?php largo_excerpt( $post, 5, true, __('Continue&nbsp;Reading&nbsp;&rarr;', 'largo'), true, false ); ?>

    <?php if ( !is_home() || ( largo_has_categories_or_tags() && $tags === 'btm' ) ) : ?>
    		<h5 class="tag-list"><strong><?php _e('Filed under:', 'largo'); ?></strong> <?php largo_categories_and_tags( 8 ); ?></h5>
    <?php endif; ?>

	</div><!-- .entry-content -->

	<?php	else : ?>

	<header>

 		<?php if ( is_home() && largo_has_categories_or_tags() && $tags === 'top' ) { ?>
	 		<h5 class="top-tag"><?php largo_top_term(); ?></h5>
	 	<?php } ?>

 		<h2 class="entry-title">
 			<a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
 		</h2>

 		<h5 class="byline"><?php largo_byline(); ?></h5>

	</header><!-- / entry header -->

	<div class="entry-content">
		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>

		<?php largo_excerpt( $post, 5, true, __('Continue&nbsp;Reading&nbsp;&rarr;', 'largo'), true, false ); ?>

    <?php if ( !is_home() || ( largo_has_categories_or_tags() && $tags === 'btm' ) ) : ?>
		<h5 class="tag-list"><strong><?php _e('Filed under:', 'largo'); ?></strong> <?php largo_categories_and_tags( 8 ); ?></h5>
		<?php endif; ?>

	</div><!-- .entry-content -->

	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->