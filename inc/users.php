<?php
    function largo_contactmethods( $contactmethods ) {

	     // Remove Yahoo IM
	    if ( isset( $contactmethods['yim'] ) )
	    unset( $contactmethods['yim'] );

	    // Remove AIM
	    if ( isset( $contactmethods['aim'] ) )
	    unset( $contactmethods['aim'] );

	    // Remove Jabber
	    if ( isset( $contactmethods['jabber'] ) )
	    unset( $contactmethods['jabber'] );

        // Add a format hint for G+
	    $contactmethods['googleplus'] = 'Google+<br><em>https://plus.google.com/userID/<em>';

	    // Add Twitter
	    if ( !isset( $contactmethods['twitter'] ) )
	    $contactmethods['twitter'] = 'Twitter<br><em>https://twitter.com/username<em>';

	    // Add Facebook
	    if ( !isset( $contactmethods['facebook'] ) )
	    $contactmethods['Facebook'] = 'Facebook<br><em>https://www.facebook.com/username<em>';

	    return $contactmethods;
	}

	add_filter( 'user_contactmethods', 'largo_contactmethods', 10, 1 );
?>