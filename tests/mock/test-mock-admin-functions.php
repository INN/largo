<?php
class MockAdminTestFunctions extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();
	}
	function test_mock_in_admin() {
		mock_in_admin('foo');
		$this->assertEquals('foo', $GLOBALS['mock_in_admin']);
		$this->assertEquals('foo', $GLOBALS['current_screen']->in_admin());
		unmock_in_admin();
	}
	function test_unmock_in_admin() {
		mock_in_admin('foo');
		unmock_in_admin();
		$this->assertEquals(false, isset($GLOBALS['mock_in_admin']));
		$this->assertEquals(false, isset($GLOBALS['current_screen']));
	}
}
