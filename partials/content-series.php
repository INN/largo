<?php
/**
 * The template for displaying content on a series landing page
 */
global $opt;	// get display options for the loop
$tags = of_get_option ('tag_display');

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>

	<header>

 		<?php if ( isset($opt['show']['tags']) && $opt['show']['tags'] && largo_has_categories_or_tags() && $tags === 'top' && largo_top_term() ) : ?>
			<h5 class="top-tag"><?php largo_top_term() ?></h5>
		<?php endif ?>

 		<h2 class="entry-title">
 			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute( array( 'before' => __( 'Permalink to', 'largo' ) . ' ' ) )?>" rel="bookmark"><?php the_title(); ?></a>
 		</h2>

 		<?php if ( isset($opt['show']['byline']) && $opt['show']['byline'] ) : ?>
 		<h5 class="byline"><?php largo_byline(); ?></h5>
 		<?php endif; ?>
	</header><!-- / entry header -->

	<div class="entry-content">
 		<?php if ( isset($opt['show']['image']) && $opt['show']['image'] ) : ?>
			<div class="has-thumbnail <?php largo_hero_class(get_the_ID()); ?>">
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail(); ?>
				</a>
			</div>
		<?php endif; ?>

 		<?php if ( isset($opt['show']['excerpt']) && $opt['show']['excerpt'] ) :
			largo_excerpt( $post, 5, null, null, true, false );
		endif; ?>

		<?php if ( isset($opt['show']['tags']) && $opt['show']['tags'] && largo_has_categories_or_tags() && $tags === 'btm' ) { ?>
    		<h5 class="tag-list"><strong><?php _e('Filed under:', 'largo'); ?></strong> <?php largo_categories_and_tags( 8 ); ?></h5>
    	<?php } ?>

	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
