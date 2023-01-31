<?php

// Test enabling/disabling of disclaimer widget.

class DisclaimerWidgetTestFunctions extends WP_UnitTestCase {

	function test_enabled_disclaimer() {

		global $wp_widget_factory;

		/* 1: Test that enabling the option actually registers the widget */

		// First make sure nothing else registered this widget.
		unregister_widget('largo_disclaimer_widget');

		// Set the option
		of_set_option('disclaimer_enabled','1');

		largo_widgets();

		// Make sure it's registered and the correct type.
		$widget_obj = $wp_widget_factory->widgets['largo_disclaimer_widget'];
		$this->assertTrue( is_a($widget_obj, 'WP_Widget') );

		/* 2: Test that disabling the option actually disabled the registration of the widget */
		
		// Unregister it.
		unregister_widget('largo_disclaimer_widget');

		// Set the option
		of_set_option('disclaimer_enabled','0');

		largo_widgets();

		// $wp_widget_factory shouldn't know anything about the disclaimer widget anymore.
		$this->assertFalse( array_key_exists('largo_disclaimer_widget',$wp_widget_factory->widgets) );

		
	}


}
