<?php

// Test functions in inc/related-content.php
class RelatedContentTestFunctions extends wp_UnitTestCase{
	function setUp() {
		parent::setUp();
	}

	function test_largo_get_related_topics_for_category() {
		$this->markTestIncomplete('This test has not been implemented yet.');

		// if a category has no related topics (in cats or tags), it outputs empty string
		// if a category has fewer than 5 related categories, and it has tags, it backfills with tags
		// if it has 5 related categories and any number of tags, it just outputs categories
		// in any case where there are related terms, the output contains apply_filters( 'largo_related_topics_title_ul', __( 'Related Topics:' , 'largo' ) );
	}

	function test__tags_associated_with_category() {
		$this->markTestIncomplete('This test has not been implemented yet.');

	}

	function test__subcategories_for_category() {
		$this->markTestIncomplete('This test has not been implemented yet.');

	}

	function test_largo_get_post_related_topics() {
		$this->markTestIncomplete('This test has not been implemented yet.');

	}

	function test_largo_get_recent_posts_for_term() {
		$this->markTestIncomplete('This test has not been implemented yet.');

	}

	function test_largo_has_categories_or_tags() {
		$this->markTestIncomplete('This test has not been implemented yet.');

	}

	function test_largo_categories_and_tags() {
		$this->markTestIncomplete('This test has not been implemented yet.');

	}

	function test_largo_top_term() {
		$this->markTestIncomplete('This test has not been implemented yet.');

	}

	function test_largo_filter_get_post_related_topics() {
		$this->markTestIncomplete('This test has not been implemented yet.');

	}

	function test_largo_filter_get_recent_posts_for_term_query_args() {
		$this->markTestIncomplete('This test has not been implemented yet.');

	}

}

class LargoRelatedTestFunctions extends WP_UnitTestCase {

	public $cat_id;
	public $cat;
	public $series_id;
	public $series;
	public $considered;

	function setUp() {
		parent::setUp();

		// The category
		$this->cat_id = $this->factory->category->create();
		$this->cat = get_term($this->cat_id, 'category');

		// The series
		$this->series_id = $this->factory->term->create(array(
			'taxonomy' => 'series'
		));
		$this->series = get_term($this->series_id, 'series');

		// The post
		$this->considered = $this->factory->post->create(array(
			'post_date' => '2014-01-11 00:00:00',
			'post_category' => array($this->cat_id),
			'tax_input' => array(
				'series' => $this->series_id
			)
		));
	}

	function test___construct() {
		$this->go_to("/?p=$this->considered");
		// check that Largo_Related->number equals 1 if number is not set
		$lr = new Largo_Related();
		$this->assertInstanceOf('Largo_Related', $lr, 'The constructor failed to make $lr an instance of Largo_Related');
		$this->assertEquals(1, $lr->number, "The number of posts to be returned, when not explicitly set, did not match the programmed number of 1 posts");

		$lr = new Largo_Related(4);
		$this->assertEquals(4, $lr->number, "The number of posts to be returned, when set to 4, was not 4");
		// check that Largo_Related->post_id is the value of the set post, or the value of the global post
		global $post;
		$this->assertEquals($post->ID, $lr->post_id, "The global post's ID does not match the ID of the post considered by the Largo_Related class");

		$this->go_to('/'); // So there is no global $post
		$lr = new Largo_Related(4, $this->considered);
		$this->assertEquals($this->considered, $lr->post_id, "The post this test is considering does not match the post in the Largo_Related class, when that post ID is explicitly set.");
	}

	function test_popularity_sort() {
		// create some objects that look kinda like term objects if you look at them from the right angle
		$a_term = (object) array('count' => 4);
		$b_term = (object) array('count' => 4);
		$c_term = (object) array('count' => 3);

		$lr = new Largo_Related(1, $this->considered);

		$this->assertEquals(0, $lr->popularity_sort($a_term, $b_term), 'Comparing two terms with equal numbers of posts did not return 0, which would indicate they were of the same length');
		$this->assertEquals(-1, $lr->popularity_sort($c_term, $b_term), 'Comparing a term with 3 posts to a term with 4 posts did not return -1, which would indicate that 3 had fewer posts than 4 did. If the difference were greater than 1 post, -1 would still be returned.');
		$this->assertEquals(1, $lr->popularity_sort($a_term, $c_term), 'Comparing a term with 4 posts to a term with 3 posts did not return 1, which would indicate that 4 had more posts than 3 did. If the difference were greater than 1 post, 1 would still be returned.');
	}

	/**
	 * Test the function with a lot of different conditions
	 *
	 * - Series without organization
	 *		- one post published after the current post
	 *		- one post published before the current post
	 * - Series with CFTL post with organization information
	 *		- ASC
	 *		- series_custom
	 *		- DESC
	 *		- featured, DESC
	 *		- featured, ASC
	 * - No series, but category
	 *		- one post published after the current post
	 *		- one post published before the current post
	 * - No series, but tag
	 * - Tags and Categories
	 * - No series or category or tag
	 */

	/**
	 * Test that an unorganized series returns a post published before the considered post, but no others.
	 */
	function test_unorganized_series_before() {
		of_set_option('series_enabled', 1);

		// Some randos before and after
		$this->factory->post->create(array(
			'post_date' => '2013-01-01 00:00:00',
		));
		$this->factory->post->create(array(
			'post_date' => '2015-01-01 00:00:00',
		));

		// Post published before the current post in its series
		$before_id = $this->factory->post->create(array(
			'post_date' => '2013-01-01 00:00:00',
			'tax_input' => array(
				'series' => $this->series_id
			)
		));
		$lr = new Largo_Related(1, $this->considered);
		$ids = $lr->ids();
		$this->assertEquals(1, count($ids), "Largo_Related returned more than one post");
		$this->assertEquals($before_id, $ids[0], "Largo_Related did not return the post it was expected to ");
	}

	/**
	 * Test that an unorganized series returns a post published after the considered post, but no others.
	 */
	function test_unorganized_series_after() {
		of_set_option('series_enabled', 1);

		// Some randos before and after
		$this->factory->post->create(array(
			'post_date' => '2013-01-01 00:00:00',
		));
		$this->factory->post->create(array(
			'post_date' => '2015-01-01 00:00:00',
		));

		// Post published after the current post in its series
		$before_id = $this->factory->post->create(array(
			'post_date' => '2015-01-01 00:00:00',
			'tax_input' => array(
				'series' => $this->series_id
			)
		));
		$lr = new Largo_Related(1, $this->considered);
		$ids = $lr->ids();
		$this->assertEquals(1, count($ids), "Largo_Related returned more than one post");
		$this->assertEquals($before_id, $ids[0], "Largo_Related did not return the post it was expected to ");
	}

	function test_series_asc() {
		of_set_option('series_enabled', 1);

		// Some randos before and after
		$this->factory->post->create(array(
			'post_date' => '2013-01-01 00:00:00',
		));
		$this->factory->post->create(array(
			'post_date' => '2015-01-01 00:00:00',
		));
		// Post published after the current post in its series
		$before_id = $this->factory->post->create(array(
			'post_date' => '2015-01-01 00:00:00',
			'tax_input' => array(
				'series' => $this->series_id
			)
		));
		// Post published before the current post in its series
		$after_id = $this->factory->post->create(array(
			'post_date' => '2013-01-01 00:00:00',
			'tax_input' => array(
				'series' => $this->series_id
			)
		));

		// Create a landing page that sets the order to ASC
		$landing = $this->factory->post->create(array(
			'post_type' => 'cftl-tax-landing',
			'tax_input' => array(
				'series' => $this->series->slug,
			),
		));
		update_post_meta($landing, 'has_order', 'ASC');

		$lr = new Largo_Related(2, $this->considered);
		$ids = $lr->ids();
		$this->assertEquals(2, count($ids), "Largo_Related returned other than 2 posts");
		$this->assertGreaterThan($ids[0], $ids[1], "The second post should be higher in post ID than the first");
	}

	function test_series_desc() {
		of_set_option('series_enabled', 1);

		// Some randos before and after
		$this->factory->post->create(array(
			'post_date' => '2013-01-01 00:00:00',
		));
		$this->factory->post->create(array(
			'post_date' => '2015-01-01 00:00:00',
		));
		// Post published before the current post in its series
		$before_id = $this->factory->post->create(array(
			'post_date' => '2013-01-01 00:00:00',
			'tax_input' => array(
				'series' => $this->series_id
			)
		));
		// Post published after the current post in its series
		$after_id = $this->factory->post->create(array(
			'post_date' => '2015-01-01 00:00:00',
			'tax_input' => array(
				'series' => $this->series_id
			)
		));

		// Create a landing page that sets the order to DESC
		$landing = $this->factory->post->create(array(
			'post_type' => 'cftl-tax-landing',
			'tax_input' => array(
				'series' => $this->series->slug,
			),
		));
		update_post_meta($landing, 'has_order', 'DESC');

		$lr = new Largo_Related(2, $this->considered);
		$ids = $lr->ids();
		$this->assertEquals(2, count($ids), "Largo_Related returned other than 2 posts");
		$this->assertEquals($before_id, $ids[1], "The first post should be the younger post");
		$this->assertEquals($after_id, $ids[0], "The first post should be the older post");
	}

	function test_series_series_custom() {
		of_set_option('series_enabled', 1);

		// Some randos before and after
		$this->factory->post->create(array(
			'post_date' => '2013-01-01 00:00:00',
		));
		$this->factory->post->create(array(
			'post_date' => '2015-01-01 00:00:00',
		));
		// Post published before the current post in its series
		$before_id = $this->factory->post->create(array(
			'post_date' => '2013-01-01 00:00:00',
			'tax_input' => array(
				'series' => $this->series_id
			)
		));
		// Post published after the current post in its series
		$after_id = $this->factory->post->create(array(
			'post_date' => '2015-01-01 00:00:00',
			'tax_input' => array(
				'series' => $this->series_id
			)
		));
		// Post published waaaay in the past
		$past_id = $this->factory->post->create(array(
			'post_date' => '1013-01-01 00:00:00',
			'tax_input' => array(
				'series' => $this->series_id
			)
		));

		// Create a landing page that sets the order to 'custom', setting the sort order to 'series_custom'
		$landing = $this->factory->post->create(array(
			'post_type' => 'cftl-tax-landing',
			'tax_input' => array(
				'series' => $this->series->slug,
			),
		));
		update_post_meta($landing, 'has_order', 'custom');

		/*
		 * The order of posts within a series, if set customly, is done with each post's series_order post meta.
		 * It's not set on the series landing page.
		 */
		add_post_meta($after_id, 'series_order', 1);
		add_post_meta($past_id, 'series_order', 2);
		add_post_meta($before_id, 'series_order', 3);

		$lr = new Largo_Related(3, $this->considered);
		$ids = $lr->ids();
		$this->assertEquals(3, count($ids), "Largo_Related returned other than 3 posts");
		$this->assertFalse(array($past_id, $before_id, $after_id) == $ids, "The posts were returned in increasing order of newness");
		$this->assertFalse(array($after_id, $before_id, $past_id) == $ids, "The posts were returned in decreasing order of newness");
		$this->assertFalse(array($before_id, $after_id, $past_id) == $ids, "The posts were returned in increasing order of post ID");
		$this->assertFalse(array($past_id, $after_id, $before_id) == $ids, "The posts were returned in decreasing order of post ID");
		$this->assertFalse(array($before_id, $past_id, $after_id) == $ids, "The posts were returned in the opposite of the custom order.");
		$this->assertTrue(array($after_id, $past_id, $before_id) == $ids, "The posts were not returned in the custom order.");
	}

	function test_series_featured_desc() {
		$this->markTestIncomplete('This test has not been implemented yet.');
		of_set_option('series_enabled', 1);
		// Create a landing page that sets the order to 'featured, DESC'
		$landing = $this->factory->post->create(array(
			'post_type' => 'cftl-tax-landing',
			'tax_input' => array(
				'series' => $this->series->slug,
			),
		));
		update_post_meta($landing, 'has_order', 'featured, DESC');
		// Post published before the current post in its series
		$before_id = $this->factory->post->create(array(
			'post_date' => '2013-01-01 00:00:00',
			'tax_input' => array(
				'series' => $this->series_id
			)
		));
		// Post published after the current post in its series
		$after_id = $this->factory->post->create(array(
			'post_date' => '2015-01-01 00:00:00',
			'tax_input' => array(
				'series' => $this->series_id
			)
		));
		// create a post that is featured in this taxonomy. It shall be the first.
		$feat = $this->factory->post->create(array(
			'series' => $this->series->slug,
			'tax_input' => array(
				'prominence' => 'taxonomy-featured'
			)
		));
		$lr = new Largo_Related(3, $this->considered);
		$ids = $lr->ids();
		$this->assertEquals(3, count($ids), "Largo_Related returned other than 2 posts");
		$this->assertFalse($feat == $ids[2], "The featured post is the last post in the return.");
		$this->assertEquals($feat, $ids[0], "The featured post is not the first post in the return.");
	}

	function test_series_featured_asc() {
		$this->markTestIncomplete('This test has not been implemented yet.');
		of_set_option('series_enabled', 1);
		// Create a landing page that sets the order to 'featured, ASC'
		$landing = $this->factory->post->create(array(
			'post_type' => 'cftl-tax-landing',
			'tax_input' => array(
				'series' => $this->series->slug,
			),
		));
		update_post_meta($landing, 'has_order', 'featured, ASC');
		// Post published before the current post in its series
		$before_id = $this->factory->post->create(array(
			'post_date' => '2013-01-01 00:00:00',
			'tax_input' => array(
				'series' => $this->series_id
			)
		));
		// Post published after the current post in its series
		$after_id = $this->factory->post->create(array(
			'post_date' => '2015-01-01 00:00:00',
			'tax_input' => array(
				'series' => $this->series_id
			)
		));
		// create a post that is featured in this taxonomy. It shall be the first.
		$tf = $this->factory->term->create(array(
			'taxonomy' => 'prominence',
		));
		wp_update_term($tf, 'prominence',
			array(
				'slug' => 'taxonomy-featured'
			)
		);
		$feat = $this->factory->post->create(array(
			'series' => $this->series->slug,
			'tax_input' => array(
				'prominence' => 'taxonomy-featured'
			)
		));
		$lr = new Largo_Related(3, $this->considered);
		$ids = $lr->ids();
		$this->assertEquals(3, count($ids), "Largo_Related returned other than 3 posts");
		$this->assertFalse($feat == $ids[2], "The featured post is the last post in the return.");
		$this->assertEquals($feat, $ids[0], "The featured post is not the first post in the return.");
	}

	/**
	 * @todo This test checks for a post before and a post after the considered post
	 */
	function test_category() {
		of_set_option('series_enabled', false);
		// Some randos before and after
		$this->factory->post->create(array(
			'post_date' => '2013-01-01 00:00:00',
		));
		$this->factory->post->create(array(
			'post_date' => '2015-01-01 00:00:00',
		));
		$cp = $this->factory->post->create(array(
			'post_category' => array($this->cat_id),
		));
		$lr = new Largo_Related(1, $this->considered);
		$ids = $lr->ids();
		$this->assertEquals(1, count($ids), "Largo_Related returned other than 1 posts");
		$this->assertEquals($cp, $ids[0], "Largo_Related did not return the post in the category");
	}

	function test_tags() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @todo This test should ask for 2 posts, and return one post each from a category and a tag
	 */
	function test_category_and_tag() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @todo This test should check that recent posts are returned when there are no posts in the considered post's series, category, or tags
	 */
	function test_recent_posts() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
