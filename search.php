<?php
/**
 * The template for displaying Search Results pages.
 */
get_header();
?>

<div id="content" class="stories search-results span8" role="main">
	<?php if ( have_posts() ) {
		get_search_form(); ?>

		<h3 class="recent-posts clearfix">
			<?php
				printf( __('Your search for <span class="search-term">%s</span> returned ', 'largo'), get_search_query() );
				printf( _n( '%s result', '%s results', $wp_query->found_posts ), number_format_i18n( $wp_query->found_posts ) );
			?>
		</h3>

		<?php
			while ( have_posts() ) : the_post();
				get_template_part( 'content', 'search' );
			endwhile;
    		largo_content_nav( 'nav-below' );
    	} else {
			get_template_part( 'content', 'not-found' );
		}
    ?>
</div><!--#content-->

<?php get_sidebar(); ?>
<?php get_footer(); ?>