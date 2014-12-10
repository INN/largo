<?php

/**
 * Modify the user profile screen
 * Remove old contact methods (yahoo, aol and jabber)
 * Add new ones (twitter, facebook, linkedin)
 *
 * @since 1.0
 */
function largo_contactmethods( $contactmethods ) {

	$remove = array( 'yim', 'aim', 'jabber' );
	foreach ( $remove as $service ) {
		if ( isset( $contactmethods[$service] ) )
			unset( $contactmethods[$service] );
	}

	$add = array(
		'twitter' 	=> 'Twitter<br><em>https://twitter.com/username</em> or <em>@username</em> or <em>username</em>',
		'fb' 		=> 'Facebook<br><em>https://www.facebook.com/username</em>',
		'linkedin' 	=> 'LinkedIn<br><em>http://www.linkedin.com/in/username</em>'
	);
	foreach ( $add as $service => $format ) {
		if ( !isset( $contactmethods[$service] ) )
	    	$contactmethods[$service] = $format;
	}

	// Add a format hint for G+
	$contactmethods['googleplus'] = 'Google+<br><em>https://plus.google.com/userID/</em>';

	return $contactmethods;
}
add_filter( 'user_contactmethods', 'largo_contactmethods' );


/**
 * Same deal, but for guest authors in the Co-Authors Plus plugin
 * @TODO: figure out if there's a way to remove fields as we do for regular users above
 *
 * @since 1.0
 */
function largo_filter_guest_author_fields( $fields_to_return, $groups ) {

	if ( in_array( 'all', $groups ) || in_array( 'contact-info', $groups ) ) {
		$fields_to_return[] = array(
			'key'      => 'twitter',
			'label'    => 'Twitter<br><em>https://twitter.com/username</em>',
			'group'    => 'contact-info',
		);
		$fields_to_return[] = array(
			'key'      => 'fb',
			'label'    => 'Facebook<br><em>https://www.facebook.com/username</em>',
			'group'    => 'contact-info',
		);
		$fields_to_return[] = array(
			'key'      => 'linkedin',
			'label'    => 'LinkedIn<br><em>http://www.linkedin.com/in/username</em>',
			'group'    => 'contact-info',
		);
		$fields_to_return[] = array(
			'key'      => 'googleplus',
			'label'    => 'Google+<br><em>https://plus.google.com/userID/</em>',
			'group'    => 'contact-info',
		);
	}
	if ( in_array( 'all', $groups ) || in_array( 'name', $groups ) ) {
		$fields_to_return[] = array(
			'key'      => 'organization',
			'label'    => 'Organization',
			'group'    => 'name',
		);
	}

	return $fields_to_return;
}
add_filter( 'coauthors_guest_author_fields', 'largo_filter_guest_author_fields', 10, 2 );


/**
 * In a multisite network, allow site admins to edit user profiles
 * props: http://thereforei.am/2011/03/15/how-to-allow-administrators-to-edit-users-in-a-wordpress-network/
 *
 * @since 1.0
 */
function largo_admin_users_caps( $caps, $cap, $user_id, $args ){

    foreach( $caps as $key => $capability ){

        if( $capability != 'do_not_allow' )
            continue;

        switch( $cap ) {
            case 'edit_user':
            case 'edit_users':
                $caps[$key] = 'edit_users';
                break;
            case 'delete_user':
            case 'delete_users':
                $caps[$key] = 'delete_users';
                break;
            case 'create_users':
                $caps[$key] = $cap;
                break;
        }
    }

    return $caps;
}
add_filter( 'map_meta_cap', 'largo_admin_users_caps', 1, 4 );
remove_all_filters( 'enable_edit_any_user_configuration' );
add_filter( 'enable_edit_any_user_configuration', '__return_true');

/**
 * Checks that both the editing user and the user being edited are
 * members of the blog and prevents the super admin being edited.
 */
function largo_edit_permission_check() {
    global $current_user, $profileuser;

    $screen = get_current_screen();

    get_currentuserinfo();

    if( ! is_super_admin( $current_user->ID ) && in_array( $screen->base, array( 'user-edit', 'user-edit-network' ) ) ) { // editing a user profile
        if ( is_super_admin( $profileuser->ID ) ) { // trying to edit a superadmin while less than a superadmin
            wp_die( __( 'You do not have permission to edit this user.', 'largo' ) );
        } elseif ( ! ( is_user_member_of_blog( $profileuser->ID, get_current_blog_id() ) && is_user_member_of_blog( $current_user->ID, get_current_blog_id() ) )) { // editing user and edited user aren't members of the same blog
            wp_die( __( 'You do not have permission to edit this user.', 'largo' ) );
        }
    }

}
add_filter( 'admin_head', 'largo_edit_permission_check', 1, 4 );


/**
 * Cleans a Twitter url or an @username to the bare username when the user is edited
 *
 * Edits $_POST directly because there's no other way to save the corrected username
 * from this callback. The action hooks this is used for run before edit_user in 
 * wp-admin/user-edit.php, which overwrites the user's contact methods. edit_user 
 * reads from $_POST. 
 *
 * @param  object  $user_id the WP_User object being edited
 * @param  array   $_POST
 * @since  0.4
 * @uses   largo_twitter_url_to_username
 * @link   http://codex.wordpress.org/Plugin_API/Action_Reference/edit_user_profile_update
 * @link   http://codex.wordpress.org/Plugin_API/Action_Reference/personal_options_update
 */

add_action('edit_user_profile_update', 'clean_user_twitter_username');
add_action('personal_options_update', 'clean_user_twitter_username');

function clean_user_twitter_username($user_id) {

	if ( current_user_can('edit_user', $user_id) ) {
		$twitter = largo_twitter_url_to_username( $_POST['twitter'] );
		if ( preg_match( '/[^a-zA-Z0-9_]/', $twitter ) ) {
			// it's not a valid twitter username, because it uses an invalid character
			$twitter = "";
		}
		update_user_meta($user_id, 'twitter_link', $twitter);
		if ( get_user_meta($user_id, 'twitter_link', true) != $twitter ) {
			wp_die(__('An error occurred.'));
		}
		$_POST['twitter'] = $twitter;
	}
}

/**
 * Checks that the Twitter URL is composed of valid characters [a-zA-Z0-9_] and 
 * causes an error if there is not.
 *
 * @param   $errors the error object
 * @param   bool    $update whether this is a user update
 * @param   object  $user a WP_User object
 * @uses    largo_twitter_url_to_username
 * @link    http://codex.wordpress.org/Plugin_API/Action_Reference/user_profile_update_errors
 * @since   0.4
 */

add_action( 'user_profile_update_errors', 'validate_twitter_username', 10, 3);

function validate_twitter_username( $errors, $update, $user ) {

	if ( isset( $_POST["twitter"] ) ) {
		$tw_suspect = trim( $_POST["twitter"] );
		if( ! empty( $tw_suspect ) ) {
			if ( preg_match( '/[^a-zA-Z0-9_]/', largo_twitter_url_to_username( $tw_suspect ) ) ) {
				// it's not a valid twitter username, because it uses an invalid character
				$errors->add('twitter_username', '<b>' . $tw_suspect . '</b>' . __('is an invalid Twitter username.') . '</p>' . '<p>' . __('Twitter usernames only use the uppercase and lowercase alphabet letters (a-z A-Z), the Arabic numbers (0-9), and underscores (_).') );
			}
		}
	}
}

/**
 * Cleans a Facebook url to the bare username or id when the user is edited
 *
 * Edits $_POST directly because there's no other way to save the corrected username
 * from this callback. The action hooks this is used for run before edit_user in 
 * wp-admin/user-edit.php, which overwrites the user's contact methods. edit_user 
 * reads from $_POST. 
 *
 * @param  object  $user_id the WP_User object being edited
 * @param  array   $_POST
 * @since  0.4
 * @uses   largo_fb_url_to_username
 * @link   http://codex.wordpress.org/Plugin_API/Action_Reference/edit_user_profile_update
 * @link   http://codex.wordpress.org/Plugin_API/Action_Reference/personal_options_update
 */
add_action('edit_user_profile_update', 'clean_user_fb_username');
add_action('personal_options_update', 'clean_user_fb_username');

function clean_user_fb_username($user_id) {

	if ( current_user_can('edit_user', $user_id) ) {
		$fb = largo_fb_url_to_username( $_POST['fb'] );
		if ( preg_match( '/[^a-zA-Z0-9\.\-]/', $fb ) ) {
			// it's not a valid Facebook username, because it uses an invalid character
			$fb = "";
		}
		update_user_meta($user_id, 'fb', $fb);
		if ( get_user_meta($user_id, 'fb', true) != $fb ) {
			wp_die(__('An error occurred.'));
		}
		$_POST['fb'] = $fb;
	}
}
/**
 * Checks that the Facebook URL submitted is valid and the user is followable and causes an error if not
 *
 * @uses  largo_fb_url_to_username
 * @uses  largo_fb_user_is_followable
 * @param   $errors the error object
 * @param   bool    $update whether this is a user update
 * @param   object  $user a WP_User object
 * @link    http://codex.wordpress.org/Plugin_API/Action_Reference/user_profile_update_errors
 * @since   0.4
 */
add_action( 'user_profile_update_errors', 'validate_fb_username', 10, 3);

function validate_fb_username( $errors, $update, $user ) {

	if ( isset( $_POST["fb"] ) ) {
		$fb_suspect = trim( $_POST["fb"] );
		if( ! empty( $fb_suspect ) ) {
			$fb_user = largo_fb_url_to_username( $fb_suspect );
			if ( preg_match( '/[^a-zA-Z0-9\.\-]/', $fb_user ) ) {
				// it's not a valid Facebook username, because it uses an invalid character
				$errors->add('fb_username', '<b>' . $fb_suspect . '</b> ' . __('is an invalid Facebook username.') . '</p>' . '<p>' . __('Facebook usernames only use the uppercase and lowercase alphabet letters (a-z A-Z), the Arabic numbers (0-9), periods (.) and dashes (-)') );
			}
			if ( ! largo_fb_user_is_followable( $fb_user ) ) {
				$errors->add('fb_username',' <b>' . $fb_suspect . '</b> ' . __('does not allow followers on Facebook.') . '</p>' . '<p>' . __('<a href="https://www.facebook.com/help/201148673283205#How-can-I-let-people-follow-me?">Follow these instructions</a> to allow others to follow you.') );
			}
		}
	}
}
/**
 * Get users based on a role. Defaults to fetching all authors for the current blog.
 *
 * @param $args array Same as the options one would pass to `get_users` with one extra
 * key -- `roles` -- which should be an array of roles to include in the query.
 * @since 0.4
 */
function largo_get_user_list($args=array()) {
	$roles = (isset($args['roles']))? $args['roles'] : null;
	unset($args['roles']);

	$args = array_merge(array(
		'blog_id' => get_current_blog_id(),
		'include' => array(),
		'exclude' => array(),
		'role' => 'author',
		'orderby' => 'display_name'
	), $args);

	if (empty($roles)) {
		$users = get_users($args);
	} else {
		$users = array();
		foreach ($roles as $role) {
			$args['role'] = $role;
			$result = get_users($args);
			$users = array_merge($users, $result);
		}
	}
	return $users;
}

/**
 * Render a list of user profiles based on the array of users passed
 *
 * @param $users array The WP_User objects to use in rendering the list.
 * @param $show_users_with_empty_desc bool Whether we should skip users that have no bio/description.
 * @since 0.4
 */
function largo_render_user_list($users, $show_users_with_empty_desc=false) {
	foreach ($users as $user) {
		$desc = trim($user->description);
		if (empty($desc) && empty($show_users_with_empty_desc))
			continue;

		if (get_user_meta($user->ID, 'hide', true))
			continue;

		$ctx = array('author_obj' => $user);
		echo '<div class="author-box">';
		largo_render_template('partials/author-bio', 'description', $ctx);
		echo '</div>';
	}
}

/**
 * Shortcode version of `largo_render_user_list`
 *
 * @param $atts array The attributes of the shortcode.
 *
 * Example of possible attributes:
 *
 * 	[roster roles="author,contributor" include="292,12312" exclude="5002,2320" show_users_with_empty_desc="true"]
 *
 * @since 0.4
 */
function largo_render_staff_list_shortcode($atts=array()) {
	$options = array();

	$show_users_with_empty_desc = false;
	if (!empty($atts['show_users_with_empty_desc'])) {
		$show_users_with_empty_desc = ($atts['show_users_with_empty_desc'] == 'false')? false : true;
		unset($atts['show_users_with_empty_desc']);
	}

	if (!empty($atts['roles'])) {
		$roles = explode(',', $atts['roles']);
		$options['roles'] = array_map(function($arg) { return trim($arg); }, $roles);
	}

	if (!empty($atts['exclude'])) {
		$exclude = explode(',', $atts['exclude']);
		$options['exclude'] = array_map(function($arg) { return trim($arg); }, $exclude);
	}

	if (!empty($atts['include'])) {
		$exclude = explode(',', $atts['include']);
		$options['include'] = array_map(function($arg) { return trim($arg); }, $exclude);
	}

	$defaults = array(
		'roles' => array(
			'author'
		)
	);
	$args = array_merge($defaults, $options);
	largo_render_user_list(largo_get_user_list($args), $show_users_with_empty_desc);
}
add_shortcode('roster', 'largo_render_staff_list_shortcode');

/**
 * Display extra profile fields related to staff member status
 *
 * @param $users array The WP_User object for the current profile.
 * @since 0.4
 */
function more_profile_info($user) {
	$hide = get_user_meta( $user->ID, "hide", true );
	$emeritus = get_user_meta( $user->ID, "emeritus", true );
	$honorary = get_user_meta( $user->ID, "honorary", true );
	?>
	<h3>More profile information</h3>
	<table class="form-table">
		<tr>
			<th><label for="job_title">Job title</label></th>
			<td>
				<input type="text" name="job_title" id="job_title" value="<?php echo esc_attr( get_the_author_meta( 'job_title', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your job title.</span>
			</td>
		</tr>
		<?php if (current_user_can('edit_users')) { ?>
		<tr>
			<th><label for="staff_widget">Staff status</label></th>
			<td>
				<input type="checkbox" name="hide" id="hide"
					<?php if (esc_attr($hide) == "on") { ?>checked<?php }?> />
				<label for="hide"><?php _e("Hide in roster"); ?></label><br />

				<input type="checkbox" name="emeritus" id="emeritus"
					<?php if (esc_attr($emeritus) == "on") { ?>checked<?php } ?> />
				<label for="emeritus"><?php _e("Emeritus?"); ?></label><br />

				<input type="checkbox" name="honorary" id="honorary"
				<?php if (esc_attr($honorary) == "on") { ?>checked<?php } ?> />
				<label for="honorary"><?php _e("Honorary?"); ?></label>
			</td>
		</tr>
		<?php } ?>
		<?php do_action('largo_more_profile_information', $user); ?>
	</table>
<?php }
add_action( 'show_user_profile', 'more_profile_info' );
add_action( 'edit_user_profile', 'more_profile_info' );

/**
 * Save data from form elements added to profile via `more_profile_info`
 *
 * @param $user_id array The ID of the user for the profile being saved.
 * @since 0.4
 */
function save_more_profile_info($user_id) {
	if (!current_user_can('edit_user', $user_id ))
		return false;

	update_user_meta($user_id, 'job_title', $_POST['job_title']);
	if (isset($_POST['hide']))
		update_user_meta($user_id, 'hide', $_POST['hide']);
	if (isset($_POST['emeritus']))
		update_user_meta($user_id, 'emeritus', $_POST['emeritus']);
	if (isset($_POST['honorary']))
		update_user_meta($user_id, 'honorary', $_POST['honorary']);
}
add_action('personal_options_update', 'save_more_profile_info');
add_action('edit_user_profile_update', 'save_more_profile_info');
