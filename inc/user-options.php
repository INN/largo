<?php
    function largo_contactmethods( $contactmethods ) {
	    // Add Twitter
	    if ( !isset( $contactmethods['twitter'] ) )
	    $contactmethods['twitter'] = 'Twitter (Profile URL)';

	    // Add Facebook
	    if ( !isset( $contactmethods['facebook'] ) )
	    $contactmethods['Facebook'] = 'Facebook (Profile URL)';

	    // Add Google+
	    //if ( !isset( $contactmethods['gplus'] ) )
	    //$contactmethods['gplus'] = 'Google+';

	    // Remove Yahoo IM
	    if ( isset( $contactmethods['yim'] ) )
	    unset( $contactmethods['yim'] );

	    // Remove AIM
	    if ( isset( $contactmethods['aim'] ) )
	    unset( $contactmethods['aim'] );

	    // Remove Jabber
	    if ( isset( $contactmethods['jabber'] ) )
	    unset( $contactmethods['jabber'] );

	    return $contactmethods;
	}

	add_filter( 'user_contactmethods', 'largo_contactmethods', 10, 1 );
?>