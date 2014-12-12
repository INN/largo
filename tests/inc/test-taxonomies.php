<?php
class TaxonomiesTestFunctions extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();

		// Test data
		$this->author_user_ids = $this->factory->user->create_many(10, array('role' => 'author'));;
		$this->contributor_user_ids = $this->factory->user->create_many(5, array('role' => 'contributor'));
	}
	
	function test_largo_custom_taxonomies(){
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
	function test_largo_post_in_series() {
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
	function test_largo_categoy_archive_posts() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
	function test_hide_post_type_taxonomy_menu() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
	function test_hide_post_type_taxonomy_metabox() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
	function test_hide_post_type_taxonomy_table() {
		mock_in_admin('site');
		$columns = array (
			'taxonomy-post-type' => 'Post Types',
			);
		$columns = hide_post_type_taxonomy_table($columns);
		$this->assertEquals(
			array(),
			$columns
			);
		unmock_in_admin();
	}
}

