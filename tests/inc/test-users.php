<?php

// Test functions in inc/users.php
class UsersTestFunctions extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();

		// Test data
		$this->author_user_ids = $this->factory->user->create_many(10, array('role' => 'author'));
		$this->contributor_user_ids = $this->factory->user->create_many(5, array('role' => 'contributor'));
	}

	function test_largo_contactmethods() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_filter_guest_author_fields() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_admin_users_caps() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_edit_permission_check() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_clean_user_twitter_username() {
		$this->markTestIncomplete('This test has not been implemented yet.');;
	}

	function test_validate_twitter_username() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_clean_user_fb_username() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_validate_fb_username() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_get_user_list() {
		/**
		 * With no arguments, `largo_get_user_list` should get a list of all authors for the current blog;
		 */
		$result = largo_get_user_list();
		$ids = array_map(function($user) { return $user->ID; }, $result);

		$this->assertEquals(count($ids), count($this->author_user_ids));

		// Make sure all of the ids in the list returned by `largo_get_user_list` are the same as
		// those in $this->author_user_ids
		$not_present = array();
		foreach ($ids as $id) {
			if (!in_array($id, $this->author_user_ids))
				$not_present[] = $id;
		}
		$this->assertEmpty($not_present);

		unset($result);
		unset($ids);

		/**
		 * When we specify a role, we should get back only users with that role.
		 */
		$result = largo_get_user_list(array('roles' => array('contributor')));
		$ids = array_map(function($user) { return $user->ID; }, $result);

		$this->assertEquals(count($ids), count($this->contributor_user_ids));

		// Make sure all of the ids in the list returned by `largo_get_user_list` are the same as
		// those in $this->contribut_user_ids
		$not_present = array();
		foreach ($ids as $id) {
			if (!in_array($id, $this->contributor_user_ids))
				$not_present[] = $id;
		}
		$this->assertEmpty($not_present);

		unset($result);
		unset($ids);

		/**
		 * When we specify multiple roles, we should get back a list of users with any of the roles specified.
		 */
		$result = largo_get_user_list(array('roles' => array('contributor', 'author')));
		$ids = array_map(function($user) { return $user->ID; }, $result);

		$this->assertEquals(count($ids), count($this->contributor_user_ids) + count($this->author_user_ids));

		// Make sure all of the ids in the list returned by `largo_get_user_list` are the same as
		// those in $this->contributor_user_ids and $this->author_user_ids
		$not_present = array();
		foreach ($ids as $id) {
			if (!in_array($id, $this->contributor_user_ids) && !in_array($id, $this->author_user_ids))
				$not_present[] = $id;
		}
		$this->assertEmpty($not_present);

		unset($result);
		unset($ids);
	}

	/**
	 * Test the function largo_render_user_list
	 *
	 * Be aware: This may fail if partials/author-bio-description.php changes.
	 */
	function test_largo_render_user_list() {
		/*
		 * Test that a user without a description gets no output
		 */
		$user1 = $this->factory->user->create();
		$arg1[] = get_user_by('id', $user1);

		ob_start();
		largo_render_user_list($arg1);
		$out1 = ob_get_clean();
		$this->assertEquals('<div class="user-list"></div>', $out1);

		/*
		 * Test that a user with a description gets output
		 */
		$user2 = $this->factory->user->create();
		update_user_meta($user2, 'description', 'foobar');
		$arg2[] = get_user_by('id', $user2);

		ob_start();
		largo_render_user_list($arg2);
		$out2 = ob_get_clean();
		$this->assertRegExp('/author-box/', $out2);
		$this->assertRegExp('/foobar/', $out2);

		/*
		 * Test that a user with an avatar has output including the avatar
		 */
		$user3 = $this->factory->user->create();
		$attachment = $this->factory->post->create(array('post_type' => 'attachment'));
		update_user_meta($user3, 'description', 'foobar');
		update_user_meta($user3, 'largo_avatar', $attachment);
		$arg3[] = get_user_by('id', $user3);

		ob_start();
		largo_render_user_list($arg3);
		$out3 = ob_get_clean();
		$this->assertRegExp('/author-box/', $out3);
		$this->assertRegExp('/foobar/', $out3);
		$this->assertRegExp('/src/', $out3);

		/*
		 * Test it with all the users
		 */
		$arg4[] = get_user_by('id', $user1);
		$arg4[] = get_user_by('id', $user2);
		$arg4[] = get_user_by('id', $user3);

		ob_start();
		largo_render_user_list($arg4);
		$out4 = ob_get_clean();

		$this->assertEquals( 2, preg_match_all('/author-box/', $out4, $matches), "There were not 2 authors with descriptions rendered.");
		$this->assertEquals( 1, preg_match_all('/src=/', $out4, $matches), "There was not 1 author with an avatar image rendered.");

		/*
		 * Test it with a user that is set to hide.
		 */
		$user4 = $this->factory->user->create();
		$att4 = $this->factory->post->create(array('post_type' => 'attachment'));
		update_user_meta($user4, 'description', 'foobar');
		update_user_meta($user4, 'hide', 'on');
		update_user_meta($user4, 'largo_avatar', $att4);
		$arg4[] = get_user_by('id', $user4);

		ob_start();
		largo_render_user_list($arg4);
		$out5 = ob_get_clean();

		// Using same numbers here because the hidden user should not change these numbers.
		$this->assertEquals( 2, preg_match_all('/author-box/', $out5, $matches), "There were not 2 authors with descriptions rendered.");
		$this->assertEquals( 1, preg_match_all('/src=/', $out5, $matches), "There was not 1 author with an avatar image rendered.");

	}

	function test_largo_render_staff_list_shortcode() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_more_profile_info() {
		$vars = $this->_more_profile_info_setup();

		extract($vars);

		save_more_profile_info($user_id);

		ob_start();
		more_profile_info(get_user_by('id', $user_id));
		$output = ob_get_contents();
		ob_end_clean();

		// Four inputs should be present and four should be checked or "on" after running
		// `save_more_profile_info`, because $this->_more_profile_info sets show_email to true.
		$this->assertEquals(4, substr_count($output, 'checked'), "Not all inputs that should have been checked were.");

		// There should be one job_title input and it should be populated with the value set by
		// `save_more_profile_info`.
		$this->assertEquals(1, substr_count($output, 'value="' . $job_title));
	}

	function test_save_more_profile_info() {
		$vars = $this->_more_profile_info_setup();

		extract($vars);

		save_more_profile_info($user_id);

		$this->assertEquals($hide, get_user_meta($user_id, "hide", true));
		$this->assertEquals($emeritus, get_user_meta($user_id, "emeritus", true));
		$this->assertEquals($honorary, get_user_meta($user_id, "honorary", true));
		$this->assertEquals($job_title, get_user_meta($user_id, "job_title", true));
	}

	// Utilities
	function _more_profile_info_setup() {
		$user_id = $this->author_user_ids[0];

		$args = array(
			'job_title' => 'Test Job Title',
			'hide' => 'on',
			'emeritus' => 'on',
			'honorary' => 'on',
			'show_email' => 'on'
		);

		extract($args);

		$_POST = array_merge($_POST, array(
			'hide' => $hide,
			'emeritus' => $emeritus,
			'honorary' => $honorary,
			'job_title' => $job_title,
			'show_email' => $show_email
		));

		return array_merge(array('user_id' => $user_id), $args);
	}

}
