<?php
	$tax_query = array(
		array(
			'taxonomy' 	=> 'prominence',
			'field' 	=> 'slug',
			'terms' 	=> 'taxonomy-featured'
		)
	);

	// Filter the posts to match the current page taxonomy term
	if ( is_category() || is_tag() || is_tax() ) {
		$queried_object = get_queried_object();
		$tax_query[] = array(
			'taxonomy' => $queried_object->taxonomy,
			'field' => 'id',
			'terms' => $queried_object->term_id,
		);
	}

	$featured_posts = largo_get_featured_posts( array(
		'tax_query' => $tax_query,
		'showposts' => 4
	) );

	if ( $featured_posts->found_posts ):
?>
<div class="primary-featured-post">
	<?php 
	$_the_post = $post;
	while ( $featured_posts->have_posts() ) {
		$featured_posts->the_post();
		get_template_part( 'content', 'primary-featured' );
	}

	// Revert the post data
	$post = $_the_post;
	if ( !empty($post) ) {
		setup_postdata( $post );
	}
	?>
</div>

<?php endif; ?>