<?php
/**
 * The template for displaying argolinks posts on archive/search pages
 */
$custom = get_post_custom( $post->ID );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
	<header>
		<h2 class="entry-title"><?php echo ( isset( $custom["argo_link_url"][0] ) ) ? '<a href="' . $custom["argo_link_url"][0] . '">' . get_the_title() . '</a>' : get_the_title(); ?></h2>
	</header>

	<div class="entry-content">
		<?php
			if ( isset( $custom["argo_link_description"][0] ) )
				echo '<p class="description">' . $custom["argo_link_description"][0] . '</p>';
			if ( isset($custom["argo_link_source"][0] ) && ( $custom["argo_link_source"][0] != '' ) ) {
		        echo '<p class="source">' . __('Source: ', 'largo') . '<span>';
		        echo ( isset( $custom["argo_link_url"][0] ) ) ? '<a href="' . $custom["argo_link_url"][0] . '">' . $custom["argo_link_source"][0] . '</a>' : $custom["argo_link_source"][0];
		        echo '</span></p>';
		    }
		?>
		<p class="posted-on">Posted on: <?php the_time('F j, Y') ?></p>
	</div>
</article>
