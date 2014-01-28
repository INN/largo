<?php
/**
 * The template for displaying content in lists and other places where it's small
 * Typically will be wrapped in an <li> or ohter container
 */
 global $post;
?>
<h4><a href="<?php the_permalink(); ?>" title="Read: <?php esc_attr( the_title('','', FALSE) ); ?>"><?php the_title(); ?></a></h4>
<?php
	if ( get_the_post_thumbnail() )
		echo '<a href="' . get_permalink() . '"/>' . get_the_post_thumbnail( get_the_ID(), '60x60', array('class'=>'alignleft') ) . '</a>';

	if ($post->post_excerpt) {
		echo '<p>' . $post->post_excerpt . '</p>';
	} else {
		echo '<p>' . largo_trim_sentences($post->post_content, 2) . '</p>';
	}
?>