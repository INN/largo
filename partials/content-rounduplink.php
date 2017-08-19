<?php
/*
 * The template for displaying posts from the Roundup Link post type from INN/link-roundups
 *
 * @package Largo
 * @since 0.5.4
 * @link https://github.com/INN/link-roundups
 * @link https://wordpress.org/plugins/link-roundups/
 */

$custom = get_post_custom($post->ID);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
	<header>
		<h2 class="entry-title"><?php echo ( isset( $custom['lr_url'][0] ) ) ? '<a href="' . $custom['lr_url'][0] . '">' . get_the_title() . '</a>' : get_the_title(); ?></h2>
	</header>

	<div class="entry-content">
	<?php
	if ( isset( $custom['lr_desc'][0] ) ) {
		echo '<p class="description">';
		echo $custom['lr_desc'][0];
		echo '</p>';
	}
	if ( isset($custom['lr_source'][0] ) ) {
		echo '<p class="source">' . __('Source: ', 'largo') . '<span>';
		echo ( isset( $custom['lr_url'][0] ) ) ? '<a href="' . $custom['lr_url'][0] . '">' . $custom['lr_source'][0] . '</a>' : $custom['lr_source'][0];
		echo '</span></p>';
	}
	?>
	<p class="posted-on">Posted on: <?php the_time('F j, Y') ?></p>
	</div>
</article> <!-- /.post-lead -->


