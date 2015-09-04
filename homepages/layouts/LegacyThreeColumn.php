<?php

include_once dirname( __DIR__ ) . '/homepage-class.php';

class LegacyThreeColumn extends Homepage {
	
	function __construct( $options=array() ) {
		$defaults = array(
			'template' 			=> get_template_directory() . '/homepages/templates/legacy-three-column.php',
			'name' 				=> __( 'Legacy Three Column', 'largo' ),
			'type' 				=> 'legacy-three-column',
			'description' 		=> __( 'This layout has a skinny left sidebar, wide right sidebar and a list of stories in the middle column. This layout allows setting a homepage Top Story.', 'largo' ),
			'rightRail' 		=> true,
			'sidebars' 			=> array(
									__( 'Homepage Left Rail (An optional widget area that, when enabled, appears to the left of the main content area on the homepage)', 'largo' )
								),
			'prominenceTerms' 	=> array(
				array(
					'name' 			=> __( 'Homepage Top Story', 'largo' ),
					'description' 	=> __( 'If you are using a "Big story" homepage layout, add this label to a post to make it the top story on the homepage', 'largo' ),
					'slug' 			=> 'top-story'
				)
			),
			'assets' => array(
				array( 'legacy-three-column', get_template_directory_uri() . '/homepages/assets/css/legacy-three-column.css', array() ),
			),
		);
		$options = array_merge( $defaults, $options );
		parent::__construct( $options );
	}
	
}