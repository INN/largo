<?php

class EnqueueTestFunctions extends WP_UnitTestCase {


	function test_largo_enqueue_js() {
		// Modernizr
		$this->markTestIncomplete('This test has not been implemented yet.');
		// the jquery plugins
		$this->markTestIncomplete('This test has not been implemented yet.');
		// our main JS file
		$this->markTestIncomplete('This test has not been implemented yet.');
		// only load jquery tabs for the related content box if it's active
		$this->markTestIncomplete('This test has not been implemented yet.');
		// Load the child theme's style.css if we're actually running a child theme of Largo
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_enqueue_admin_scripts() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_header_js() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_footer_js() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function largo_google_analytics() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}

class EnqueueTestFunctions_Gutenberg extends WP_UnitTestCase {
	function set_up() {
		// @see wp_version_check();
		require( ABSPATH . WPINC . '/version.php' );
		if (
			getenv( 'GUTENBERG' ) != 1
			|| -1 === version_compare( '4.999', $wp_version ) // 4.999 > 4.9.8 or any release in the 4.9 branch; 4.999 < 5.0-beta1
		) {
			$this->markTestSkipped( 'Skipping Gutenberg tests in non-gutenberg environment' );
		}

		parent::set_up();
	}

	// helper to activate gutenberg
	function activate_gutenberg() {
	}

	// helper to deactivate gutenberg
	function deactivate_gutenberg() {
	}

	function test_activate_gutenberg() {
		$this->activate_gutenberg();

		// test that Gutenberg exists as we expect it to exist
		$this->assertTrue(
			function_exists( 'register_block_type' ),
			'Attempted to activate Gutenberg, but it looks like the function `register_block_type()` was not defined. Did that function name change? It is what we use to decide whether to enqueue css/gutenberg.css'
		);
		$this->activate_gutenberg();
		$this->deactivate_gutenberg();
	}

	function test_largo_gutenberg_frontend_css_js() {
		$this->activate_gutenberg();
		largo_gutenberg_frontend_css_js();
		// assertTrue that the stylesheet 'largo-stylesheet-gutenberg' is enqueued
		$this->deactivate_gutenberg();
	}

	function test_largo_gutenberg_editor_css_js() {
		$this->activate_gutenberg();
		largo_gutenberg_editor_css_js();
		$this->deactivate_gutenberg();
	}
}
