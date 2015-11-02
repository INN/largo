<?php
/*
 * When changing the operation of these functions, be sure to test it in:
 * - the homepage, all templates
 * - category archive pages
 * - series archive pages
 * - custom series landing pages
 * - search results pages
 */

/*
 * Enqueue script for "load more posts" functionality
 */
if ( !function_exists( 'largo_load_more_posts_enqueue_script' ) ) {
	function largo_load_more_posts_enqueue_script() {
		wp_enqueue_script(
			'load-more-posts',
			get_template_directory_uri() . '/js/load-more-posts.js',
			array('jquery'), null, false
		);
	}
	add_action( 'wp_enqueue_scripts', 'largo_load_more_posts_enqueue_script' );
}

/*
 * Print an HTML script tag for a post navigation element and corresponding query
 *
 * @param $nav_id string the unique id of the navigation element used as the trigger to load more posts
 * @param $the_query object the WP_Query object upon which calls to load more posts will be based
 */
if ( !function_exists( 'largo_load_more_posts_data' ) ) {
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

/*
 * Renders markup for a page of posts and sends it back over the wire.
 */
if ( !function_exists( 'largo_load_more_posts' ) ) {
	function largo_load_more_posts() {

		global $opt;

		$paged = (isset($_POST['paged'])) ? $_POST['paged'] : 1;
		$context = (isset($_POST['query']))? json_decode(stripslashes($_POST['query']), true) : array();

		// Making sure that this isn't home
		if (isset($_POST['is_home']))
			$is_home = ($_POST['is_home'] == 'false')? false : true;
		else if ( isset($_POST['query']['cat']) ||
			isset($_POST['query']['author']) ||
			isset($_POST['query']['term']) || # tags, taxonomies and custom taxonomies
			isset($_POST['query']['s'])) # searches
			$is_home = false;
		else
			$is_home = true;

		$args = array_merge(array(
			'paged' => (int) $paged,
			'post_status' => 'publish',
			'posts_per_page' => intval( get_option( 'posts_per_page' ) ),
			'ignore_sticky_posts' => true,
		), $context);

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
			while ( $query->have_posts() ) : $query->the_post();
				$partial = 'home';
				if( $_POST['is_series_landing'] == 'true' ) {
					$partial = 'series';
					$opt = $_POST['opt'];
				}
				get_template_part(
					'partials/content',
					apply_filters( 'largo_lmp_template_partial', $partial, $query )
				);
			endwhile;
		}
		wp_die();
	}
	add_action( 'wp_ajax_nopriv_load_more_posts', 'largo_load_more_posts' );
	add_action( 'wp_ajax_load_more_posts', 'largo_load_more_posts' );
}
