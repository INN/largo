<?php

class PostSocialTestFunctions extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();

		$this->post_excerpt = <<<EOT
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque cursus purus id pharetra dapibus.
EOT;

		$this->post_id = $this->factory->post->create(array(
			'post_excerpt' => $this->post_excerpt,
		));
	}

	function test_largo_post_social_links() {
		// Create a post, go to it.
		$id = $this->factory->post->create();
		$this->go_to('/?p=' . $id);

		// Test the output of this when no options are set
		of_set_option('article_utilities', array(
			'facebook' => false,
			'twitter' => false,
			'print' => false,
			'email' => false
		));

		ob_start();
		largo_post_social_links();
		$ret = ob_get_clean();
		$this->assertRegExp('/post-social/', $ret, "The .post-social class was not in the output");
		unset($ret);

		// Test that this outputs the expected data for each of the button types

		// Twitter
		of_set_option('article_utilities', array('twitter' => '1', 'facebook' => false, 'print' => false, 'email' => false));
		of_set_option('twitter_link', 'foo');
		ob_start();
		largo_post_social_links();
		$ret = ob_get_clean();
		$this->assertRegExp('/class="twitter"/' , $ret, "The 'twitter' class did not appear in the output");
		// @TODO: insert a test for the get_the_author_meta test here
		$this->assertRegExp('/' . __('Tweet', 'largo') . '/', $ret, "The translation of 'Tweet' was not in the Twitter output");
		$this->assertRegExp('/' . preg_quote(rawurlencode(get_permalink()), '/') . '/', $ret, "The permalink was not in the Twitter output");
		$this->assertRegExp('/' . preg_quote(rawurlencode(get_the_title()), '/') . '/', $ret, "The title was not in the Twitter output");
		unset($ret);
		of_reset_options();

		// Facebook
		of_set_option('article_utilities', array('facebook' => '1', 'twitter' => false, 'print' => false, 'email' => false));
		ob_start();
		largo_post_social_links();
		$ret = ob_get_clean();
		$this->assertRegExp('/' . preg_quote(esc_attr( of_get_option( 'fb_verb' ) ), '/' ) . '/i', $ret, "The Facebook Verb was not in the Facebook output");
		$this->assertRegExp('/' . preg_quote(rawurlencode(get_permalink()), '/') . '/', $ret, "The permalink was not in the Facebook output");
		unset($ret);
		of_reset_options();

		// Print
		of_set_option('article_utilities', array('print' => '1', 'twitter' => '1', 'facebook' => false, 'email' => false));
		ob_start();
		largo_post_social_links();
		$ret = ob_get_clean();
		$this->assertRegExp('/print/', $ret, "The Print output did not include a print class");
		unset($ret);
		of_reset_options();

		// Email
		of_set_option('article_utilities', array('email' => '1', 'twitter' => false, 'facebook' => false, 'print' => false));
		ob_start();
		largo_post_social_links();
		$ret = ob_get_clean();
		$this->assertRegExp('/email/', $ret, "The Email output did not include an email class");
		unset($ret);
		of_reset_options();

	}

	function test_largo_floating_social_buttons() {
		$id = $this->factory->post->create();
		of_set_option('single_floating_social_icons', 1);
		of_set_option('single_template', 'normal');
		$this->go_to('/?p=' . $id);
		$this->expectOutputRegex('/post-social/', "The .post-social class was not in the output.");
		$this->expectOutputRegex('/id="tmpl-floating-social-buttons"/', "The #floating-social-buttons id was not in the output.");
		largo_floating_social_buttons();
	}

	function test_largo_floating_social_button_width_json() {
		$id = $this->factory->post->create();
		of_set_option('single_floating_social_icons', 1);
		of_set_option('single_template', 'normal');
		$this->go_to('/?p=' . $id);
		$this->expectOutputRegex('/id="floating-social-buttons-width-json"/', "The #floating-social-buttons-width-json id was not in the output.");
		largo_floating_social_button_width_json();
	}

	function test_largo_floating_social_button_js() {
		$id = $this->factory->post->create();
		of_set_option('single_floating_social_icons', 1);
		of_set_option('single_template', 'normal');
		$this->go_to('/?p=' . $id);
		$this->expectOutputRegex('/\/js\/floating-social-buttons/', "The Floating social buttons js was not output to the page.");
		largo_floating_social_button_js();
	}

	function test_not_largo_floating_social_buttons() {
		of_set_option('single_floating_social_icons', false);
		of_set_option('single_template', 'normal');
		$this->go_to('/');
		$this->expectOutputString('', "The .post-social class was not in the output.");
		largo_floating_social_buttons();
	}

	function test_not_largo_floating_social_button_width_json() {
		of_set_option('single_floating_social_icons', false);
		of_set_option('single_template', 'normal');
		$this->go_to('/');
		$this->expectOutputString('', "The #floating-social-buttons-width-json id was in the output, when nothing should have been output.");
		largo_floating_social_button_width_json();
	}

	function test_not_largo_floating_social_button_js() {
		of_set_option('single_floating_social_icons', false);
		of_set_option('single_template', 'normal');
		$this->go_to('/');
		$this->expectOutputString('', "The Floating social buttons js was output to the page when it should not have been.");
		largo_floating_social_button_js();
	}

}
