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
		<?php
		if ( 'standard' == $opt['header_style'] ) {
			//need to set a size, make this responsive, etc
			?>
			<div class="full series-banner"><?php the_post_thumbnail( 'full' ); ?></div>
		<?php
		} else {
			the_content();
		}
		?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php edit_post_link(__('Edit This Series Landing', 'largo'), '<h5 class="byline"><span class="edit-link">', '</span></h5>'); ?>

		<div class="description">
			<?php echo apply_filters( 'the_content', $post->post_excerpt ); ?>
		</div>
	</section>
	</div><!-- end main div -->
	<div id="series-main" class="row-fluid clearfix">
<?php endif; ?>


<?php // display left rail
if ( 'three-column' == $opt['cftl_layout'] ) get_sidebar( 'series-left' );
?>

<div id="content" class="span<?php echo $content_span[ $opt['cftl_layout'] ]; ?>" role="main">
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
    'post_type' => 'post',
    'taxonomy' => 'series',
    'term' => $series,
    'order' => 'DESC',
    'posts_per_page' => $opt['per_page']
  );

  //change args as needed
  if ('ASC' == $opt['post_order'] ) $args['order'] = 'ASC';

  //other changes handled by filters from cftl-series-order.php

	//build the query
	$wp_query = new WP_Query($args);

	// and finally wind the posts back so we can go through the loop as usual
	while ( have_posts() ) : the_post();
		get_template_part( 'content', 'series' );
	endwhile;

	largo_content_nav( 'nav-below' );

	$wp_query = $old_query;
	wp_reset_postdata();
} ?>

</div><!-- /.grid_8 #content -->

<?php // display left rail
if ($opt['cftl_layout'] != 'one-column') : ?>
	<?php get_sidebar('series-right'); ?>
<?php endif; ?>


<?php //display series footer
if ( $opt['footer_enabled'] ) : ?>
	<section id="series-footer">
		<?php
			//custom footer html
			echo apply_filters( 'the_content', $opt['footerhtml'] );
			//footer widget region
			if ( is_active_sidebar( $post->post_name . "_footer" ) ) : ?>
			<aside id="sidebar-bottom">
			<?php dynamic_sidebar( $post->post_name . "_footer" ); ?>
			</aside>
			<?php endif;
		?>
	</section>
<?php endif; ?>

<!-- /.grid_4 -->
<?php get_footer(); ?>
