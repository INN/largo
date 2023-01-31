<?php

class PostSeriesLinksWidgetTestFunctions extends WP_UnitTestCase {

	function test_enabled_widget() {

		global $wp_widget_factory;

		/* 1: Test that enabling the option actually registers the widget */

		// First, make sure that nothing else registered this widget.
		unregister_widget('largo_post_series_links_widget');

		// Set the option
		of_set_option('series_enabled', '1');

		largo_widgets();

		// Make sure it's registered and the correct type.
		$widget_obj = $wp_widget_factory->widgets['largo_series_posts_widget'];
		$this->assertTrue( is_a($widget_obj, 'WP_Widget') );

		/* 2: Test that disabling the option actually disabled the registration of the widget */

		// Unregister it.
		unregister_widget('largo_post_series_links_widget');

		// Set the option
		of_set_option('series_enabled', false);

		largo_widgets();

		// $wp_widget_factory shouldn't know anything about the disclaimer widget anymore.
		$this->assertFalse( array_key_exists('largo_post_series_links_widget',$wp_widget_factory->widgets) );

	}
}
