<?php

// Test functions in inc/avatars/admin.php and inc/avatars/functions.php
class AvatarsTestFunctions extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();

		include_once dirname(dirname(__DIR__)) . '/inc/avatars/admin.php';

		$this->test_file_id = 'test-file-id';

		// Test data
		$this->avatar_id = $this->factory->post->create(array('post_type' => 'attachment'));
		$this->user_id = $this->factory->user->create();
	}

	function test_largo_get_user_avatar_id() {
		// Set the avatar for $user_id to $avatar_id
		largo_associate_avatar_with_user($this->user_id, $this->avatar_id);

		$ret = largo_get_user_avatar_id($this->user_id);

		$this->assertEquals($ret, $this->avatar_id);
	}

	function test_has_files_to_upload() {
		// The $_FILES global should not have a key with $this->test_file_id before we set one.
		$this->assertEquals(has_files_to_upload($this->test_file_id), false);

		// After the key exists and has a value, `has_files_to_upload` should return true.
		$_FILES[$this->test_file_id] = 'Nothing to see here.';
		$this->assertEquals(has_files_to_upload($this->test_file_id), true);
	}

	function test_largo_associate_avatar_with_user() {
		// Set the avatar for $user_id to $avatar_id
		largo_associate_avatar_with_user($this->user_id, $this->avatar_id);

		// Retrieve the $avatar_id directly with `get_user_meta`
		$retrieved = get_user_meta($this->user_id, LARGO_AVATAR_META_NAME, true);

		// $retrieved should not be empty and show equal $avatar_id
		$this->assertTrue(!empty($retrieved));
		$this->assertEquals($retrieved, $this->avatar_id);
	}

	function test_largo_remove_user_avatar() {
		// Set the avatar for $user_id to $avatar_id
		largo_associate_avatar_with_user($this->user_id, $this->avatar_id);

		// Remove the avatar
		largo_remove_user_avatar($this->user_id);

		// Retrieve the $avatar_id directly with `get_user_meta`
		$retrieved = get_user_meta($this->user_id, LARGO_AVATAR_META_NAME, true);

		// $retrieved should be empty at this point
		$this->assertEmpty($retrieved);
	}

	function test_largo_load_avatar_js() {
		global $wp_scripts;
		largo_load_avatar_js();
		$this->assertTrue(!empty($wp_scripts->registered['largo_avatar_js']));
	}

	function test_largo_add_edit_form_multipart_encoding() {
		$this->expectOutputString(' enctype="multipart/form-data"');
		largo_add_edit_form_multipart_encoding();
	}

	// TODO: Figure out how to test these last few functions
	function test_largo_add_avatar_field() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_save_avatar_field() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_print_avatar_admin_css() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_get_avatar_filter() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_get_avatar_src() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

}

// Ajax function(s) from inc/avatars/admin.php
class AvatarsTestAdminAjaxFunctions extends WP_Ajax_UnitTestCase {

	function setUp() {
		parent::setUp();

		// Test data
		$this->avatar_id = $this->factory->post->create(array('post_type' => 'attachment'));
		$this->user_id = $this->factory->user->create();
	}

	function test_largo_remove_avatar_current_user() {
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;

		// Set the avatar for $user_id (current user) to $avatar_id
		largo_associate_avatar_with_user($user_id, $this->avatar_id);

		try {
			$this->_handleAjax("largo_remove_avatar");
		} catch (WPAjaxDieContinueException $e) {
			// Retrieve the $avatar_id.
			$retrieved = largo_get_user_avatar_id($user_id);

			// $retrieved should be empty at this point
			$this->assertEmpty($retrieved);
		}

	}

	function test_largo_remove_avatar() {
		// Should also work if we're editing another user's profile
		largo_associate_avatar_with_user($this->user_id, $this->avatar_id);

		// We can simulate editing another user's profile by setting the $_POST['user_id'] key.
		$_POST['user_id'] = $this->user_id;

		try {
			$this->_handleAjax("largo_remove_avatar");
		} catch (WPAjaxDieContinueException $e) {
			// Retrieve the $avatar_id.
			$retrieved = largo_get_user_avatar_id($this->user_id);

			// $retrieved should be empty at this point
			$this->assertEmpty($retrieved);
		}
	}

}
