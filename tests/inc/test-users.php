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
		$this->assertEquals(2, substr_count($output, 'checked'), "Not all inputs that should have been checked were.");

		// There should be one job_title input and it should be populated with the value set by
		// `save_more_profile_info`.
		$this->assertEquals(1, substr_count($output, 'value="' . $job_title));
	}

	function test_save_more_profile_info() {
		$vars = $this->_more_profile_info_setup();

		extract($vars);

		save_more_profile_info($user_id);

		$this->assertEquals($job_title, get_user_meta($user_id, "job_title", true));
	}

	// Utilities
	function _more_profile_info_setup() {
		$user_id = $this->author_user_ids[0];

		$args = array(
			'job_title' => 'Test Job Title',
			'show_email' => 'on'
		);

		extract($args);

		$_POST = array_merge($_POST, array(
			'job_title' => $job_title,
			'show_email' => $show_email
		));

		return array_merge(array('user_id' => $user_id), $args);
	}

}
