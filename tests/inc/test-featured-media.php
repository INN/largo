<?php

class FeaturedMediaTestFunctions extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();

		$this->post = $this->factory->post->create();
		$this->attachment = $this->factory->post->create(array('post_type' => 'attachment'));
		$this->gallery = $this->factory->post->create_many(5, array('post_type' => 'attachment'));

		$this->media_types = array(
			'image' => array(
				'id' => $this->post,
				'attachment' => $this->attachment,
				'type' => 'image'
			),
			'gallery' => array(
				'id' => $this->post,
				'gallery' => $this->gallery,
				'type' => 'gallery'
			),
			'video' => array(
				'id' => $this->post,
				'caption' => 'Lipsum video caption',
				'title' => 'Lipsum vidoe title',
				'credit' => 'Lipsum video credit',
				'url' => 'http://test.com/path/',
				'embed' => '<iframe id="test-iframe-embed-code"></iframe>',
				'type' => 'video'
			),
			'embed' => array(
				'id' => $this->post,
				'caption' => 'Lipsum embed caption',
				'title' => 'Lipsum embed title',
				'credit' => 'Lipsum embed credit',
				'url' => 'http://test.com/path/',
				'embed' => '<iframe id="test-iframe-embed-code"></iframe>',
				'type' => 'embed-code'
			)
		);
	}

	function test_largo_get_featured_media() {
		update_post_meta($this->post, 'featured_media', $this->media_types['image']);
		$featured_media = largo_get_featured_media($this->post);
		$this->assertEquals($featured_media, $this->media_types['image']);
	}

	function test_largo_hero() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_get_hero() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_featured_image_hero() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_get_featured_image_hero() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_featured_embed_hero() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_get_featured_embed_hero() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_featured_gallery_hero() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_get_featured_gallery_hero() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	function test_largo_has_featured_media() {
		// If the post doesn't have featured media set, `largo_has_featured_media` should return false
		$has_featured_media = largo_has_featured_media($this->post);
		$this->assertTrue(!$has_featured_media);

		// If the post does have featured media set, `largo_has_featured_media` should return true
		update_post_meta($this->post, 'featured_media', $this->media_types['image']);
		$has_featured_media = largo_has_featured_media($this->post);
		$this->assertTrue($has_featured_media);
	}

	function test_largo_default_featured_media_types() {
		$media_types = largo_default_featured_media_types();
		$this->assertEquals(count($media_types), count($this->media_types));
	}

	function test_largo_enqueue_featured_media_js() {
		global $wp_scripts, $post;

		$post = get_post($this->post);
		setup_postdata($post);

		$hook = 'post.php';
		largo_enqueue_featured_media_js($hook);

		$this->assertTrue(!empty($wp_scripts->registered['largo_featured_media']));
	}

	function test_largo_add_featured_media_button() {
		global $post;
		$post = get_post($this->post);
		setup_postdata($post);

		$ret = largo_add_featured_media_button('TEST');

		// `largo_add_featured_media_button` should concatenate featured media button markup
		// and the $context string passed to it
		$this->assertTrue((bool) preg_match('/^TEST/', $ret));

		// Make sure the button ID attribute is present and matches our expectation
		$this->assertTrue((bool) preg_match('/id\="set\-featured\-media\-button"/', $ret));
	}

	function test_largo_featured_media_templates() {
		// Since this function prints A LOT of stuff, let's just make sure it exists
		$this->assertTrue(function_exists('largo_featured_media_templates'));
	}

	function test_largo_remove_featured_image_meta_box() {
		largo_remove_featured_image_meta_box();

		global $wp_meta_boxes;
		$contexts = array('normal', 'side', 'advanced');
		$screen = convert_to_screen('post');
		$page = $screen->id;
		$id = 'postimagediv';

		foreach ($contexts as $context) {
			foreach (array('high', 'core', 'default', 'low') as $priority)
				$this->assertTrue($wp_meta_boxes[$page][$context][$priority][$id] == false);
		}
	}

	function test_largo_featured_media_post_classes() {
		global $post;
		$tmp_post = $post;
		$post = get_post($this->post);
		update_post_meta($this->post, 'featured_media', $this->media_types['image']);
		$ret = largo_featured_media_post_classes(array());
		$this->assertTrue(in_array('featured-media', $ret));
		$this->assertTrue(in_array('featured-media-image', $ret));
		$post = $tmp_post;
	}
}

class FeaturedMediaTestAjaxFunctions extends WP_Ajax_UnitTestCase {

	function setUp() {
		parent::setUp();

		$this->post = $this->factory->post->create();
		$this->attachment = $this->factory->post->create(array('post_type' => 'attachment'));
		$this->gallery = $this->factory->post->create_many(5, array('post_type' => 'attachment'));

		$this->media_types = array(
			'image' => array(
				'id' => $this->post,
				'attachment' => $this->attachment,
				'type' => 'image'
			)
		);
	}

	function test_largo_featured_media_read() {
		// Set post `featured_media` meta
		update_post_meta($this->post, 'featured_media', $this->media_types['image']);

		// Ask for the post `featured_media` meta
		$_POST['data'] = json_encode(array('id' => $this->post));

		try {
			$this->_handleAjax("largo_featured_media_read");
		} catch (WPAjaxDieContinueException $e) {
			// The response should be equal to the $_POST data we sent
			$this->assertEquals(json_encode($this->media_types['image']), $this->_last_response);
		}
	}

	function test_largo_featured_media_save() {
		$_POST['data'] = json_encode($this->media_types['image']);

		try {
			$this->_handleAjax("largo_featured_media_save");
		} catch (WPAjaxDieContinueException $e) {
			// The response should be equal to the $_POST data we sent
			$data = json_decode($_POST['data']);
			$response = json_decode($this->_last_response);
			$this->assertEquals($data->id, $response->id);
		}
	}

	function test_largo_save_featured_image_display() {
		$_POST['data'] = json_encode(array(
			'id' => $this->post,
			'featured-image-display' => 'on'
		));

		try {
			$this->_handleAjax("largo_save_featured_image_display");
		} catch (WPAjaxDieContinueException $e) {
			// The response should be equal to the $_POST data we sent
			$this->assertEquals($_POST['data'], $this->_last_response);
		}
	}

	function test_largo_fetch_video_oembed() {
		// The heavy lifting is done by `wp_oembed_get` which is part of WordPress core,
		// so let's just make sure this function exists.
		$this->assertTrue(function_exists('largo_fetch_video_oembed'));
	}

}
