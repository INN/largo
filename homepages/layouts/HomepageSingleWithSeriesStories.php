<?php

include_once __DIR__ . '/HomepageSingle.php';

class HomepageSingleWithSeriesStories extends HomepageSingle {

	function __construct($options=array()) {
		$defaults = array(
			'name' => __('One big story and list of stories from the same series', 'largo'),
			'type' => 'series',
			'description' => __('A single story with full-width image treatment. Series stories appear to the right of the big story\'s headline and excerpt.', 'largo'),
			'sidebars' => array(
				__('Home Bottom Left', 'largo'), __('Home Bottom Center', 'largo'), __('Home Bottom Right', 'largo')
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

	public function moreStories() {
		return homepage_series_stories_list();
	}
}
