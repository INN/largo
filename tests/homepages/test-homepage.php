<?php

class HomepageTest extends WP_UnitTestCase {

	function set_up() {
		parent::set_up();
		// Make sure options get zeroed out after each test.
		of_reset_options();

		// Set up $largo_homepage_factory
		largo_register_default_homepage_layouts();
	}

	function tear_down() {
		parent::tear_down();
		// cleanup
		unset($GLOBALS['largo_homepage_factory']);
		$GLOBALS['largo_homepage_factory'] = new HomepageLayoutFactory();
	}

	function test_largo_register_default_homepage_layouts() {
		global $largo_homepage_factory;
		$this->assertCount(6, $largo_homepage_factory->layouts, "Series enabled failed");
	}

	function test_largo_get_home_layouts_series_enabled() {
		global $largo_homepage_factory;

		// if series are enabled
		of_set_option('series_enabled', 1);

		foreach ($largo_homepage_factory->layouts as $key => $value)
			$this->assertTrue( is_object( $value ) );
	}

	function test_largo_get_home_layouts_series_disabled() {
		global $largo_homepage_factory;
		// if series are not enabled
		of_set_option('series_enabled', 0);

		foreach ($largo_homepage_factory->layouts as $key => $value)
			$this->assertTrue( is_object( $value ) );
	}

	function test_largo_get_home_thumb() {
		global $largo_homepage_factory;

		foreach ($largo_homepage_factory->layouts as $key => $value) {
			$image = largo_get_home_thumb($key);
			$this->assertTrue(!empty($image));
		}
		$image = largo_get_home_thumb('');
		$this->assertTrue((bool) strpos($image, 'no-thumb.png'));
	}

	function test_largo_render_homepage_layout() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_get_active_homepage_layout() {
		// By default the HomepageBlog layout is active
		$active = largo_get_active_homepage_layout();
		$this->assertEquals('HomepageBlog', $active);

		// If we set a new layout, largo_get_active_homepage_layout should return the correct one
		of_set_option('home_template', 'HomepageSingle');
		$active = largo_get_active_homepage_layout();
		$this->assertEquals('HomepageSingle', $active);
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
