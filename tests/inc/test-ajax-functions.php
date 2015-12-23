<?php

class AjaxFunctionsTestFunctions extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();

		// Test data
		$this->post_count = 10;
		$this->post_ids = $this->factory->post->create_many($this->post_count);
	}

	function test_largo_load_more_posts_enqueue_script() {
		/**
		 * This test does not, cannot test whether the global LARGO_DEBUG affects enqueueing.
		 */
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

	// @todo: Test this! Can we actually test something that calls wp_die() ?
	function test_largo_load_more_posts() {
		$this->markTestIncomplete(
		' This test calls wp_die(), which we may not be able to tests. Try it and see, sometime later.'
		);
	}

	function test_largo_load_more_posts_choose_partial() {
		$qv = (object) array(
			'query_vars' => array()
		);

		// Note: These options are arrayed in the order that they are tested for in largo_load_more_posts_choose_partial($qv)
		// That is to say, later options *should* override earlier options. This is why $qv is not being unset($qv).

		$ret = largo_load_more_posts_choose_partial($qv);
		$this->assertEquals('home', $ret, 'empty query vars did not result in a determination that the partial type is home');

		// Test it with everything that shouldn't affect the determination that this is home.
		$qv->query_vars['category_name'] = '';
		$qv->query_vars['author_name'] = '';
		$qv->query_vars['tag'] = '';
		$qv->query_vars['s'] = '';
		$qv->query_vars['year'] = '';
		$_POST['is_series_landing'] = false;
		$qv->query_vars['series'] = '';
		// @todo find way to test get_post_type() returning argolinks.
		$ret = largo_load_more_posts_choose_partial($qv);
		$this->assertEquals('home', $ret, 'empty query vars did not result in a determination that the partial type is home');

		// category
		$qv->query_vars['category_name'] = 'foo';
		$ret = largo_load_more_posts_choose_partial($qv);
		$this->assertEquals('archive', $ret, 'Testing category');
		$this->assertFalse(('home' == $ret), 'set query query vars did result in a determination that the partial type is home');

		// Author archive page
		$qv->query_vars['author_name'] = 'admin';
		$ret = largo_load_more_posts_choose_partial($qv);
		$this->assertEquals('archive', $ret, 'Testing author archive');
		$this->assertFalse(('home' == $ret), 'set query query vars did result in a determination that the partial type is home');

		// tag
		$qv->query_vars['tag'] = 'tag';
		$ret = largo_load_more_posts_choose_partial($qv);
		$this->assertEquals('archive', $ret, 'Testing tag');
		$this->assertFalse(('home' == $ret), 'set query query vars did result in a determination that the partial type is home');

		// Search
		$qv->query_vars['s'] = 'search';
		$ret = largo_load_more_posts_choose_partial($qv);
		$this->assertEquals('search', $ret, 'Testing search');
		$this->assertFalse(('home' == $ret), 'set query query vars did result in a determination that the partial type is home');

		// Date archive
		$qv->query_vars['year'] = '2015';
		$ret = largo_load_more_posts_choose_partial($qv);
		$this->assertEquals('archive', $ret, 'Testing date query with "year" => "2015"');
		$this->assertFalse(('home' == $ret), 'set query query vars did result in a determination that the partial type is home');

		// series landing pages
		$_POST['is_series_landing'] = 'true';
		$_POST['opt'] = 'foo';
		$ret = largo_load_more_posts_choose_partial($qv);
		$this->assertEquals('series', $ret, '');
		global $opt;
		$this->assertEquals($opt, $_POST['opt'], 'global $opt was not set to the value supplied in $_POST["opt"]');
		$this->assertFalse(('home' == $ret), 'set query query vars did result in a determination that the partial type is home');

		// non-series-landing series archives
		$qv->query_vars['series'] = 'series';
		$ret = largo_load_more_posts_choose_partial($qv);
		$this->assertEquals('archive', $ret, '');
		$this->assertFalse(('home' == $ret), 'set query query vars did result in a determination that the partial type is home');

		// @todo find way to test get_post_type() returning argolinks.
		$this->assertFalse(('home' == $ret), 'set query query vars did result in a determination that the partial type is home');

		// Test the filter.
	}

}

class AjaxFunctionsTestAjaxFunctions extends WP_Ajax_UnitTestCase {

	function setUp() {
		parent::setUp();

		// Test data
		$this->post_count = 10;
		$this->post_ids = $this->factory->post->create_many($this->post_count);
		of_reset_options();
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
