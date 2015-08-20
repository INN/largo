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
		global $wp_scripts, $wp_query;
		$args = array(
			'post_type' => 'post',
		);
		$wp_query = new WP_Query($args);
		if ($wp_query->have_posts()) {
			while ($wp_query->have_posts()) {
				$wp_query->the_post();
				largo_mark_post_shown(get_the_ID());
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
