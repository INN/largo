<?php

class HeaderFooterTestFunctions extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();
	}

	function test_largo_header() {
		$this->expectOutputRegex('/[.*]+/'); // This is excessively greedy, it expects any output at all
		largo_header();
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_copyright_message() {
		$this->expectOutputRegex('/[.*]+/'); // This is excessively greedy, it expects any output at all
		largo_copyright_message();
		// this should also test of_get_option( 'copyright_msg' );
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_inn_logo() {
		$this->expectOutputRegex('/[.*]+/'); // This is excessively greedy, it expects any output at all
		$this->expectOutputRegex('/inn_logo_gray.png/'); // This is excessively greedy, it expects any output at all
		inn_logo();
	}

	function test_largo_social_links() {
		// this function only creates output if there are >0 social links
		$fields = array(
			'rss_link',
			'facebook_link',
			'twitter_link',
			'youtube_link',
			'flickr_link',
			'tumblr_link',
			'gplus_link',
			'linkedin_link',
			'github_link'
		);
		foreach ( $fields as $field ) {
			of_set_option( $field, 'http://foo.bar\/'.$field);
			$this->expectOutputRegex('/http:\/\/foo.bar\/'.$field.'/'); // This is excessively greedy, it expects any output at all
			largo_social_links();
			of_reset_options();
		}
	}

	function test_largo_seo() {
		largo_seo();
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
