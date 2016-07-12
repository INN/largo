<?php

class PaginationTestFunctions extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();

		$this->post_excerpt = <<<EOT
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque cursus purus id pharetra dapibus.
EOT;

		$this->post_id = $this->factory->post->create(array(
			'post_excerpt' => $this->post_excerpt,
		));
	}

	function test_largo_entry_content() {
		$this->markTestIncomplete("This test has not yet been implemented.");
	}


	function test_largo_content_nav() {
		$this->markTestIncomplete("This test has not yet been implemented.");
	}

	function test_largo_custom_wp_link_pages() {
		$this->markTestIncomplete("This test has not yet been implemented.");
	}
}
