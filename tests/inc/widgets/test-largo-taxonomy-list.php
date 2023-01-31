<?php

class TaxonomyListWidgetTestFunctions extends WP_UnitTestCase {

	// Check custom functionality in the update method.
	function test_update() {
		// count of '' should remain ''
		// count of '-1' should become '1'
		// count of '-234' should become '1'
		// count of '0' should become '1'
		// count of a number > 1 should remain that number
		// those are really the only unusual cases in the update method
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_count_option_blank() {
		// series
		// category
		// tag
		// generic other taxonomy
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_taxonomy_returned_is_correct() {
		// series
		// category
		// tag
		// generic other taxonomy
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	// test that thumbnails are not displayed when the thumbnail option is disabled
	function test_thumbnails_are_not_displayed() {
		// series
		// category
		// tag
		// generic other taxonomy
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	// test that headlines are not displayed when the thumbnail option is disabled
	function test_headlines_are_not_displayed() {
		// series
		// category
		// tag
		// generic other taxonomy
		$this->markTestIncomplete('This test has not been implemented yet.');
	}


	// test that thumbnails are displayed when the thumbnail option is enabled
	function test_thumbnails_are_displayed() {
		// series
		// category
		// tag
		// generic other taxonomy
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	// test that headlines are displayed when the thumbnail option is enabled
	function test_headlines_are_displayed() {
		// series
		// category
		// tag
		// generic other taxonomy
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	// test that the private method render_li outputs appropriate syntax
	function test_render_li() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	// Test that, if a number of term IDs are set in $instance['include'], those are output.
	function test_include_IDs() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

}
