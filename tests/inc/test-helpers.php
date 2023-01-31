<?php

class HelpersTestFunctions extends WP_UnitTestCase {

	function set_up() {
		parent::set_up();

		// Test data
		$this->author_user_ids = $this->factory->user->create_many(10, array('role' => 'author'));;
		$this->contributor_user_ids = $this->factory->user->create_many(5, array('role' => 'contributor'));
	}
	function test_largo_fb_url_to_username() {
		/**
		 * A short list of valid Facebook IDs:
		 	https://www.facebook.com/Foo-bar.1
			https://www.facebook.com/profile.php?id=012345678901234&fref=ts
			https://www.facebook.com/Foo-bar.2?rf=012345678901234
			https://www.facebook.com/pages/Foo-bar.3/012345678901234
			https://m.facebook.com/Foo-bar.4?_e_pi_=7%2CPAGE_ID10%2C0123456789
			https://m.facebook.com/profile.php?id=012345678901234
			https://m.facebook.com/?_rdr#!/Foo-bar.5?__user=012345678901234
		 */

		/**
		 * With no input, it should return an empty string
		 */
		$result = largo_fb_url_to_username("");
		$this->assertEquals("", $result);
		unset($result);

		/**
		 * With a valid username, it should return that username
		 */
		$result = largo_fb_url_to_username("Foo-bar.0");
		$this->assertEquals("Foo-bar.0", $result);
		unset($result);
		$result = largo_fb_url_to_username("012345678901234");
		$this->assertEquals("012345678901234", $result);
		unset($result);

		/**
		 * With a valid URL, it should return the username
		 */
		$result = largo_fb_url_to_username("https://www.facebook.com/Foo-bar.1");
		$this->assertEquals("Foo-bar.1", $result);
		unset($result);
		$result = largo_fb_url_to_username("https://www.facebook.com/profile.php?id=012345678901234&fref=ts");
		$this->assertEquals("012345678901234", $result);
		unset($result);
		$result = largo_fb_url_to_username("https://www.facebook.com/Foo-bar.2?rf=012345678901234");
		$this->assertEquals("Foo-bar.2", $result);
		unset($result);
		// For making a URL, Foo-bar.3 is a valid username, but so is the ID listed here.
		$result = largo_fb_url_to_username("https://www.facebook.com/pages/Foo-bar.3/012345678901234");
		$this->assertEquals("012345678901234", $result);
		unset($result);
		$result = largo_fb_url_to_username("https://m.facebook.com/Foo-bar.4?_e_pi_=7%2CPAGE_ID10%2C0123456789");
		$this->assertEquals("Foo-bar.4", $result);
		unset($result);
		$result = largo_fb_url_to_username("https://m.facebook.com/profile.php?id=012345678901234");
		$this->assertEquals("012345678901234", $result);
		unset($result);
		$result = largo_fb_url_to_username("https://m.facebook.com/?_rdr#!/Foo-bar.5?__user=012345678901234");
		$this->assertEquals("Foo-bar.5", $result);
		unset($result);
	}

	function test_largo_fb_user_is_followable() {
		/**
		 * The function has been changed to always return false.
		 */
		$result = largo_fb_user_is_followable( 'zuck' );
		$this->assertFalse($result, "largo_fb_user_is_followable doesn't work, so it shouldn't be returning anything other than False. What changed?");
		unset($result);
	}

	function test_clean_user_fb_username() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_validate_fb_username() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_twitter_url_to_username() {
		/**
		 * With no input, it should return an empty string
		 */
		$result = largo_twitter_url_to_username("");
		$this->assertEquals("", $result);
		unset($result);

		/**
		 * With a valid username, it should return the username
		 */
		$result = largo_twitter_url_to_username("foo_1");
		$this->assertEquals("foo_1", $result);
		unset($result);

		/**
		 * With a valid url, it should return a valid username
		 */
		$result = largo_twitter_url_to_username("http://twitter.com/foo_2");
		$this->assertEquals("foo_2", $result);
		unset($result);

		$result = largo_twitter_url_to_username("https://twitter.com/foo_3");
		$this->assertEquals("foo_3", $result);
		unset($result);

		$result = largo_twitter_url_to_username("https://www.twitter.com/foo_4");
		$this->assertEquals("foo_4", $result);
		unset($result);

		/**
		 * With an @ in the username, it should return the username without the @
		 */
		$result = largo_twitter_url_to_username("https://twitter.com/@foo_5");
		$this->assertEquals("foo_5", $result);
		unset($result);

		$result = largo_twitter_url_to_username("@foo_6");
		$this->assertEquals("foo_6", $result);
		unset($result);

		/**
		 * With a username that has invalid characters and URL parameters in it, it should return the invalid characters, but not URL parameters
		 */
		$result = largo_twitter_url_to_username("http://twitter.com/@foo.bar_7-!0?baz=qux&yes=no#hi");
		$this->assertEquals("foo.bar_7-!0", $result);
		unset($result);

		$result = largo_twitter_url_to_username("https://twitter.com/@foo.bar_8-!0?baz=qux&yes=no#hi");
		$this->assertEquals("foo.bar_8-!0", $result);
		unset($result);

		/**
		 * With a non-Twitter URL, it should still return the last section after the /
		 */
		$result = largo_twitter_url_to_username("http://github.com/INN/foo_9");
		$this->assertEquals("foo_9", $result);
		unset($result);

		$result = largo_twitter_url_to_username("https://plus.google.com/foo_10");
		$this->assertEquals("foo_10", $result);
		unset($result);

		/**
		 * With no input, it should return an empty string
		 */
		$result = largo_twitter_url_to_username("");
		$this->assertEquals("", $result);
		unset($result);
	}
	
	function test_clean_user_twitter_username() {
		$this->markTestIncomplete('This test has not been implemented yet.');;
	}

	function test_validate_twitter_username() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
	
	function test_largo_youtube_url_to_ID() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_youtube_iframe_from_url() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_youtube_image_from_url() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_make_slug() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_var_log() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_render_template() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_get_current_url() {
		$preserve = $_SERVER;

		$_SERVER['SERVER_NAME'] = 'testdomain.com';
		$_SERVER['REQUEST_URI'] = '/path/to/something';

		$current_url = largo_get_current_url();
		$this->assertTrue((bool) preg_match('/^http:\/\//', $current_url));

		$_SERVER['HTTPS'] = 'on';
		$current_url = largo_get_current_url();
		$this->assertTrue((bool) preg_match('/^https:\/\//', $current_url));

		$_SERVER = $preserve;
	}
}
