<?php

include_once dirname(__DIR__) . '/homepage-class.php';

class HomepageSingle extends Homepage {

	function __construct($options=array()) {
		$defaults = array(
			'template' => get_template_directory() . '/homepages/templates/full-width-image.php',
			'type' => 'single',
			'name' => __('Big story, full-width image', 'largo'),
			'description' => __('A single story with full-width image treatment. Includes a headline and excerpt.', 'largo'),
			'sidebars' => array(
				__('Home Bottom Left', 'largo'), __('Home Bottom Center', 'largo'), __('Home Bottom Right', 'largo')
			),
			'assets' => array(
				array('homepage-single', get_template_directory_uri() . '/homepages/assets/css/single.css', array()),
				array('homepage-single', get_template_directory_uri() . '/homepages/assets/js/single.js', array('jquery'))
			),
			'prominenceTerms' => array(
				array(
					'name' => __('Homepage Top Story', 'largo'),
					'description' => __('If you are using the Newspaper or Carousel optional homepage layout, add this label to a post to make it the top story on the homepage', 'largo'),
					'slug' => 'top-story'
				)
			)
		);
		$options = array_merge($defaults, $options);
		parent::__construct($options);
	}

	public function viewToggle() {
		return homepage_view_toggle();
	}

	public function bigStory() {
		return homepage_big_story_headline();
	}
}
