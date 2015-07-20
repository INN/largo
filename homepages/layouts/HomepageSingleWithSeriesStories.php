<?php

include_once __DIR__ . '/HomepageSingle.php';

/**
 * Homepage layout that provides one BIG featured story and several other stories
 * from the same Series.
 */
class HomepageSingleWithSeriesStories extends HomepageSingle {

	/**
	 * If the Series taxonomy is not enabled, this class should unregister itself.
	 *
	 * The default homepage layouts are listed in homepages/homepage.php.
	 * For each default layout, register_homepage_layout is called on that layout class.
	 * register_homepage_layout calls the HomepageLayoutFactory method register on that class instance
	 * the register method creates a new instance of that class
	 * therefore the __construct method below checks largo_is_series_enabled 
	 * and adds an auto-removal function to the init hook of series are not enabled.
	 */
	function unregister_HomepageSingleWithSeriesStories() {
		global $largo_homepage_factory;
		unset($largo_homepage_factory->layouts[get_class($this)]);
	}

	function __construct($options=array()) {
		if ( !largo_is_series_enabled() && !$this->isActiveHomepageLayout() )
			add_action('init', array($this, 'unregister_HomepageSingleWithSeriesStories'), 105);

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
					'description' => __('If you are using a "Big story" homepage layout, add this label to a post to make it the top story on the homepage', 'largo'),
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
