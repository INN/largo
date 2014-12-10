<?php
/**
 * Returns a Facebook username or ID from the URL
 * 
 * @param   string  $url a Facebook url
 * @return  string  the Facebook username or id extracted from the input string
 * @since   0.4
 */
function largo_fb_url_to_username( $url )  {
	$urlParts = explode("/", $url);
	if ( end($urlParts) == '' ) {
		// URL has a trailing slash
		$urlParts = array_slice($urlParts, 0 , -1);
	}
	$username = end($urlParts);
	if ( preg_match( "/profile.php/", $username ) ) {
		// a profile id
		preg_match( "/id=([0-9]+)/", $username, $matches );
		$username = $matches[1];
	} else {
		// hopefully there's a username
		preg_match( "/[^\?&#]+/", $username, $matches);
		if (isset($matches[0])){
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
	$get = wp_remote_get( "https://www.facebook.com/plugins/follow.php?href=https%3A%2F%2Fwww.facebook.com%2F" . $username . "&amp;width&amp;height=80&amp;colorscheme=light&amp;layout=button&amp;show_faces=true");
	if (! is_wp_error( $get ) ) {
		$response = $get['body'];
		if ( strpos($response, 'table') !== false ) {
			// can follow
			return true;
		} else {
			// cannot follow
			return false;
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
	$username = preg_replace( "/@/", '', end($urlParts) );
	// strip the ?&# URL parameters if they're present
	// this will let through all other characters
	preg_match( "/[^\?&#]+/", $username, $matches);
	if (isset($matches[0])){
		$username = $matches[0];
	}
	return $username;
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
 * Send anything to the error log in a human-readable format
 *
 * @param 	mixed $stuff the stuff to be sent to the error log.
 * @since 	0.4
 */
function var_log($stuff) {
	error_log(var_export($stuff, true));
}
/**
 * @param string $slug the slug of the template file to render.
 * @param string $name the name identifier for the template file; works like get_template_part.
 * @param array $context an array with the variables that should be made available in the template being loaded.
 * @since 0.4*
 */
function largo_render_template($slug, $name=null, $context=array()) {
	global $wp_query;

	if (is_array($name) && empty($context))
		$context = $name;

	if (!empty($context)) {
		$context = apply_filters('largo_render_template_context', $context, $slug, $name);
		$wp_query->query_vars = array_merge($wp_query->query_vars, $context);
	}

	get_template_part($slug, $name);
}
