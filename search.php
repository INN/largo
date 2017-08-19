<?php
/**
 * The template for displaying Search Results pages.
 */
get_header();
?>

<div id="content" class="stories archive search-results span8" role="main">
	<?php if (of_get_option('use_gcs') && of_get_option('gcs_id')) { ?>
		<h1>
			<?php
				printf( __('Search results for <span class="search-term">%s</span>', 'largo'), get_search_query() );
			?>
		</h1>

		<?php
			/**
			 * Fires before the Google Custom Search container
			 *
			 * @since Largo 0.5.5
			 */
			do_action('largo_search_gcs_before_container');
		?>

		<div class="gcs_container">
			<script>
				(function() {
					var cx = '<?php echo of_get_option('gcs_id'); ?>';
					var gcse = document.createElement('script');
					gcse.type = 'text/javascript';
					gcse.async = true;
					gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
						'//www.google.com/cse/cse.js?cx=' + cx;
					var s = document.getElementsByTagName('script')[0];
					s.parentNode.insertBefore(gcse, s);
				})();
			</script>

			<gcse:searchbox
				gname="largoGCSE"
				overlayResults="false"
				queryParameterName="s"></gcse:searchbox>
			<?php if (is_search()) { ?>
			<gcse:searchresults
				gname="largoGCSE"
				overlayResults="false"
				queryParameterName="s"></gcse:searchresults>
			<?php } ?>

			<?php if (is_search() && !isset($_GET['s'])) { ?>
			<script type="text/javascript">
				(function() {
					var setQuery = function() {
						var query = '<?php echo get_search_query(); ?>';

						google.setOnLoadCallback(function() {
							var el = google.search.cse.element.getElement('largoGCSE');
								el.execute(query);
						});
					};

					window.__gcse = {
						callback: setQuery
					};
				}());
			</script>
			<?php } ?>
		</div>

		<?php
			/**
			 * Fires after the Google Custom Search container
			 *
			 * @since Largo 0.5.5
			 */
			do_action('largo_search_gcs_after_container');
		?>

	<?php } else { ?>

		<?php if ( have_posts() ) {

			/**
			 * Fires before the non-GCS search form
			 *
			 * @since Largo 0.5.5
			 */
			do_action('largo_search_normal_before_form');

			get_search_form();

			/**
			 * Fires after the non-GCS search form, before the search results counter
			 *
			 * @since Largo 0.5.5
			 */
			do_action('largo_search_normal_before_results');

			?>

			<h3 class="recent-posts clearfix">
				<?php
					printf( __('Your search for <span class="search-term">%s</span> returned ', 'largo'), get_search_query() );
					printf( _n( '%s result', '%s results', $wp_query->found_posts, 'largo' ), number_format_i18n( $wp_query->found_posts ) );
					printf( '<a class="rss-link" href="%1$s"><i class="icon-rss"></i></a>', get_search_feed_link() );
				?>
			</h3>

			<?php
				while ( have_posts() ) : the_post();
					$partial = largo_get_partial_by_post_type('search', get_post_type( $post ), 'search');
					get_template_part( 'partials/content', $partial );
				endwhile;
				largo_content_nav( 'nav-below' );
			} else {
				get_template_part( 'partials/content', 'not-found' );
			}

			/**
			 * Fires after the non-GCS search results or lack-of-results
			 *
			 * @since Largo 0.5.5
			 */
			do_action('largo_search_normal_after_results');
		} ?>
</div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer();
