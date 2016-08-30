<?php
	
/**
 * Returns a Facebook username or ID from the URL
 * 
 * @param   string  $url a Facebook url
 * @return  string  the Facebook username or id extracted from the input string
 * @since   0.4
 */
function largo_fb_url_to_username( $url )  {
	$urlParts = explode( "/", $url );
	if ( end( $urlParts ) == '' ) {
		// URL has a trailing slash
		$urlParts = array_slice( $urlParts, 0 , -1 );
	}
	$username = end( $urlParts );
	if ( preg_match( "/profile.php/", $username ) ) {
		// a profile id
		preg_match( "/id=([0-9]+)/", $username, $matches );
		$username = $matches[1];
	} else {
		// hopefully there's a username
		preg_match( "/[^\?&#]+/", $username, $matches);
		if ( isset( $matches[0] ) ){
			$username = $matches[0];
		}
	}
	
	return $username;
}

/**
 * Checks to see if a given Facebook username or ID has following enabled by 
 * checking the iframe of that user's "Follow" button for <table>.
 * Usernames that can be followed have <tables>.
 * Users that can't be followed don't.
 * Users that don't exist don't.
 * 
 * @param   string  $username a valid Facebook username or page name. They're generally indistinguishable, except pages get to use '-'
 * @uses    wp_remote_get
 * @return  bool    The user specified by the username or ID can be followed
 */
function largo_fb_user_is_followable( $username ) {
	// syntax for this iframe taken from https://developers.facebook.com/docs/plugins/follow-button/
	$get = wp_remote_get( 'https://www.facebook.com/plugins/follow.php?href=https%3A%2F%2Fwww.facebook.com%2F' . $username . '&amp;width&amp;height=80&amp;colorscheme=light&amp;layout=button&amp;show_faces=true' );
	if (! is_wp_error( $get ) ) {
		$response = $get['body'];
		if ( strpos( $response, 'table' ) !== false ) {
			// can follow
			return true;
		} else {
			// cannot follow
			return false;
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
function clean_user_fb_username( $user_id ) {

	if ( current_user_can( 'edit_user', $user_id ) ) {
		$fb = largo_fb_url_to_username( $_POST['fb'] );
		if ( preg_match( '/[^a-zA-Z0-9\.\-]/', $fb ) ) {
			// it's not a valid Facebook username, because it uses an invalid character
			$fb = "";
		}
		update_user_meta( $user_id, 'fb', $fb );
		if ( get_user_meta( $user_id, 'fb', true) != $fb ) {
			wp_die( __( 'An error occurred.', 'largo' ) );
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
 * Returns a Twitter username (without the @ symbol)
 *
 * @param 	string 	$url a twitter url
 * @return 	string	the twitter username extracted from the input string
 * @since 	0.3
 */
function largo_twitter_url_to_username( $url ) {
	$urlParts = explode("/", $url);
	if ( end($urlParts) == '' ) {
		// URL has a trailing slash
		$urlParts = array_slice($urlParts, 0 , -1);
	}
	$username = preg_replace( "/@/", '', end( $urlParts ) );
	// strip the ?&# URL parameters if they're present
	// this will let through all other characters
	preg_match( "/[^\?&#]+/", $username, $matches );
	if ( isset( $matches[0] ) ){
		$username = $matches[0];
	}
	return $username;
}

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
function clean_user_twitter_username( $user_id ) {

	if ( current_user_can( 'edit_user', $user_id ) ) {
		$twitter = largo_twitter_url_to_username( $_POST['twitter'] );
		if ( preg_match( '/[^a-zA-Z0-9_]/', $twitter ) ) {
			// it's not a valid twitter username, because it uses an invalid character
			$twitter = "";
		}
		update_user_meta( $user_id, 'twitter_link', $twitter );
		if ( get_user_meta( $user_id, 'twitter_link', true ) != $twitter ) {
			wp_die( __( 'An error occurred.', 'largo' ) );
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
 * Give it a YouTube URL, it'll give you just the video ID
 *
 * @param 	string 	$url a YouTube URL (e.g. - https://www.youtube.com/watch?v=i5vfw5f1CZo)
 * @return 	string	just the video ID (e.g. - i5vfw5f1CZo)
 * @since 0.4
 */
function largo_youtube_url_to_ID( $url ) {
	parse_str( parse_url( $url, PHP_URL_QUERY ), $var_array );
	$youtubeID = $var_array['v'];
	return $youtubeID;
}

/**
 * For a given YouTube URL, return an iframe to embed
 *
 * @param 	string 	$url a YouTube URL (e.g. - https://www.youtube.com/watch?v=i5vfw5f1CZo)
 * @param 	bool 	$echo return or echo the output
 * @return 	string	a standard YouTube iframe embed code
 * @uses 	largo_youtube_url_to_ID
 * @since 	0.4
 */
function largo_youtube_iframe_from_url( $url, $echo = TRUE ) {
	$output = '<iframe  src="//www.youtube.com/embed/' . largo_youtube_url_to_ID( $url ) . '" frameborder="0" allowfullscreen></iframe>';
	if ( $echo ) {
		echo $output;
	} else {
		return $output;
	}
}

/**
 * For a given YouTube URL, return the image url for various thumbnail sizes
 *
 * @param 	string 	$url a YouTube URL (e.g. - https://www.youtube.com/watch?v=i5vfw5f1CZo)
 * @param	string the image size you'd like (options are: thumb | small | medium | large)
 * @param 	bool 	$echo return or echo the output
 * @return 	string	a youtube image url
 * @uses 	largo_youtube_url_to_ID
 * @since 0.4
 */
function largo_youtube_image_from_url( $url, $size = large, $echo = TRUE ) {
	$id = largo_youtube_url_to_ID( $url );

	$output = 'http://img.youtube.com/vi/' . $id;
	switch( $size ) {
		case 'thumb':
			$output .= '/default.jpg'; // 120 x 90
			break;
		case 'small':
			$output .= '/hqdefault.jpg'; // 480 x 360
			break;
		case 'medium':
			$output .= '/sddefault.jpg'; // 640 x 480
			break;
		case 'large':
			$output .= '/maxresdefault.jpg'; // 1280 x 720
			break;
	}

	if ( $echo ) {
		echo $output;
	} else {
		return $output;
	}
}

/**
 * Transform user-entered text into WP-compatible slugs
 *
 * @param 	string 	$string		the string to turn into a slug
 * @param 	string	$maxLength 	the max length for the slug in characters
 * @since 	0.4
 */
function largo_make_slug( $string, $maxLength = 63 ) {
  $result = preg_replace( '/[^a-z0-9\s-]/', '', strtolower( $string ) );
  $result = trim( preg_replace( '/[\s-]+/', ' ', $result ) );
  $result = trim( substr( $result, 0, $maxLength ) );
  return preg_replace( '/\s/', '-', $result );
}

/**
 * @param string $slug the slug of the template file to render.
 * @param string $name the name identifier for the template file; works like get_template_part.
 * @param array $context an array with the variables that should be made available in the template being loaded.
 * @since 0.4
 */
function largo_render_template( $slug, $name=null, $context=array() ) {
	global $wp_query;

	if ( is_array( $name ) && empty( $context ) )
		$context = $name;

	if ( ! empty( $context ) ) {
		$context = apply_filters( 'largo_render_template_context', $context, $slug, $name );
		$wp_query->query_vars = array_merge( $wp_query->query_vars, $context );
	}

	get_template_part( $slug, $name );
}

/**
 * Get the current URL, including the protocol and host
 *
 * @since 0.5
 */
function largo_get_current_url() {
	$is_ssl = is_ssl();
	if ( ! empty( $is_ssl ) )
		return "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	else
		return "http://" .$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
}

/**
 * Return the first featured image thumbnail found in a given array of WP_Posts
 *
 * Useful if you wint to create a thumbnail for a given taxonomy
 *
 * @param array An array of WP_Post objects to iterate over
 * @return str|false The HTML for the image, or false if no images were found.
 * @since 0.5.3
 * @uses largo_has_featured_media
 */
function largo_first_thumbnail_in_post_array( $array ) {
	$thumb = '';
	foreach ( $array as $post ) {
		$thumb = get_the_post_thumbnail( $post->ID );
		if ( $thumb != '' ) return $thumb;
	}
	return $thumb;
}

/**
 * Return the first headline link for an array of WP_Posts
 *
 * Useful if you want to link to an example post in a series.
 *
 * @param array An array of WP_Post objects to iterate over
 * @return str The HTML for the link
 * @since 0.5.3
 */
function largo_first_headline_in_post_array( $array ) {
	$headline = '';
	foreach ( $array as $post ) {
		$headline = sprintf( '<a href="%s">%s</a>',
			get_permalink( $post->ID ),
			get_the_title( $post->ID )
		);
		if ( $headline != '') return $headline;
	}
	return $headline;
}

/**
 * Send anything to the error log in a human-readable format
 *
 * @param 	mixed $stuff the stuff to be sent to the error log.
 * @since 	0.4
 */
if (!  function_exists( 'var_log' ) ) {
	function var_log( $stuff ) {
		error_log( var_export( $stuff, true ) );
	}
}