<?php
/**
 * The template for displaying content in lists and other places where it's small
 * Typically will be wrapped in an <li> or ohter container
 */
 global $post;

if ( get_the_post_thumbnail() )
		echo '<a href="' . get_permalink() . '"/>' . get_the_post_thumbnail( get_the_ID(), 'thumbnail', array('class'=>'alignleft') ) . '</a>';
?>
<h4><a href="<?php the_permalink(); ?>" title="Read: <?php esc_attr( the_title('','', FALSE) ); ?>"><?php the_title(); ?></a></h4>
<h5 class="byline"><time class="entry-date updated dtstamp pubdate" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php largo_time(); ?></time></h5>
<?php

	if ($post->post_excerpt) {
		echo '<p>' . $post->post_excerpt . '</p>';
	} else {
		echo '<p>' . largo_trim_sentences($post->post_content, 2) . '</p>';
	}
?>