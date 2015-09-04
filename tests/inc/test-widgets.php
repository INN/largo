<?php

class WidgetsTestFunctions extends WP_UnitTestCase {

	function test_largo_widgets() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_add_widget_classes() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_widget_counter_reset() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_widget_custom_fields_form() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_register_widget_custom_fields() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_widget_update_extend() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_add_link_to_widget_title() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_is_sidebar_registered_and_active() {
		$sidebars_widgets = get_option('sidebars_widgets', array());

		// This sidebar is registered and active when tests are run
		$registered_and_active = 'sidebar-1';
		$this->assertTrue(largo_is_sidebar_registered_and_active($registered_and_active));

		// A sidebar that is definitely not registered and active
		$not_registered_and_active = 'not-registered-and-active';
		$this->assertFalse(largo_is_sidebar_registered_and_active($not_registered_and_active));
	}

}
