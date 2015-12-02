<?php

class AjaxFunctionsTestFunctions extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();

		// Test data
		$this->post_count = 10;
		$this->post_ids = $this->factory->post->create_many($this->post_count);
	}

	function test_largo_load_more_posts_enqueue_script() {
		global $wp_scripts;
		largo_load_more_posts_enqueue_script();
		$this->assertTrue(!empty($wp_scripts->registered['load-more-posts']));
	}

	function test_largo_load_more_posts_data() {
		// create $shown_ids
		global $wp_scripts, $wp_query, $shown_ids;
		$args = array(
			'post_type' => 'post',
		);
		$wp_query = new WP_Query($args);
		if ($wp_query->have_posts()) {
			while ($wp_query->have_posts()) {
				$wp_query->the_post();
				$shown_ids[] = get_the_ID();
			}
		}

		$this->expectOutputRegex('/script/');
		largo_load_more_posts_data('test_nav', $wp_query);
	}

}

class AjaxFunctionsTestAjaxFunctions extends WP_Ajax_UnitTestCase {

	function setUp() {
		parent::setUp();

		// Test data
		$this->post_count = 10;
		$this->post_ids = $this->factory->post->create_many($this->post_count);
		of_reset_options();

		/**
		 * A sample wordpress query that can be extended for use in tests like for largo_load_more_posts_choose_partial
		 */
		$this->post_query = new WP_Query(array(
			'query_vars' => array (
				'paged' => 2,
				'post_status' => 'publish',
				'posts_per_page' => 10,
				'ignore_sticky_posts' => true,
				'post__not_in' => array (),
				'error' => '',
				'm' => '',
				'p' => 0,
				'post_parent' => '',
				'subpost' => '',
				'subpost_id' => '',
				'attachment' => '',
				'attachment_id' => 0,
				'name' => '',
				'static' => '',
				'pagename' => '',
				'page_id' => 0,
				'second' => '',
				'minute' => '',
				'hour' => '',
				'day' => 0,
				'monthnum' => 0,
				'year' => 0,
				'w' => 0,
				'category_name' => '',
				'tag' => '',
				'cat' => '',
				'tag_id' => '',
				'author' => '',
				'author_name' => '',
				'feed' => '',
				'tb' => '',
				'comments_popup' => '',
				'meta_key' => '',
				'meta_value' => '',
				'preview' => '',
				'sentence' => '',
				'fields' => '',
				'menu_order' => '',
				'category__in' => array (),
				'category__not_in' => array (),
				'category__and' => array (),
				'post__in' => array (),
				'tag__in' => array (),
				'tag__not_in' => array (),
				'tag__and' => array (),
				'tag_slug__in' => array (),
				'tag_slug__and' => array (),
				'post_parent__in' => array (),
				'post_parent__not_in' => array (),
				'author__in' => array (),
				'author__not_in' => array (),
				'suppress_filters' => false,
				'cache_results' => true,
				'update_post_term_cache' => true,
				'update_post_meta_cache' => true,
				'post_type' => 'any',
				'nopaging' => false,
				'comments_per_page' => '50',
				'no_found_rows' => false,
				'search_terms_count' => 1,
				'search_terms' => array (),
				'search_orderby_title' => array (),
				'order' => 'DESC',
			),
			'tax_query' => array( // Should be a WP_Tax_Query
				'queries' => array (),
				'relation' => 'AND',
				'table_aliases' => array (),
				'queried_terms' => array (),
				'primary_table' => 'wp_46_posts',
				'primary_id_column' => 'ID',
			),
			'meta_query' => array( // should be a WP_Meta_Query
				'queries' => array (),
				'relation' => NULL,
				'meta_table' => NULL,
				'meta_id_column' => NULL,
				'primary_table' => NULL,
				'primary_id_column' => NULL,
				'table_aliases' => array (),
				'clauses' => array (),
			),
			'date_query' => false,
			'post_count' => 10,
			'current_post' => -1,
			'in_the_loop' => false,
			'comment_count' => 0,
			'current_comment' => -1,
			'found_posts' => '63',
			'max_num_pages' => 7,
			'max_num_comment_pages' => 0,
			'is_single' => false,
			'is_preview' => false,
			'is_page' => false,
			'is_archive' => false,
			'is_date' => false,
			'is_year' => false,
			'is_month' => false,
			'is_day' => false,
			'is_time' => false,
			'is_author' => false,
			'is_category' => false,
			'is_tag' => false,
			'is_tax' => false,
			'is_search' => true,
			'is_feed' => false,
			'is_comment_feed' => false,
			'is_trackback' => false,
			'is_home' => true,
			'is_404' => false,
			'is_comments_popup' => false,
			'is_paged' => true,
			'is_admin' => false,
			'is_attachment' => false,
			'is_singular' => false,
			'is_robots' => false,
			'is_posts_page' => false,
			'is_post_type_archive' => false,
			'query_vars_hash' => 'b788e6e7c1f6a66fc9c1445dd3168165',
			'query_vars_changed' => false,
			'thumbnails_cached' => false,
			'stopwords' => array (), // Normally a long list of words that should be ignored in searches.
			'compat_fields' => array (
				0 => 'query_vars_hash',
				1 => 'query_vars_changed',
			),
			'compat_methods' => array (
				0 => 'init_query_flags',
				1 => 'parse_tax_query',
			),
			'query' => array (
				'paged' => 2,
				'post_status' => 'publish',
				'posts_per_page' => 10,
				'ignore_sticky_posts' => true,
				'post__not_in' => NULL,
				's' => 'chicken',
			),
			'request' => '', // Normally a long SQL query.
			'posts' => array ()
		));
		// end $this->post_query
	}

	function test_largo_load_more_posts() {
		$_POST['paged'] = 0;
		$_POST['query'] = json_encode(array());
		$_POST['is_series_landing'] = true;
		$_POST['opt'] = array();

		try {
			$this->_handleAjax("load_more_posts");
		} catch (WPAjaxDieContinueException $e) {
			foreach ($this->post_ids as $number) {
				$pos = strpos($this->_last_response, 'post-' . $number);
				$this->assertTrue((bool) $pos);
			}
		}
	}

	/*
	 * Apologies in advance for the mess here. This will be using real queries from vagrant, and there will consequently be a mess.
	 */
	function test_largo_load_more_posts_choose_partial_home() {
		$_POST['is_series_landing'] = false;
		$pq = $this->post_query;
		$ret = largo_load_more_posts_choose_partial($pq);
		$this->assertEquals('home', $ret, "Didn't return home on an empty query.");
	}
	function test_largo_load_more_posts_choose_partial_category() {
		$_POST['is_series_landing'] = false;
		$pq = $this->post_query;
		$pq->query_vars['category_name'] = 'foo';

		$ret = largo_load_more_posts_choose_partial($pq);
		$this->assertEquals('archive', $ret, "Didn't return 'archive' on a category query.");
	}
	function test_largo_load_more_posts_choose_partial_author() {
		$_POST['is_series_landing'] = false;
		$pq = $this->post_query;
		$pq->query_vars['author_name'] = 'foo';

		$ret = largo_load_more_posts_choose_partial($pq);
		$this->assertEquals('archive', $ret, "Didn't return home on an empty query.");
	}
	function test_largo_load_more_posts_choose_partial_tag() {
		$_POST['is_series_landing'] = false;
		$pq = $this->post_query;
		$pq->query_vars['tag'] = 'foo';

		$ret = largo_load_more_posts_choose_partial($pq);
		$this->assertEquals('archive', $ret, "Didn't return home on an empty query.");
	}
	function test_largo_load_more_posts_choose_partial_search() {
		$_POST['is_series_landing'] = false;
		$pq = $this->post_query;
		$pq->query_vars['s'] = 'foo';

		$ret = largo_load_more_posts_choose_partial($pq);
		$this->assertEquals('archive', $ret, "Didn't return home on an empty query.");
	}
	function test_largo_load_more_posts_choose_partial_date() {
		$_POST['is_series_landing'] = false;
		$pq = $this->post_query;
		$pq->query_vars['year'] = '1992';

		$ret = largo_load_more_posts_choose_partial($pq);
		$this->assertEquals('archive', $ret, "Didn't return home on an empty query.");
	}
	function test_largo_load_more_posts_choose_partial_series_landing_page() {
		$_POST['is_series_landing'] = true;
		$_POST['opt'] = 'Boo!';
		global $opt;
		$backup = $opt;
		$pq = $this->post_query;

		$ret = largo_load_more_posts_choose_partial($pq);
		$this->assertEquals('series', $ret, "Didn't return 'series' on a series landing page");
		$this->assertEquals('Boo!', $opt, 'Did not set the global $opt to $_POST[\'opt\'] on a series landing page.');
		$opt = $backup;
	}
	function test_largo_load_more_posts_choose_partial_series() {
		$_POST['is_series_landing'] = false;
		$pq = $this->post_query;
		$pq->query_vars['series'] = 'foo';

		$ret = largo_load_more_posts_choose_partial($pq);
		$this->assertEquals('archive', $ret, "Didn't return 'archive' on a series without a landing page.");
	}
	// Will be removed with https://github.com/INN/Largo/issues/926
	function test_largo_load_more_posts_choose_partial_argolinks() {
		$this->markTestIncomplete();
		$_POST['is_series_landing'] = false;
		$pq = $this->post_query;

		$ret = largo_load_more_posts_choose_partial($pq);
		$this->assertEquals('argolinks', $ret, "Didn't return 'argolinks' when the returned post type was argolinks.");
	}

	/*
	 * Make sure `largo_load_more_posts` works when `cats_home` option is set.
	 *
	 * Regression test for issue: http://github.com/inn/largo/issues/499
	 */
	function test_largo_load_more_posts_cats_home_option() {
		$this->markTestSkipped('Unable to read the ajax return, even when it is filled with dumb <h1>foo</h1> tags that do not depend upon categories or posts or queries.');

		global $wp_action;
		$preserve = $wp_action;
		$wp_action = array();

		$category = $this->factory->category->create();
		of_set_option('cats_home', (string) $category);
		$posts = $this->factory->post->create_many(10, array(
			'post_category' => $category
		));

		$_POST['paged'] = 0;
		$_POST['query'] = json_encode(array());

		try {
			$this->_handleAjax("load_more_posts");
		} catch (WPAjaxDieStopException $e) {
			foreach ($this->post_ids as $number) {
				$pos = strpos($this->_last_response, 'post-' . $number);
				$this->assertTrue((bool) $pos);
			}
		} catch (WPAjaxDieContinueException $e) {
			foreach ($this->post_ids as $number) {
				$pos = strpos($this->_last_response, 'post-' . $number);
				$this->assertTrue((bool) $pos);
			}
		}

		$wp_action = $preserve;
	}

	function test_largo_load_more_posts_empty_query() {
		$_POST['paged'] = 0;
		$_POST['is_series_landing'] = true;
		$_POST['opt'] = array();

		try {
			$this->_handleAjax("load_more_posts");
		} catch (WPAjaxDieContinueException $e) {
			foreach ($this->post_ids as $number) {
				$pos = strpos($this->_last_response, 'post-' . $number);
				$this->assertTrue((bool) $pos);
			}
		}
	}

}
