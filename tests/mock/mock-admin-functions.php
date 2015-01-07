<?php
class Mock_in_admin_WP_Screen {
	public function in_admin( $admin = null ) {
		if ( empty( $admin ) )
			return $GLOBALS['mock_in_admin'];
		return ( $admin == $this->in_admin);
	}
}
/**
 * Sets the admin to the parameter passed. Useful if your test needs to pass the is_admin() test. Uses $GLOBALS. 
 * @uses   $GLOBALS
 * @param  string    $admin  '', network, user, site, false
 */
function mock_in_admin($admin) {
	global $GLOBALS;
	$GLOBALS['mock_in_admin'] = $admin;
	$GLOBALS['current_screen'] = new Mock_in_admin_WP_Screen; 
}
/**
 * Undoes mock_in_admin($admin)
 * @uses   $GLOBALS
 *
 */
function unmock_in_admin() {
	if (isset($GLOBALS['mock_in_admin'])) {
		unset($GLOBALS['mock_in_admin']);
		unset($GLOBALS['current_screen']);
	}
}
