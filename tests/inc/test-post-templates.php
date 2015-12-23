<?php

class PostTemplatesTestFunctions extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();

	}

	function test_insert_image_no_thumb() {

    	$filename = ( dirname(__FILE__) .'/../mock/img/cat.jpg' );
    	$contents = file_get_contents($filename);

    	$upload = wp_upload_bits(basename($filename), null, $contents);

    	print_r($upload['error']);
    	$this->assertTrue( empty($upload['error']) );

    	$attachment_id = $this->_make_attachment($upload);

    	$attachment_url = wp_get_attachment_image_src($attachment_id,"large");
		$attachment_url = $attachment_url[0];

		$c1 = '<p><img src="'.$attachment_url.'" alt="1559758083_cef4ef63d2_o" width="771" height="475" class="alignnone size-large" /></p>
<h2>Headings</h2>
<p>Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Sed posuere consectetur est at lobortis. Nulla vitae elit libero, a pharetra augue. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Donec sed odio dui.</p>';

		$c1final = '<h2>Headings</h2>
<p>Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Sed posuere consectetur est at lobortis. Nulla vitae elit libero, a pharetra augue. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Donec sed odio dui.</p>';

		$c2 = '<p><img src="'.$attachment_url.'" alt="1559758083_cef4ef63d2_o" width="771" height="475" class="alignnone size-medium" /></p>
<h2>Headings</h2>
<p>Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Sed posuere consectetur est at lobortis. Nulla vitae elit libero, a pharetra augue. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Donec sed odio dui.</p>';

		$c2final = '<p><img src="'.$attachment_url.'" alt="1559758083_cef4ef63d2_o" width="771" height="475" class="alignnone size-medium" /></p>
<h2>Headings</h2>
<p>Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Sed posuere consectetur est at lobortis. Nulla vitae elit libero, a pharetra augue. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Donec sed odio dui.</p>';

		$post_id = $this->factory->post->create();

		add_post_meta($post_id, '_thumbnail_id', $attachment_id);
		$this->go_to( "/?p=$post_id" );

		$final1 = largo_remove_hero($c1);
		$final2 = largo_remove_hero($c2);
		$this->assertEquals($c1final,$final1);
		$this->assertEquals($c2final,$final2);

    }


	function _make_attachment( $upload, $parent_post_id = 0 ) {

    	$type = '';
    	if ( !empty($upload['type']) ) {
    	    $type = $upload['type'];
    	} else {
    	    $mime = wp_check_filetype( $upload['file'] );
    	    if ($mime)
    	        $type = $mime['type'];
    	}
	
    	$attachment = array(
    	    'post_title' => basename( $upload['file'] ),
    	    'post_content' => '',
    	    'post_type' => 'attachment',
    	    'post_parent' => $parent_post_id,
    	    'post_mime_type' => $type,
    	    'guid' => $upload[ 'url' ],
    	);
	
    	// Save the data
    	$id = wp_insert_attachment( $attachment, $upload[ 'file' ], $parent_post_id );
    	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
	
    	return $this->ids[] = $id;

	}

	function test_largo_get_partial_by_post_type() {
		$ret = largo_get_partial_by_post_type('foo', 'bar', 'baz');
		$this->assertEquals($ret, 'foo'); // dummy test so that this test will run. Mostly we're just asserting that the function doesn't cause errors.
		$this->markTestIncomplete();
	}

}
