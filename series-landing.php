<?php
/**
 * Template Name: Series Landing Page Default
 * Description: The default template for a series landing page. Many display options are set via admin.
 */

get_header();

// Load up our meta data and whatnot
the_post();

//make sure it's a landing page.
if ( 'cftl-tax-landing' == $post->post_type ) {
	$opt = get_post_custom( $post->ID );
	foreach( $opt as $key => $val ) {
		$opt[ $key ] = $val[0];
	}
	$opt['show'] = maybe_unserialize($opt['show']);	//make this friendlier
	if ( 'all' == $opt['per_page'] ) $opt['per_page'] = -1;
	/**
	 * $opt will look like this:
	 *
	 *	Array (
	 *		[header_enabled] => boolean
	 *		[show_series_byline] => boolean
	 *		[show_sharebar] => boolean
	 *		[header_style] => standard|alternate
	 *		[cftl_layout] => one-column|two-column|three-column
	 *		[per_page] => integer|all
	 *		[post_order] => ASC|DESC|top, DESC|top, ASC
	 *		[footer_enabled] => boolean
	 *		[footerhtml] => {html}
	 *		[show] => array with boolean values for keys byline|excerpt|image|tags
	 *	)
	 *
	 * The post description is stored in 'excerpt' and the custom HTML header is the post content
	 */
}

// #content span width helper
$content_span = array( 'one-column' => 12, 'two-column' => 8, 'three-column' => 5 );
?>

<?php if ( $opt['header_enabled'] ) : ?>
	<section id="series-header" class="span12">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php
		if ( $opt['show_series_byline'] )
			echo '<h5 class="byline">' . largo_byline( false ) . '</h5>';
		if ( $opt['show_sharebar'] )
			largo_post_social_links();
		?>
		<div class="description">
			<?php echo apply_filters( 'the_content', $post->post_excerpt ); ?>
		</div>
		<?php
		if ( 'standard' == $opt['header_style'] ) {
			//need to set a size, make this responsive, etc
			?>
			<div class="full series-banner full-image"><?php the_post_thumbnail( 'full' ); ?></div>
		<?php
		} else {
			the_content();
		}
		?>
	</section>
	</div><!-- end main div -->
	<div id="series-main" class="row-fluid clearfix">
<?php endif; ?>


<?php // display left rail
if ( 'three-column' == $opt['cftl_layout'] ) : ?>
	<aside id="sidebar-left" class="span3">
		<div class="widget-area" role="complementary">
			<?php dynamic_sidebar( $opt['left_region'] ); ?>
		</div>
	</aside>
<?php
endif;
?>

<div id="content" class="span<?php echo $content_span[ $opt['cftl_layout'] ]; ?> stories" role="main">
<?php

global $wp_query;

// Make sure we're actually a series page, and pull posts accordingly
if ( isset( $wp_query->query_vars['term'] )
			&& isset( $wp_query->query_vars['taxonomy'] )
			&& 'series' == $wp_query->query_vars['taxonomy'] ) {

	$series = $wp_query->query_vars['term'];
	$old_query = $wp_query;

	//default query args: by date, descending
	$args = array(
		'p' 				=> '',
		'post_type' 		=> 'post',
		'taxonomy' 			=> 'series',
		'term' 				=> $series,
		'order' 			=> 'DESC',
		'posts_per_page' 	=> $opt['per_page']
	);

	//stores original 'paged' value in 'pageholder'
	global $cftl_previous;
	if ( isset($cftl_previous['pageholder']) && $cftl_previous['pageholder'] > 1 ) {
		$args['paged'] = $cftl_previous['pageholder'];
		global $paged;
		$paged = $args['paged'];
	}

	//change args as needed
	//these unusual WP_Query args are handled by filters defined in cftl-series-order.php
	switch ( $opt['post_order'] ) {
		case 'ASC':
			$args['order'] = 'ASC';
			break;
		case 'custom':
			$args['orderby'] = 'series_custom';
			break;
		case 'featured, DESC':
		case 'featured, ASC':
			$args['orderby'] = $opt['post_order'];
			break;
	}

	//build the query, using the original as a guide for pagination and whatnot
	$all_args = array_merge( $old_query->query_vars, $args );
	$wp_query = new WP_Query($all_args);

	// and finally wind the posts back so we can go through the loop as usual
	while ( $wp_query->have_posts() ) : $wp_query->the_post();
		get_template_part( 'content', 'series' );
	endwhile;

	largo_content_nav( 'nav-below' );

	$wp_query = $old_query;
	wp_reset_postdata();
} ?>

</div><!-- /.grid_8 #content -->

<?php // display left rail
if ($opt['cftl_layout'] != 'one-column') : ?>
<aside id="sidebar" class="span4">
	<?php do_action('largo_before_sidebar_content'); ?>
	<div class="widget-area" role="complementary">
		<?php
			do_action('largo_before_sidebar_widgets');
			dynamic_sidebar( $opt['right_region'] );
			do_action('largo_after_sidebar_widgets');
		?>
	</div><!-- .widget-area -->
	<?php do_action('largo_after_sidebar_content'); ?>
</aside>

<?php
endif;

//display series footer
if ( 'none' != $opt['footer_style'] ) : ?>
	<section id="series-footer">
		<?php
			//custom footer html
			if ( 'custom' == $opt['footer_style']) {
				echo apply_filters( 'the_content', $opt['footerhtml'] );
			} else if ( 'widget' == $opt['footer_style'] && is_active_sidebar( $post->post_name . "_footer" ) ) { ?>
				<aside id="sidebar-bottom">
				<?php dynamic_sidebar( $post->post_name . "_footer" ); ?>
				</aside>
			<?php }
		?>
	</section>
<?php endif; ?>

<!-- /.grid_4 -->
<?php get_footer(); ?>