<?php
class TaxonomiesTestFunctions extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();

		// Make sure options are zeroed out after each test
		of_reset_options();

		// Test data
		$this->author_user_ids = $this->factory->user->create_many(10, array('role' => 'author'));;
		$this->contributor_user_ids = $this->factory->user->create_many(5, array('role' => 'contributor'));
	}

	function test_largo_is_series_enabled() {
		// Series option has not been touched yet (a new install)
		$result = largo_is_series_enabled();
		$this->assertFalse($result);

		// Series option is to not enable series
		of_set_option('series_enabled', false);
		$result = largo_is_series_enabled();
		$this->assertFalse($result);

		// Series option is to enable series
		of_set_option('series_enabled', 1);
		$result = largo_is_series_enabled();
		$this->assertTrue($result);
	}

	function test_largo_is_series_landing_enabled() {
		// Series option has not been touched yet (a new install)
		$result = largo_is_series_landing_enabled();
		$this->assertFalse($result);

		// Series option is to not enable series
		of_set_option('custom_landing_enabled', false);
		$result = largo_is_series_landing_enabled();
		$this->assertFalse($result);

		// Series option is to enable series
		of_set_option('custom_landing_enabled', 1);
		$result = largo_is_series_landing_enabled();
		$this->assertTrue($result);
	}

	function test_largo_custom_taxonomies(){
		// unregister the taxonomies using register_taxonomy('slug', array());
		// largo_custom_taxonomies();
		// Test that taxonomy_exists('prominence')
		// Test that taxonomy_exists('post-type')
		// Test that post-type's 'public' property is equal to of_get_option('post_types_enabled')
		// Test that prominence terms are registered
		// Test that taxonomy_exists('series')

		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_post_in_series() {
		// If series are disabled:
		of_set_option('series_enabled', false);
		$result = largo_post_in_series();
		$this->assertFalse($result);

		// If series are enabled:
		of_set_option('series_enabled', 1);
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_custom_taxonomy_terms() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_term_to_label() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_get_series_posts() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_series_landing_link() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_get_series_landing_page_by_series() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_category_archive_posts() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_featured_thumbnail_in_post_array() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_first_headline_in_post_array() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_get_featured_posts_in_category() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_unregister_series_taxonomy() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_unregister_post_types_taxonomy() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}

