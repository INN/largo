<?php

include_once dirname( __DIR__ ) . '/homepage-class.php';

class HomepageBlog extends Homepage {

	function __construct( $options=array() ) {
		$defaults = array(
			'name' 			=> __( 'Blog', 'largo' ),
			'description' 	=> __( 'A blog-like list of posts with the ability to stick a post to the top of the homepage. Be sure to set Homepage Bottom to the single column view.', 'largo' ),
			'rightRail' 	=> true,
			'template' 		=> get_template_directory() . '/homepages/templates/blog.php'
		);
		$options = array_merge( $defaults, $options );
		parent::__construct( $options );
	}
	
}