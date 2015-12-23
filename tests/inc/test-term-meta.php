<?php

/**
 * Tests for inc/term-meta.php
 */

class TermMetaTestFunctions extends WP_UnitTestCase {
	function setUp() {
		parent::setUp();
		$this->post_id = $this->factory->post->create(array());

		// the category
		$this->cat_id = $this->factory->category->create();
		$this->cat = get_term($this->cat_id, 'category');

		// The series
		$this->series_id = $this->factory->term->create(array(
			'taxonomy' => 'series'
		));
		$this->series = get_term($this->series_id, 'series');
	}

	/*
	 * Regression test for behavior introduced in a1cd519e0543f4a37ef1c6f39955e077701d96f9
	 * https://github.com/INN/Largo/commit/a1cd519e0543f4a37ef1c6f39955e077701d96f9#commitcomment-14906257
	 */
	function test_largo_term_featured_media_types() {
		$testarray = array(
			'image' => array(
				'title' => 'Featured image',
				'id' => 'image',
			),
			'test' => array(
				'title' => 'bar',
				'id' => 'title',
			),
		);
		global $post;
		$backup = $post;

		// Test this on a normal post: Both image and test should come out.
		$this->go_to('/?p='.$this->post_id);
		$ret = largo_term_featured_media_types($testarray);
		$this->assertTrue(array_key_exists('image', $ret), "post: The function removed the 'image' key from the array. This should never happen.");
		$this->assertTrue(array_key_exists('test', $ret), "post: The function removed the 'test' key from the array. This should not happen when the post type is 'post'");
		unset($ret);

		// Test this with a post type of "_term_meta" which is found when editing a taxonomy term.
		$post->post_type = '_term_meta';
		$ret = largo_term_featured_media_types($testarray);
		$this->assertTrue(array_key_exists('image', $ret), "_term_meta: The function removed the 'image' key from the array. This should never happen.");
		$this->assertFalse(array_key_exists('test', $ret), "_term_meta: The function failed to remove the 'test' key from the array. This shouldn't happen when the post type is '_term_meta'.");
		unset($ret);

		// Test this against the cftl tax landing page
		$post->post_type = 'cftl-tax-landing';
		$ret = largo_term_featured_media_types($testarray);
		$this->assertTrue(array_key_exists('image', $ret), "flarp: The function removed the 'image' key from the array. This should never happen.");
		$this->assertTrue(array_key_exists('test', $ret), "flarp: The function removed the 'test' key from the array. This shouldn't happen when the post type is other than 'cftl-tax-landing'.");
		unset($ret);

		// Test this against a strange post type.
		$post->post_type = 'flarp';
		$ret = largo_term_featured_media_types($testarray);
		$this->assertTrue(array_key_exists('image', $ret), "flarp: The function removed the 'image' key from the array. This should never happen.");
		$this->assertTrue(array_key_exists('test', $ret), "flarp: The function removed the 'test' key from the array. This shouldn't happen when the post type is other than '_term_meta'.");
		unset($ret);

		// Test this when the global post is strange.
		$post = null;
		$ret = largo_term_featured_media_types($testarray);
		$this->assertTrue(array_key_exists('image', $ret), "null: The function removed the 'image' key from the array. This should never happen.");
		$this->assertTrue(array_key_exists('test', $ret), "null: The function removed the 'test' key from the array. This shouldn't happen when the global post isn't an object (for whatever reason.)");
		unset($ret);

		// reset;
		$post = $backup;
	}
}
