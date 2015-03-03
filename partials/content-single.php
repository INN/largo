<?php
/**
 * The template for displaying content in the single.php template
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'hnews item' ); ?> itemscope itemtype="http://schema.org/Article">

	<?php do_action('largo_before_post_header'); ?>

	<header>

		<h5 class="top-date"><?php largo_time(); ?></h5>
		<h5 class="top-tag"><?php largo_top_term(); ?></h5>

 		<h1 class="entry-title" itemprop="headline"><?php the_title(); ?></h1>
 		<?php if ( $subtitle = get_post_meta( $post->ID, 'subtitle', true ) )
 			echo '<h2 class="subtitle">' . $subtitle . '</h2>';
 		?>
 		<h5 class="byline"><?php largo_byline(); ?></h5>

 		<?php largo_post_metadata( $post->ID ); ?>

	</header><!-- / entry header -->

	<?php
		do_action('largo_after_post_header');

		get_template_part( 'partials/single', 'hero' );

		do_action('largo_after_hero');
	?>

	
	<?php get_sidebar(); ?>

	<section class="entry-content clearfix" itemprop="articleBody">
		
		<?php largo_entry_content( $post ); ?>
		
	</section>

	<?php do_action('largo_after_post_content'); ?>

</article>
