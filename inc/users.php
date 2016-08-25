<?php

/**
 * Modify the user profile screen
 * Remove old contact methods (yahoo, aol and jabber)
 * Add new ones (twitter, facebook, linkedin)
 *
 * @since 0.1
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

// clean and validate fb and twitter usernames when user profiles are updated
add_action('edit_user_profile_update', 'clean_user_twitter_username');
add_action('personal_options_update', 'clean_user_twitter_username');
add_action( 'user_profile_update_errors', 'validate_twitter_username', 10, 3);

add_action( 'edit_user_profile_update', 'clean_user_fb_username' );
add_action( 'personal_options_update', 'clean_user_fb_username' );
add_action( 'user_profile_update_errors', 'validate_fb_username', 10, 3);

/**
 * Same deal, but for guest authors in the Co-Authors Plus plugin
 * @TODO: figure out if there's a way to remove fields as we do for regular users above
 *
 * @since 0.1
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
		$fields_to_return[] = array(
			'key'      => 'show_email',
			'label'    => 'Show Email Address',
			'group'    => 'contact-info',
			'input'    => 'checkbox',
			'type'     => 'checkbox',
		);
	}
	if ( in_array( 'all', $groups ) || in_array( 'name', $groups ) ) {
		$fields_to_return[] = array(
			'key'      => 'job_title',
			'label'    => 'Job Title',
			'group'    => 'name',
		);
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
 * @link http://thereforei.am/2011/03/15/how-to-allow-administrators-to-edit-users-in-a-wordpress-network/
 *
 * @since 0.3
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
 *
 * @since 0.3
 */
function largo_edit_permission_check() {
    global $current_user, $profileuser;

    $screen = get_current_screen();
    
    global $wp_version; 
    if ( $wp_version < 4.5 ) {
    	get_currentuserinfo();
    } else {
    	wp_get_current_user();
    }

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
	echo '<div class="user-list">';
	foreach ($users as $user) {
		$desc = trim($user->description);
		if (empty($desc) && ($show_users_with_empty_desc == false))
			continue;

		$hide = get_user_meta($user->ID, 'hide', true);
		if ($hide == 'on')
			continue;

		$ctx = array('author_obj' => $user);
		echo '<div class="author-box row-fluid">';
		largo_render_template('partials/author-bio', 'description', $ctx);
		echo '</div>';
	}
	echo '</div>';
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
	$show_email = get_user_meta( $user->ID, "show_email", true );
	$hide = get_user_meta( $user->ID, "hide", true );
	?>
	<h3><?php _e( 'More profile information', 'largo' ); ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="job_title"><?php _e( 'Job title', 'largo' ); ?></label></th>
			<td>
				<input type="text" name="job_title" id="job_title" value="<?php echo esc_attr( get_the_author_meta( 'job_title', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e( 'Please enter your job title.', 'largo' ); ?></span>
			</td>
		</tr>

		<tr>
			<th><label for="show_email"><?php _e( 'Show Email Address', 'largo' ); ?></label></th>
			<td>
				<input type="checkbox" name="show_email" id="show_email"
					<?php if (esc_attr($show_email) == "on" || empty($show_email)) { ?>checked<?php } ?> />
				<label for="show_email"><?php _e( 'Show email address publicly?', 'largo' ); ?></label><br />
			</td>
		</tr>

		<?php if (current_user_can('edit_users')) { ?>
		<tr>
			<th><label for="staff_widget"><?php _e( 'Staff status', 'largo' ); ?></label></th>
			<td>
				<input type="checkbox" name="hide" id="hide"
					<?php if (esc_attr($hide) == "on") { ?>checked<?php }?> />
				<label for="hide"><?php _e( 'Hide in roster', 'largo' ); ?></label><br />
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
	
	if ( ! isset($_POST['show_email']) ) {
		$_POST['show_email'] = 'off';
	}

	$values = wp_parse_args($_POST, array(
		'show_email' => 'on',
		'hide' => 'off'
	));

	extract($values);

	update_user_meta($user_id, 'job_title', $job_title);
	update_user_meta($user_id, 'show_email', $show_email);
	update_user_meta($user_id, 'hide', $hide);
}
add_action('personal_options_update', 'save_more_profile_info');
add_action('edit_user_profile_update', 'save_more_profile_info');
