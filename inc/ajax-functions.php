<?php
/**
 * Largo AJAX functions
 *
 * When changing the operation of these functions, be sure to test it in:
 * - the homepage, all templates
 * - category archive pages
 * - series archive pages
 * - custom series landing pages
 * - search results pages
 *
 * @package Largo
 */

if ( !function_exists( 'largo_load_more_posts_enqueue_script' ) ) {
	/**
	 * Enqueue script for "load more posts" functionality
	 *
	 * @since 0.5.3
	 * @global LARGO_DEBUG
	 */
	function largo_load_more_posts_enqueue_script() {
		$suffix = (LARGO_DEBUG)? '' : '.min';
		$version = largo_version();

		wp_enqueue_script(
			'load-more-posts',
			get_template_directory_uri() . '/js/load-more-posts'. $suffix . '.js',
			array('jquery'), $version, false
		);
	}
	add_action( 'wp_enqueue_scripts', 'largo_load_more_posts_enqueue_script' );
}

if ( !function_exists( 'largo_load_more_posts_data' ) ) {
	/**
	 * Print an HTML script tag for a post navigation element and corresponding query
	 *
	 * If you plan on plugging this function, make sure the data structure it returns includes and "is_home" key.
	 *
	 * @param string  $nav_id The unique id of the navigation element used as the trigger to load more posts
	 * @param object $the_query The WP_Query object upon which calls to load more posts will be based
	 */
	function largo_load_more_posts_data( $nav_id, $the_query ) {
		global $shown_ids, $post, $opt;

		$query = $the_query->query;

		// No sticky posts or featured posts
		$query = array_merge(array(
			'post__not_in' => $shown_ids,
		), $query );

		$config = array(
			'nav_id' => $nav_id,
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'paged' => ( !empty( $the_query->query_vars['paged'] ) )? $the_query->query_vars['paged'] : 1,
			'query' => $query,
			'is_home' => $the_query->is_home(),
			'is_series_landing' => $post->post_type == 'cftl-tax-landing' ? true : false,
			'no_more_posts' => apply_filters( 'largo_no_more_posts_text', "You've reached the end!", $nav_id, $the_query )
		);

		if( $post->post_type == 'cftl-tax-landing' ) {
			$config['opt'] = $opt;
		}

		$config = apply_filters( 'largo_load_more_posts_json', $config );
		?>
		<script type="text/javascript">
			new LoadMorePosts(<?php echo json_encode( $config ); ?>);
		</script>
	<?php
	}
}

if ( !function_exists( 'largo_load_more_posts' ) ) {
	/**
	 * Renders markup for a page of posts and sends it back over the wire.
	 *
	 * @global $opt
	 * @global $_POST
	 * @uses largo_load_more_posts_choose_partial
	 * @uses largo_get_partial_by_post_type
	 */
	function largo_load_more_posts() {

		global $opt;

		$paged = (isset($_POST['paged'])) ? $_POST['paged'] : 1;
		$context = (isset($_POST['query']))? json_decode(stripslashes($_POST['query']), true) : array();

		$args = array_merge(array(
			'paged' => (int) $paged,
			'post_status' => 'publish',
			'posts_per_page' => intval( get_option( 'posts_per_page' ) ),
			'ignore_sticky_posts' => true,
		), $context);

		// Making sure that this query isn't for the homepage
		if (isset($_POST['is_home']))
			$is_home = ($_POST['is_home'] == 'false')? false : true;
		else
			$is_home = true;

		// num_posts_home is only relevant on the homepage
		if ( of_get_option( 'num_posts_home' ) && $is_home )
			$args['posts_per_page'] = of_get_option( 'num_posts_home' );

		if ( $is_home ) {
			$args['paged'] = ( $args['paged'] - 1 );
			if ( of_get_option('cats_home') )
				$args['cat'] = of_get_option( 'cats_home' );
		}

		$args = apply_filters( 'largo_lmp_args', $args );
		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			// Choose the correct partial to load here
			$partial = largo_load_more_posts_choose_partial($query);

			// Render all the posts
			while ( $query->have_posts() ) : $query->the_post();
				// Use largo_get_partial_by_post_type here as well as in the search archive,
				// to ensure that LMP posts will be using the partial set by the child theme for that post type.
				$post_type_partial = largo_get_partial_by_post_type($partial, get_post_type(), $partial);
				get_template_part( 'partials/content', $post_type_partial );
			endwhile;
		}
		wp_die();
	}
	add_action( 'wp_ajax_nopriv_load_more_posts', 'largo_load_more_posts' );
	add_action( 'wp_ajax_load_more_posts', 'largo_load_more_posts' );
}

if (!function_exists('largo_load_more_posts_choose_partial')) {
	/**
	 * Function to determine which partial slug should be used by LMP to render posts.
	 *
	 * Includes a "largo_lmp_template_partial" filter to allow for modifying the value $partial.
	 *
	 * @param WP_Query $post_query The WP_Query being used to generate LMP markup
	 * @return string $partial The slug of partial that should be loaded.
	 * @global $opt
	 * @global $_POST
	 * @see largo_load_more_posts
	 * @since 0.5.3
	 */
	function largo_load_more_posts_choose_partial($post_query) {
		global $opt;
		$query_vars = $post_query->query_vars;

		// Default is to use partials/content-home.php
		$partial = 'home';

		// This might be a category, tag, search, date, author, non-landing-page series, or other other archive

		// check if this query is for a category
		if ( isset($query_vars['category_name']) && $query_vars['category_name'] != '' ) {
			$partial = 'archive';
		}

		// check if this query is for an author page
		if ( isset($query_vars['author_name']) && $query_vars['author_name'] != '' ) {
			$partial = 'archive';
		}

		// check if this query is for a tag
		if ( isset($query_vars['tag']) && $query_vars['tag'] != '' ) {
			$partial = 'archive';
		}

		// check if this query is for a search
		if ( isset($query_vars['s']) && $query_vars['s'] != '' ) {
			$partial = 'search';
		}

		// check if this query is for a date, assuming that all date queries have a year.
		if ( isset($query_vars['year']) && $query_vars['year'] != 0 ) {
			$partial = 'archive';
		}

		// Series landing pages
		if ( isset($_POST['is_series_landing']) && $_POST['is_series_landing'] == 'true') {
			$partial = 'series';
			$opt = $_POST['opt'];
		}

		// Non-series-landing series archives
		if ( isset($query_vars['series']) && $query_vars['series'] != '' ) {
			$partial = 'archive';
		}

		// argolinks post type
		$partial = ( get_post_type() == 'argolinks' ) ? 'argolinks' : $partial;

		/**
		 * Filter to modify the Load More Posts template partial.
		 *
		 * When building your own filter, you must set the fourth parameter of add_filter to 2:
		 *
		 *     function your_filter_name($partial, $post_type, $context) {
		 *         // things
		 *         return $partials;
		 *     }
		 *     add_filter( 'largo_lmp_template_partial', 'your_filter_name', 10, 2);
		 *                                                                       ^
		 * Without setting '2', your filter will not be passed the $post_type or $context arguments.
		 * In order to set '2', you must set the third parameter of add_filter, which defaults to 10. it is safe to leave that at 10.
		 *
		 * @since 0.5.3
		 * @param String $partial The string represeting the template partial to use for the current context
		 * @param WP_Query $post_query The WP_Query object used to produce the LMP markup.
		 */
		$partial = apply_filters( 'largo_lmp_template_partial', $partial, $post_query );

		return $partial;
	}
}
