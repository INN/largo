<?php

class UpdateTestFunctions extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();

		remove_filter('sanitize_option_optionsframework', 'optionsframework_validate');
		remove_filter('sanitize_option_' . get_option('stylesheet'), 'optionsframework_validate');

		update_option('optionsframework', array(
			"id" => get_option('stylesheet'),
			"knownoptions" => array(get_option('stylesheet'))
		));

		// Set "old" options
		$this->previous_options = array(
			'social_icons_display' => 'both',
			'in_series' => NULL,
			'show_tags' => 1,
			'show_author_box' => 1,
			'show_related_content' => 1,
			'show_next_prev_nav_single' => 1,
			'largo_version' => NULL
		);
		update_option(get_option('stylesheet'), $this->previous_options);


		$this->term_ids = $this->factory->term->create_many( 10, array(
			'taxonomy' => 'prominence'
		));
	}

	function tearDown() {
		of_reset_options();
		parent::tearDown();
	}

	function test_largo_version() {
		$return = largo_version();
		$this->assertTrue(isset($return));
	}

	function test_largo_need_updates() {
		// force updates by setting current version of largo to 0.0
		of_set_option('largo_version', '0.0');
		$this->assertTrue(largo_need_updates());

		// Will we ever hit this version number?
		of_set_option('largo_version', '999.999');
		$this->assertFalse(largo_need_updates());

		of_reset_options();
	}

	function test_largo_perform_update() {
		// requires largo_need_updates

		// Backup sidebar widgets, create our own empty sidebar
		$widgets_backup = get_option( 'sidebars_widgets ');
		update_option( 'sidebars_widgets', array(
			'article-bottom' => array (), // largo_instantiate_widget uses article-bottom for all its widgets
		) );

		// force updates by setting current version of largo to 0.0
		of_set_option('largo_version', '0.0');

		largo_perform_update();

		// check that options have been set
		$this->assertEquals(largo_version(), of_get_option('largo_version'));

		// Cleanup
		delete_option('sidebars_widgets');
		update_option('sidebars_widgets', $widgets_backup);
		unset($widgets_backup);
	}

	function largo_set_new_option_defaults() {
		$this->markTestIncomplete('This function relies on the real options framework');
	}

	function test_largo_home_transition() {
		// old topstories
		update_option(
			get_option('stylesheet'),
			array_merge($this->previous_options, array('homepage_top' => 'topstories'))
		);
		largo_preserve_previous_options();
		of_reset_options();
		largo_home_transition();
		$this->assertEquals('TopStories', of_get_option('home_template', 0));

		// old slider
		update_option(
			get_option('stylesheet'),
			array_merge($this->previous_options, array('homepage_top' => 'slider'))
		);
		largo_preserve_previous_options();
		of_reset_options();
		largo_home_transition();
		$this->assertEquals('HomepageBlog', of_get_option('home_template', 0));

		// old blog
		update_option(
			get_option('stylesheet'),
			array_merge($this->previous_options, array('homepage_top' => 'blog'))
		);
		largo_preserve_previous_options();
		of_reset_options();
		largo_home_transition();
		$this->assertEquals('HomepageBlog', of_get_option('home_template', 0));

		// Not actually set
		update_option(get_option('stylesheet'), $this->previous_options);
		largo_preserve_previous_options();
		of_reset_options();
		largo_home_transition();
		$this->assertEquals('HomepageBlog', of_get_option('home_template', 0));
	}

	function test_largo_update_widgets() {
		// Backup sidebar widgets, create our own empty sidebar
		$widgets_backup = get_option('sidebars_widgets');
		update_option('sidebars_widgets', array(
			'article-bottom' => array(), // largo_instantiate_widget uses article-bottom for all its widgets
		));

		// These should all trigger a widget addition.
		update_option(get_option('stylesheet'), $this->previous_options);
		largo_preserve_previous_options();
		largo_update_widgets();

		// Check for expected output
		// These are all #2 because they are the first instance of the widget
		$widgets = get_option('sidebars_widgets');
		$this->assertEquals('largo-follow-widget-2', $widgets['article-bottom'][0]);
		$this->assertEquals('largo-post-series-links-widget-2', $widgets['article-bottom'][1]);
		$this->assertEquals('largo-tag-list-widget-2', $widgets['article-bottom'][2]);
		$this->assertEquals('largo-author-widget-2', $widgets['article-bottom'][3]);
		$this->assertEquals('largo-related-posts-widget-2', $widgets['article-bottom'][4]);
		$this->assertEquals('largo-prev-next-post-links-widget-2', $widgets['article-bottom'][5]);

		// Cleanup
		delete_option('sidebars_widgets');
		update_option('sidebars_widgets', $widgets_backup);
	}

	function test_largo_widget_in_region() {
		$widgets_backup = get_option('sidebars_widgets');

		// A widget that exists in a sidebar that does
		update_option('sidebars_widgets', array(
			'sidebar-single' => array (
				0 => 'archives-2',
			),
		));
		$largo_widget_in_region_true = largo_widget_in_region('archives', 'sidebar-single');
		$this->assertTrue($largo_widget_in_region_true);

		// A widget that does not exist in a sidebar that does
		update_option('sidebars_widgets', array(
			'sidebar-single' => array (
				0 => 'archives-2',
			),
		));
		$largo_widget_in_region_false = largo_widget_in_region('wodget', 'sidebar-single');
		$this->assertFalse($largo_widget_in_region_false);

		// A widget that does not exist in a sidebar that also does not
		$largo_widget_in_region_false_again = largo_widget_in_region('wodget', 'missing-region');
		$this->assertTrue(!$largo_widget_in_region_false_again);

		// Cleanup
		delete_option('sidebars_widgets');
		update_option('sidebars_widgets', $widgets_backup);
	}

	function test_largo_instantiate_widget() {
		$widgets_backup = get_option('sidebars_widgets');

		update_option('sidebars_widgets', array(
			'article-bottom' => array ()
		));

		// Instantiate a widget
		$ret = largo_instantiate_widget('largo-follow', array('title' => ''), 'article-bottom');

		$widgets = get_option('sidebars_widgets');

		$this->assertEquals(
			'largo-follow-widget-2', $widgets['article-bottom'][0],
			"First widget: largo_instantiate_widget did not create the expected Largo Follow widget in the first position of the Article Bottom sidebar.");

		$this->assertInternalType(
			'array', $ret, "First widget: largo_instantiate_widget did not return an array");

		$this->assertEquals(
			'largo-follow-widget-2', $ret['id'],
			"First widget: largo_instantiate_widget did not return an array whose index 'id' matched the created widget");

		$this->assertEquals(
			0, $ret['place'],
			"First widget: largo_instantiate_widget did not return an array whose index 'place' matched the created widget's position in its sidebar.");

		// Instantiate another widget
		$ret = largo_instantiate_widget('largo-about', array('title' => ''), 'article-bottom');

		$widgets = get_option('sidebars_widgets');

		$this->assertEquals(
			'largo-about-widget-2', $widgets['article-bottom'][1],
			"Second widget: largo_instantiate_widget did not create the expected Largo About widget in the second position of the Article Bottom sidebar.");

		$this->assertInternalType('array', $ret, "Second widget: largo_instantiate_widget did not return an array");

		$this->assertEquals(
			'largo-about-widget-2', $ret['id'],
			"Second widget: largo_instantiate_widget did not return an array whose index 'id' matched the created widget");

		$this->assertEquals(
			1, $ret['place'],
			"Second widget: largo_instantiate_widget did not return an array whose index 'place' matched the created widget's position in its sidebar.");

		// Regression test: If you create a bunch of widgets, they should not all have the same number
		$ret3 = largo_instantiate_widget('largo-about', array( 'title' => '' ), 'article-bottom');
		$ret4 = largo_instantiate_widget('largo-about', array( 'title' => '' ), 'article-bottom');

		$this->assertFalse(
			($ret3['place'] == $ret4['place']),
			"Third and Fourth widgets: largo_instantiate_widget is not detecting the presence of existing widgets, and is assigning the same widget id to all new widgets.");

		delete_option('sidebars_widgets');
		update_option('sidebars_widgets', $widgets_backup);
	}

	function test_largo_check_deprecated_widgets() {
		// Backup sidebar widgets
		$widgets_backup = get_option('sidebars_widgets');

		// Create the deprecated widgets
		update_option('sidebars_widgets', array(
			'article-bottom' => array (
				0 => 'largo-footer-featured-2',
				1 => 'largo-sidebar-featured-2',
			), // largo_instantiate_widget uses article-bottom for all its widgets
		));

		largo_check_deprecated_widgets();

		// Test that the requisite deprecated widgets actions have been added to the admin_notices hook
		$return = has_action('admin_notices', 'largo_deprecated_footer_widget');
		$this->assertTrue(isset($return));
		unset($return);

		$return = has_action('admin_notices', 'largo_deprecated_sidebar_widget');
		$this->assertTrue(isset($return));
		unset($return);

		// Cleanup
		of_reset_options();
		delete_option('sidebars_widgets');
		update_option('sidebars_widgets', $widgets_backup);
	}

	function test_largo_deprecated_footer_widget() {
		// prints a nag
		// uses __
		$this->expectOutputRegex('/[.*]+/'); // This is excessively greedy, it expects any output at all
		largo_deprecated_footer_widget();
	}

	function test_largo_deprecated_sidebar_widget() {
		// prints a nag
		// uses __
		$this->expectOutputRegex('/[.*]+/'); // This is excessively greedy, it expects any output at all
		largo_deprecated_sidebar_widget();
	}

	function test_largo_transition_nav_menus() {
		// Test the function's ability to create the Main Navigation nav menu
		$this->assertFalse(wp_get_nav_menu_object('Main Navigation'));
		largo_transition_nav_menus();
		$this->assertObjectHasAttribute('name', wp_get_nav_menu_object('Main Navigation'));

		// Let's create some menus and menu items to be transitioned:
		$locations = get_nav_menu_locations();
		$navs = array(
			'Navbar Categories' => 'navbar-categories',
			'Navbar Supplemental' => 'navbar-supplemental',
			'Sticky Nav' => 'sticky-nav',
		);

		foreach ($navs as $nav_title => $nav_slug) {
			// create the menu
			$id = wp_create_nav_menu($nav_title);
			$locations[$nav_slug] = $id;

			// create a nav menu item
			wp_update_nav_menu_item($id, 0, array(
				'menu-item-title' => $nav_title . ' item',
				'menu-item-classes' => $nav_slug,
				'menu-item-url' => home_url( '/' . $nav_slug . '/' ),
				'menu-item-status' => 'publish'
				));
		}
		set_theme_mod('nav_menu_locations', $locations);

		// make sure that they're created, and we have no spares
		$this->assertEquals(4, count(get_nav_menu_locations()));

		largo_transition_nav_menus();

		// make sure the old navs are deleted
		$this->assertEquals(1, count(get_nav_menu_locations()));
		// make sure the old navs' items made it to the Main Navigation menu
		$this->assertEquals(3, count(wp_get_nav_menu_items('Main Navigation')));
	}

	function test_largo_update_prominence_term_description_single() {
		$term9 = get_term_by('slug', 'term-9', 'prominence', 'ARRAY_A');
		if ( ! $term9 ) {
			wp_insert_term( 'term-9', 'prominence', array( 'description' => 'Term Description 9' ) );
		}

		$update = array(
			'name' => 'Term 9',
			'description' => 'Term 9 From Outer Space',
			'olddescription' => 'Term Description 9',
			'slug' => 'term-9'
		);
		$term_descriptions = array('Term Description 9');

		$return = largo_update_prominence_term_description_single($update, $term_descriptions);
		$this->assertTrue(is_array($return));

		$term9 = get_term_by('slug', 'term-9', 'prominence', 'ARRAY_A');
		$this->assertEquals('Term 9 From Outer Space', $term9['description']);
	}

	function test_largo_update_prominence_term_descriptions() {
		//This test checks that largo_update_prominence_term_description_single was run with certain arguments.

		// This is the only term of the set that largo_update_prominence_term_descriptions updates that is not already created by the time the test is run.
		$term_ids = $this->factory->term->create_many( 1, array(
			'taxonomy' => 'prominence'
		));
		wp_update_term(
			$term_ids[0], 'prominence',
			array(
				'name' => 'Top Story',
				'description' => 'If you are using the Newspaper or Carousel optional homepage layout, add this label to label to a post to make it the top story on the homepage.',
				'slug' => 'top-story'
			)
		);

		largo_update_prominence_term_descriptions();

		$test_post = get_term($term_ids[0], 'prominence', 'ARRAY_A');
		$this->assertEquals(
			'If you are using a "Big story" homepage layout, add this label to a post to make it the top story on the homepage', $test_post['description']);
	}

	function test_largo_force_settings_update() {
		of_reset_options();
		largo_force_settings_update();
		$this->assertEquals('normal', of_get_option('single_template'));
	}

	// Test functions related to the WP admin workflow views
	function test_largo_update_admin_notice() {
		$_GET['page'] = 'update-largo';
		$this->expectOutputRegex('/[.*]+/'); // This is excessively greedy, it expects any output at all
		$this->expectOutputRegex('/<div class="update-nag"/'); // Expecting an update nag
		largo_update_admin_notice();

		$_GET['page'] = '';
		$this->expectOutputRegex('//'); // There should be no output because the update-largo page is not being GET'd
		largo_update_admin_notice();
	}

	function test_largo_register_update_page() {
		// Testing this would be the equivalent to a functional test of WordPress' core
		// add_submenu_page, so let's just make sure the function exists.
		$this->assertTrue(function_exists('largo_register_update_page'));
	}

	function test_largo_update_page_view() {
		$this->expectOutputRegex('/[.*]+/'); // This is excessively greedy, it expects any output at all
		largo_update_page_view();
	}

	function test_largo_update_page_enqueue_js() {
		global $wp_scripts;

		largo_update_page_enqueue_js();
		$this->assertTrue(empty($wp_scripts->registered['largo_update_page']));

		$_GET['page'] = 'update-largo';
		largo_update_page_enqueue_js();
		$this->assertTrue(!empty($wp_scripts->registered['largo_update_page']));
	}

	function test_largo_update_custom_less_variables() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_remove_topstory_prominence_term() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_enable_if_series() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_enable_series_if_landing_page() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_replace_deprecated_widgets() {
		// First, create some deprecated widgets
		largo_instantiate_widget('largo-sidebar-featured', array('title'=>'Foo'), 'sidebar-single');
		largo_instantiate_widget('largo-footer-featured', array('title'=>'Bar'), 'footer-1');
		largo_instantiate_widget('largo-featured', array('title'=>'Baz'), 'sidebar-main');
		largo_instantiate_widget('largo-follow', array('title'=>'Baz'), 'homepage-alert');
		largo_instantiate_widget('largo-recent-posts', array('title'=>'Baz'), 'homepage-alert');

		// chek that things were set up correctly
		$this->assertTrue(
			largo_widget_in_region('largo-sidebar-featured', 'sidebar-single'),
			"The Largo Sidebar Featured widget was left in the Sidebar Single widget area.");

		$this->assertTrue(
			largo_widget_in_region('largo-footer-featured', 'footer-1'),
			"The Largo Footer Featured widget was left in the Footer 1 widget area.");

		$this->assertTrue(
			largo_widget_in_region('largo-featured', 'sidebar-main'),
			"Setup: The old Largo Featured widget was not created in the Sidebar Main widget area.");

		$this->assertTrue(
			largo_widget_in_region('largo-follow', 'homepage-alert'),
			"Setup: The Largo Follow widget was not created in the Homepage Alert widget area.");

		$this->assertTrue(
			largo_widget_in_region('largo-recent-posts', 'homepage-alert'),
			"Setup: The Largo Recent Posts was not created in the Homepage Alert widget area.");

		// Run the actual updates
		largo_replace_deprecated_widgets();

		// This array is currently unused.
		$updates = array(
			'largo-sidebar-featured' => array(
				'name' => 'largo-recent-posts',
				'count' => 0,
			),
			'largo-sidebar-featured' => array(
				'name' => 'largo-recent-posts',
				'count' => 0,
			),
			'largo-featured' => array(
				'name' => 'largo-recent-posts',
				'count' => 0,
			)
		);
		// You will want to check this later;
		$this->assertFalse(
			largo_widget_in_region('largo-sidebar-featured', 'sidebar-single'),
			"The Largo Sidebar Featured widget was left in the Sidebar Single widget area.");

		$this->assertFalse(
			largo_widget_in_region('largo-footer-featured', 'footer-1'),
			"The Largo Footer Featured widget was left in the Footer 1 widget area.");

		$this->assertTrue(
			largo_widget_in_region('largo-recent-posts', 'sidebar-single'),
			"The new Largo Featured widget was not found in the Sidebar Single widget area.");

		$this->assertTrue(
			largo_widget_in_region('largo-recent-posts', 'footer-1'),
			"The new Largo Featured widget was not found in the Footer 1 widget area.");

		$this->assertTrue(
			largo_widget_in_region('largo-recent-posts', 'sidebar-main'),
			"The old Largo Featured widget was not found in the Sidebar Main widget area.");

		$this->assertTrue(
			largo_widget_in_region('largo-follow', 'homepage-alert'),
			"The Largo Follow widget was not found in the Homepage Alert widget area.");

		$this->assertTrue(
			largo_widget_in_region('largo-recent-posts', 'homepage-alert'),
			"The old Largo Featured widget was not found in the Homepage Alert widget area.");

	}

	function test_largo_deprecated_callback_largo_featured() {
		$replacement = array(
			'foo' => 'bar',
		);
		$deprecated = array(
			'thumb' => 'there was an old farmer',
		);
		$return = largo_deprecated_callback_largo_featured($deprecated, $replacement);
		$this->assertEquals($return['thumbnail_display'], $deprecated['thumb']);
		$this->assertEquals($return['foo'], $replacement['foo']);
	}
}

class LargoUpdateTestAjaxFunctions extends WP_Ajax_UnitTestCase {

	function setUp() {
		parent::setUp();

		remove_filter('sanitize_option_optionsframework', 'optionsframework_validate');
		remove_filter('sanitize_option_' . get_option('stylesheet'), 'optionsframework_validate');

		update_option('optionsframework', array(
			"id" => get_option('stylesheet'),
			"knownoptions" => array(get_option('stylesheet'))
		));

		$this->previous_options = array(
			'social_icons_display' => 'both',
			'in_series' => NULL,
			'show_tags' => 1,
			'show_author_box' => 1,
			'show_related_content' => 1,
			'show_next_prev_nav_single' => 1,
			'largo_version' => NULL
		);
		update_option(get_option('stylesheet'), $this->previous_options);
	}

	function test_largo_ajax_update_database_false() {
		// If the install doesn't need to be updated, this should return json with success == false
		of_set_option('largo_version', largo_version());

		try {
			$this->_handleAjax("largo_ajax_update_database");
		} catch (WPAjaxDieContinueException $e) {
			$resp = json_decode($this->_last_response, true);
			$this->assertTrue($resp['success'] == false);
		}
	}

	function test_largo_ajax_update_database_true() {
		// If the install needs updated, this should return json with success == true
		of_set_option('largo_version', null);

		try {
			$this->_handleAjax("largo_ajax_update_database");
		} catch (WPAjaxDieContinueException $e) {
			$resp = json_decode($this->_last_response, true);
			$this->assertTrue($resp['success'] == true);
		}
	}
}
