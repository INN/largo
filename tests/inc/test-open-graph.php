<?php

class OpenGraphTestFunctions extends WP_UnitTestCase {

	function set_up() {
		parent::set_up();

		$this->post_excerpt = <<<EOT
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque cursus purus id pharetra dapibus.
EOT;

		$this->post_id = $this->factory->post->create(array(
			'post_excerpt' => $this->post_excerpt,
		));
	}

	/**
	 * Things to test for largo_opengraph():
	 * - opengraph tags output for page are appropriate to context
	 * - opengraph tags contain appropriate metadata
	 */
	function test_largo_opengraph() {
		$this->markTestIncomplete("This test has not yet been implemented.");
	}
	function test_largo_opengraph__twitter() {
		$test = 'https://twitter.com/inn';
		of_set_option( 'twitter_link', $test );
		ob_start();
		largo_opengraph();
		$capture = ob_get_clean();
		$this->assertStringContainsString(
			esc_attr( largo_twitter_url_to_username( $test ) ),
			$capture,
			"The twitter account 'inn' is not found in the open graph output"
		);
	}

	function test_largo_wp_title_parts_filter() {
		// needs test cases for:
		// - home page
		// - front page
		// - a paginated post
		// - that the returned $parts contains no empty $part
		$this->markTestIncomplete("This test has not yet been implemented.");
	}

	function test_document_title_separator() {
		$test = '-';
		$expected = '|';
		$result = apply_filters( 'document_title_separator', $test );
		$this->assertEquals( $expected, $result, "Largo's filter on document_title_separator appears to not be engaged.");
	}
}
