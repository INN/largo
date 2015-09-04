<?php

include_once dirname( __DIR__ ) . '/homepage-class.php';

class TopStories extends Homepage {

	function __construct($options=array()) {
		$defaults = array(
			'template' 			=> get_template_directory() . '/homepages/templates/top-stories.php',
			'assets' 			=> array(
										array( 'homepage-slider', get_template_directory_uri() . '/homepages/assets/css/top-stories.css', array() )
									),
			'name' 				=> __( 'Top Stories', 'largo' ),
			'type' 				=> 'top-stories',
			'description' 		=> __( 'A newspaper-like layout highlighting one Top Story on the left and others to the right.', 'largo' ),
			'rightRail' 		=> true,
			'prominenceTerms' 	=> array(
				array(
					'name' 			=> __( 'Homepage Top Story', 'largo' ),
					'description' 	=> __( 'If you are using the Newspaper or Carousel optional homepage layout, add this label to a post to make it the top story on the homepage', 'largo' ),
					'slug' 			=> 'top-story'
				),
				array(
					'name' 			=> __( 'Homepage Featured', 'largo' ),
					'description' 	=> __( 'If you are using the Newspaper or Carousel optional homepage layout, add this label to posts to display them in the featured area on the homepage.', 'largo' ),
					'slug' 			=> 'homepage-featured'
				)
			)
		);
		$options = array_merge( $defaults, $options );
		parent::__construct( $options );
	}

}