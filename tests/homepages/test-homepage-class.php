<?php

class TestHomepageLayout extends Homepage {
	var $zoneMarker;
}

class HomepageClassTest extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();

		$this->layoutOptions = array(
			'name' => 'Test Homepage Layout',
			'type' => 'homepage',
			'rightRail' => true,
			'template' => dirname(__DIR__) . '/templates/test-homepage.php',
			'sidebars' => array('Homepage Test Layout Sidebar'),
			'prominenceTerms' => array(
				array(
					'name' => 'Homepage Test Term',
					'description' => 'Homepage Test Term',
					'slug' => 'homepage-test-term'
				)
			),
			'assets' => array(
				array('homepage-asset-test-css', get_template_directory_uri() . '/doesnotexist.css', array()),
				array('homepage-asset-test-js', get_template_directory_uri() . '/doesnotexists.js', array())
			),
			'zoneMarker' => 'Hello World!'
		);
		$this->layout = new TestHomepageLayout($this->layoutOptions);

		global $wp_styles, $wp_scripts;
		$this->wp_styles_backup = $wp_styles;
		$this->wp_scripts_backup = $wp_scripts;
		$wp_styles = new WP_Styles;
		$wp_scripts = new WP_Scripts;
	}

	function tearDown() {
		global $wp_styles, $wp_scripts;
		$wp_styles = $this->wp_styles_backup;
		$wp_scripts = $this->wp_scripts_backup;

	}

	function testLoad() {
		// Hompage::load method should set an attribute on the Homepage object for
		// each option passed to the constructor
		foreach ($this->layoutOptions as $index => $option) {
			$this->assertEquals($this->layout->{$index}, $option);
		}

		// Homepage::load should set an id attribute based on the Homepage title
		$this->assertTrue((bool) $this->layout->id);
		$this->assertEquals($this->layout->id, sanitize_title($this->layout->name));
	}

	function testInit() {
		// Homepage::init is included as a way to transform or change a Homepage
		// object when it is constructed before Homepage::load runs. Only thing
		// to test is that it exists
		$this->assertTrue(method_exists($this->layout, 'init'));
	}

	function testIsActiveHomepageLayout() {
		// Our test layout should not be active homepage layout to start
		$this->assertFalse($this->layout->isActiveHomepageLayout());

		// After we set the homepage layout option, it should
		of_set_option('home_template', get_class($this->layout));
		$this->assertTrue($this->layout->isActiveHomepageLayout());
	}

	function testActivateTerms() {
		// Homepage::activateTerms should return an array that includes any term definitions
		// passed to the Homepage instance or defined on our Homepage class.
		$result = $this->layout->activateTerms();
		$this->assertEquals($this->layoutOptions['prominenceTerms'], $result);
	}

	function testRender() {
		// Our test template is `echo $zoneMarker;`. So, we should
		// see the result of rendering that template is the same
		// value as the layout options zoneMarker.
		$this->expectOutputString($this->layoutOptions['zoneMarker']);
		$this->layout->render();
	}

	function testRegister() {
		$this->markTestSkipped(
			"Functional test of Homepage::register performed via HomepageClassTest::testEnqueueAssets,\n" .
			"HomepageClassTest::testRegisterSidebars and HomepageClassTest::testSetRightRail");
	}

	function testEnqueueAssets() {
		// Homepage::enqueueAssets should add js and css asset definitions to the
		// appropriate global -- $wp_styles or $wp_scripts.
		global $wp_styles, $wp_scripts;

		$this->go_to('/'); // Run this on the homepage
		$this->layout->enqueueAssets();

		$expected_css_handle = $this->layoutOptions['assets'][0][0];
		$this->assertTrue(wp_style_is( $expected_css_handle, 'enqueued'), "Homepage styles were not enqueued on the homepage");

		$expected_js_handle = $this->layoutOptions['assets'][1][0];
		$this->assertTrue(wp_script_is( $expected_js_handle, 'enqueued'), "Homepage scripts were not enqueued on the homepage");
	}

	function testEnqueueAssets_791() {
		/*
		 * Regression test for #791, where homepage assets were being enqueued on pages not the homepage.
		 *
		 * @since 0.5.2
		 */

		// Homepage::enqueueAssets should add js and css asset definitions to the
		// appropriate global -- $wp_styles or $wp_scripts.
		global $wp_styles, $wp_scripts;

		// create a post
		$post_id = $this->factory->post->create();
		$this->go_to("/?p=$post_id"); // a page that is not the homepage

		$this->layout->enqueueAssets();
		
		$expected_css_handle = $this->layoutOptions['assets'][0][0];
		$this->assertFalse(wp_style_is( $expected_css_handle, 'enqueued'), "Homepage styles were enqueued on not the homepage");
		//$this->assertEmpty($wp_styles->registered[$expected_css_handle], "Homepage styles were enqueued on not the homepage");

		$expected_js_handle = $this->layoutOptions['assets'][1][0];
		$this->assertFalse(wp_script_is( $expected_js_handle, 'enqueued'), "Homepage scripts were enqueued on not the homepage");
		//$this->assertEmpty($wp_scripts->registered[$expected_js_handle], "Homepage scripts were enqueued on not the homepage");
	}

	function testRegisterSidebars() {
		// Homepage::registerSidebars should add any defined sidebars
		// to the global $wp_registered_sidebars array;
		global $wp_registered_sidebars;

		$this->layout->registerSidebars();

		$expected_sidebar_slug = sanitize_title($this->layoutOptions['sidebars'][0]);
		$this->assertTrue(!empty($wp_registered_sidebars[$expected_sidebar_slug]));
	}

	function testSetRightRail() {
		// Homepage::setRightRail should set the home_rail key of the $largo globl
		// to the value of rightRail either passed as an option or defined as an
		// attribute of our Homepage class.
		global $largo;
		$this->layout->setRightRail();
		$this->assertEquals($largo['home_rail'], $this->layoutOptions['rightRail']);
	}

	function testPopulateId() {
		// Make sure that Homepage::populateId properly sets the id attribute of our
		// homepage layout to a slugified version of the name attribue.
		$this->assertEquals($this->layout->id, sanitize_title($this->layoutOptions['name']));
	}

	function testReadZones() {
		// Homepage::readZones should populate the zones attribute of our
		// homepage layout with any potential zone markers in our template file.
		foreach ($this->layout->zones as $zone)
			$this->assertTrue(in_array($zone, array_keys($this->layoutOptions)));
	}

}
