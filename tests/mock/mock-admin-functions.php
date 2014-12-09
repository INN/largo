<?php
/**
 * Sets the admin to the parameter passed. Useful if your test needs to pass the is_admin() test. Uses $GLOBALS. 
 * @uses   $GLOBALS
 * @param  $admin  '', network, user, site, false
 */
class Mock_in_admin_WP_Screen {
	public function in_admin( $admin = null ) {
		if ( empty( $admin ) )
			return $GLOBALS['mock_in_admin'];
		return ( $admin == $this->in_admin);
	}
}
function mock_in_admin($admin) {
	/**
	 * Spoofs is_admin() by setting $GLOBALS['current_screen'] to a bogus WP_Screen object
	 */
	global $GLOBALS;
	$GLOBALS['mock_in_admin'] = $admin;
	$GLOBALS['current_screen'] = new Mock_in_admin_WP_Screen; 
}
