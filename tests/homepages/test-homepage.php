<?php

class HomepageTest extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();
	}

	function test_largo_register_default_homepage_layouts() {
		//if series are enabled	
		of_set_option('series', true);
		largo_register_default_homepage_layouts(); // creates $largo_homepage_factory
		global $largo_homepage_factory;
		$this->assertEquals(5, count($largo_homepage_factory->layouts));
		// cleanup
		unset($largo_homepage_factory);

		//if series are not enabled
		of_set_option('series', false);
		largo_register_default_homepage_layouts(); // creates $largo_homepage_factory
		global $largo_homepage_factory;
		$this->assertEquals(4, count($largo_homepage_factory->layouts));
		// cleanup
		unset($largo_homepage_factory);
	}

	function test_largo_get_home_layout() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_get_home_thumb() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_render_homepage_layout() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_get_active_homepage_layout() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_home_single_top() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_home_featured_stories() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_home_series_stories_data() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_home_series_stories_term() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_home_series_stories() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_home_get_single_featured_and_series() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
