<?php

/**
 * Returns the default available featured media types
 */
function largo_default_featured_media_types() {

	$media_types = apply_filters('largo_default_featured_media_types', array(
		'embed' => array(
			'title' => 'Featured embed code',
			'id' => 'embed-code'
		),
		'video' => array(
			'title' => 'Featured video',
			'id' => 'video'
		),
		'image' => array(
			'title' => 'Featured image',
			'id' => 'image'
		),
		'gallery' => array(
			'title' => 'Featured photo gallery',
			'id' => 'gallery'
		)
	));

	return array_values($media_types);

}

/**
 * Prints DOM for hero image.
 *
 * Determines the type of featured media attached to a post,
 * and generates the DOM for that type of media.
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global $post.
 * @param String $classes Optional. Class string to apply to outer div.hero
 */
function largo_hero( $post = null, $classes = '' ) {

	echo largo_get_hero( $post, $classes );

}

/**
 * Return DOM for hero image.
 *
 * Determines the type of featured media attached to a post,
 * and generates the DOM for that type of media.
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global $post.
 * @param String $classes Optional. Class string to apply to outer div.hero
 */
function largo_get_hero($post = null,$classes = '') {

	$post = get_post($post);
	$hero_class = largo_hero_class($post->ID, false);

	$ret = '';

	$values = get_post_custom($post->ID);

	// If the box is checked to override the featured image display, obey it.
	// EXCEPT if a youtube_url is added in the old way for the post. This is to respect
	// behavior before v0.4,
	if (isset($values['featured-image-display'][0]) && !isset($values['youtube_url']))
		return $ret;

	if (largo_has_featured_media($post->ID) && $hero_class !== 'is-empty') {

		$featured_media = largo_get_featured_media($post->ID);

		if (in_array($featured_media['type'], array('embed-code', 'video'))) {

			$ret = largo_get_featured_embed_hero($post->ID,$classes);

		} else if ($featured_media['type'] == 'image') {

			$ret = largo_get_featured_image_hero($post->ID,$classes);

		} else if ($featured_media['type'] == 'gallery') {

			$ret = largo_get_featured_gallery_hero($post->ID,$classes);

		}

	}

	/**
	 * Filter the hero's DOM
	 *
	 * @since 0.5.1
	 *
	 * @param String $var    DOM for hero.
	 * @param WP_Post $post  post object.
	 */
	$ret = apply_filters('largo_get_hero',$ret,$post,$classes);


	return $ret;

}

/**
 * Prints DOM for a featured image hero.
 *
 * @since 0.5.1
 * @see largo_get_featured_image_hero()
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global $post.
 * @param String $classes Optional. Class string to apply to outer div.hero
 */
function largo_featured_image_hero($post = null, $classes = '') {

	echo largo_get_featured_image_hero($post,$classes);

}

/**
 * Returns DOM for a featured image hero.
 *
 * @since 0.5.1
 * @see largo_get_featured_image_hero()
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global $post.
 * @param String $classes Optional. Class string to apply to outer div.hero
 */
function largo_get_featured_image_hero($post = null, $classes = '') {

	$the_post = get_post($post);
	$featured_media = largo_get_featured_media($the_post->ID);

	if( $featured_media['type'] != 'image' ) {
		return;
	}

	$hero_class = largo_hero_class($the_post->ID, false);
	$classes = "hero $hero_class $classes";

	$thumb_meta = null;
	if ($thumb_id = get_post_thumbnail_id($the_post->ID)) {
		$thumb_content = get_post($thumb_id);
		$thumb_custom = get_post_custom($thumb_id);

		$thumb_meta = array(
			'caption' => (!empty($thumb_content->post_excerpt))? $thumb_content->post_excerpt : null,
			'credit' => (!empty($thumb_custom['_media_credit'][0]))? $thumb_custom['_media_credit'][0] : null,
			'credit_url' => (!empty($thumb_custom['_media_credit_url'][0]))? $thumb_custom['_media_credit_url'][0] : null,
			'organization' => (!empty($thumb_custom['_navis_media_credit_org'][0]))? $thumb_custom['_navis_media_credit_org'][0] : null
		);
	}

	$context = array(
		'classes' => $classes,
		'thumb_meta' => $thumb_meta,
		'the_post' => $the_post
	);

	ob_start();
	largo_render_template('partials/hero', 'featured-image', $context);
	$ret = ob_get_clean();

	return $ret;
}


/**
 * Prints DOM for an embed code hero.
 *
 * @since 0.5.1
 * @see largo_get_featured_embed_hero()
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global $post.
 * @param String $classes Optional. Class string to apply to outer div.hero
 */
function largo_featured_embed_hero($post = null, $classes = '') {

	echo largo_get_featured_embed_hero($post,$classes);

}

/**
 * Returns DOM for an embed code hero.
 *
 * @since 0.5.1
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global $post.
 * @param String $classes Optional. Class string to apply to outer div.hero
 */
function largo_get_featured_embed_hero($post = null, $classes = '') {

	$the_post = get_post($post);
	$featured_media = largo_get_featured_media($the_post->ID);

	if( !in_array($featured_media['type'], array('embed-code', 'video')) ) {
		return;
	}

	$hero_class = largo_hero_class($the_post->ID, false);
	$classes = "hero $hero_class $classes";

	$context = array(
		'classes' => $classes,
		'featured_media' => $featured_media,
		'the_post' => $the_post
	);

	ob_start();
	largo_render_template('partials/hero', 'featured-embed', $context);
	$ret = ob_get_clean();

	return $ret;
}

/**
 * Prints DOM for a featured gallery hero.
 *
 * @since 0.5.1
 * @see largo_get_featured_gallery_hero()
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global $post.
 * @param String $classes Optional. Class string to apply to outer div.hero
 */
function largo_featured_gallery_hero($post = null, $classes = '') {

	echo largo_get_featured_gallery_hero($post, $classes);

}

/**
 * Returns DOM for a featured gallery hero.
 *
 * @since 0.5.1
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global $post.
 * @param String $classes Optional. Class string to apply to outer div.hero
 */
function largo_get_featured_gallery_hero( $post = null, $classes = '' ) {

	$the_post = get_post($post);
	$featured_media = largo_get_featured_media($the_post->ID);

	if( $featured_media['type'] != 'gallery' ) {
		return;
	}

	$hero_class = largo_hero_class($the_post->ID, false);
	$classes = "hero $hero_class $classes";

	$context = array(
		'classes' => $classes,
		'gallery_ids' => implode(',', $featured_media['gallery']),
		'the_post' => $the_post
	);

	ob_start();
	largo_render_template('partials/hero', 'featured-gallery', $context);
	$ret = ob_get_clean();

	return $ret;

}

/**
 * Returns information about the featured media.
 *
 * @since 0.4
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global $post.
 * @return array $post_type {
 *
 * 			'id' => int, 		// post id.
 * 			'type' => string, 	// the type of featured_media
 *
 * 			// ... other variables, dependent on what the type is.
 *
 * 		}
 */
function largo_get_featured_media( $post = null ) {

	$post = get_post($post);

	$ret = get_post_meta($post->ID, 'featured_media', true);

	// Check if the post has a thumbnail/featured image set.
	// If yes, send that back as the featured media.
	$post_thumbnail = get_post_thumbnail_id($post->ID);
	if (empty($ret) && !empty($post_thumbnail)) {
		$ret = array(
			'id' => $post->ID,
			'attachment' => $post_thumbnail,
			'type' => 'image'
		);
	} else if (!empty($ret) && in_array($ret['type'], array('embed', 'video')) && !empty($post_thumbnail)) {
		$attachment = wp_prepare_attachment_for_js($post_thumbnail);
		$ret = array_merge($ret, array('attachment_data' => $attachment));
	}

	// Backwards compatibility with posts that have a youtube_url set
	$youtube_url = get_post_meta($post->ID, 'youtube_url', true);
	if (empty($ret) && !empty($youtube_url)) {
		$ret = array(
			'id' => $post->ID,
			'url' => $youtube_url,
			'embed' => wp_oembed_get($youtube_url),
			'type' => 'video'
		);
	}

	return $ret;
}

/**
 * Does the post have featured media?
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global $post.
 * @return bool If a post ID has featured media or not.
 */
function largo_has_featured_media( $post = null ) {

	$post = get_post($post);
	$id = isset( $post->ID ) ? $post->ID : 0;

	$featured_media = largo_get_featured_media($id);

	return !empty($featured_media);
}

/**
 * Functions that modify the dashboard/load the featured media functionality
 */

/**
 * Enqueue the featured media javascript
 *
 * @global $post
 * @global LARGO_DEBUG
 * @param array $hook The page that this function is being run on.
 */
function largo_enqueue_featured_media_js($hook) {
	if (!in_array($hook, array('edit.php', 'edit-tags.php', 'post-new.php', 'post.php')))
		return;

	global $post, $wp_query;

	if ( in_array($hook, array('edit-tags.php')) && isset($_GET['action']) ) {
		$post = get_post( largo_get_term_meta_post( $_GET['taxonomy'], $_GET['tag_ID'] ) );
	}

	$featured_image_display = get_post_meta($post->ID, 'featured-image-display', true);

	$suffix = (LARGO_DEBUG)? '' : '.min';
	wp_enqueue_script(
		'largo_featured_media', get_template_directory_uri() . '/js/featured-media' . $suffix . '.js',
		array('media-models', 'media-views'), false, 1);

	wp_enqueue_style(
		'largo_featured_media',
		get_template_directory_uri(). '/css/featured-media' . $suffix . '.css');

	wp_localize_script('largo_featured_media', 'LFM', array(
		'options' => largo_default_featured_media_types(),
		'featured_image_display' => !empty($featured_image_display),
		'has_featured_media' => (bool) largo_has_featured_media($post->ID)
	));
}
add_action('admin_enqueue_scripts', 'largo_enqueue_featured_media_js');

/**
 * Adds the "Set Featured Media" button above the post editor
 */
function largo_add_featured_media_button($context) {
	global $post;
	$has_featured_media = largo_has_featured_media($post->ID);
	$language = (!empty($has_featured_media))? 'Edit' : 'Set';
	ob_start();
?>
	<a href="#" id="set-featured-media-button" class="button set-featured-media add_media" data-editor="content" title="<?php echo $language; ?> Featured Media"><span class="dashicons dashicons-admin-generic"></span> <?php echo $language; ?> Featured Media</a> <span class="spinner" style="display: none;"></span>
<?php
	$context .= ob_get_contents();
	ob_end_clean();
	return $context;
}
add_action('media_buttons_context',  'largo_add_featured_media_button');

/**
 * Prints the templates used by featured media modal.
 */
function largo_featured_media_templates() { ?>
	<script type="text/template" id="tmpl-featured-embed-code">
		<form id="featured-embed-code-form">
			<input type="hidden" name="type" value="embed-code" />

			<# var model = data.controller.model #>
			<div>
				<label for="title"><span>Title</span></label>
				<input type="text" name="title" <# if (model.get('type') == 'embed-code') { #>value="{{ model.get('title') }}"<# } #> />
			</div>

			<div>
				<label for="caption"><span>Caption</span></label>
				<input type="text" name="caption" <# if (model.get('type') == 'embed-code') { #>value="{{ model.get('caption') }}"<# } #> />
			</div>

			<div>
				<label for="credit"><span>Credit</span></label>
				<input type="text" name="credit" <# if (model.get('type') == 'embed-code') { #>value="{{ model.get('credit') }}"<# } #> />
			</div>

			<div>
				<label for="url"><span>URL</span></label>
				<input type="text" name="url" <# if (model.get('type') == 'embed-code') { #>value="{{ model.get('url') }}"<# } #> />
			</div>

			<div>
				<label for="embed"><span>Embed code</span></label>
				<textarea name="embed"><# if (model.get('type') == 'embed-code') { #>{{ model.get('embed') }}<# } #></textarea>
			</div>

			<div>
				<label><span>Embed thumbnail</span></span></label>
				<div id="embed-thumb"></div>
			</div>
		</form>
	</script>

	<script type="text/template" id="tmpl-featured-video">
		<form id="featured-video-form">
			<input type="hidden" name="type" value="video" />

			<p>Enter a video URL to get started.</p>
			<# var model = data.controller.model #>
			<div>
				<label for="url"><span>Video URL  <span class="spinner" style="display: none;"></span></label>
				<input type="text" class="url" name="url" <# if (model.get('type') == 'video') { #>value="{{ model.get('url') }}"<# } #>/>
				<p class="error"></p>
			</div>

			<div>
				<label for="embed"><span>Video embed code</span></label>
				<textarea name="embed"><# if (model.get('type') == 'video') { #>{{ model.get('embed') }}<# } #></textarea>
			</div>

			<div>
				<label><span>Video thumbnail</span></span></label>
				<div id="embed-thumb"></div>
			</div>

			<div>
				<label for="title"><span>Title</span></span></label>
				<input type="text" name="title" <# if (model.get('type') == 'video') { #>value="{{ model.get('title') }}"<# } #> />
			</div>

			<div>
				<label for="caption"><span>Caption</span></label>
				<input type="text" name="caption" <# if (model.get('type') == 'video') { #>value="{{ model.get('caption') }}"<# } #> />
			</div>

			<div>
				<label for="credit"><span>Credit</span></label>
				<input type="text" name="credit" <# if (model.get('type') == 'video') { #>value="{{ model.get('credit') }}"<# } #> />
			</div>

		</form>
	</script>

	<script type="text/template" id="tmpl-featured-thumb">
		<div class="thumb-container">
			<# if (typeof data.model.get('sizes') !== 'undefined') { #>
				<img src="{{ data.model.get('sizes').medium.url }}" title="Thumbnail: '{{ data.model.get('title') }}'" />
				<input type="hidden" name="attachment" value="{{ data.model.get('id') }}" />
			<# } else if (data.model.get('thumbnail_url')) { #>
				<img src="{{ data.model.get('thumbnail_url') }}" title="Thumbnail for '{{ data.model.get('title') }}'" />
				<input type="hidden" name="thumbnail_url" value="{{ data.model.get('thumbnail_url') }}" />
				<input type="hidden" name="thumbnail_type" value="oembed" />
			<# } #>
			<a href="#" class="remove-thumb">Remove thumbnail</a>
		</div>
	</script>

	<script type="text/template" id="tmpl-featured-image-override">
		<form>
			<input type="checkbox" name="featured-image-display" <# if (LFM.featured_image_display !== '') { #>checked="checked"<# } #>/> Override display of featured image for this post?
		</form>
	</script>

	<script type="text/template" id="tmpl-featured-remove-featured">
		<h1>Are you sure you want to remove featured media from this post?</h1>
	</script>
<?php }
add_action('admin_print_footer_scripts', 'largo_featured_media_templates', 1);

/**
 * Remove the default featured image meta box from post pages
 */
function largo_remove_featured_image_meta_box() {
	remove_meta_box('postimagediv', 'post', 'normal');
	remove_meta_box('postimagediv', 'post', 'side');
	remove_meta_box('postimagediv', 'post', 'advanced');
}
add_action('do_meta_boxes', 'largo_remove_featured_image_meta_box');

/**
 * AJAX functions
 */

/**
 * Read the `featured_media` meta for a given post. Expects array $_POST['data']
 * with an `id` key corresponding to post ID to look up.
 */
function largo_featured_media_read() {
	if (!empty($_POST['data'])) {
		$data = json_decode(stripslashes($_POST['data']), true);
		$ret = largo_get_featured_media($data['id']);

		// Otherwise, check for `featured_media` post meta
		if (!empty($ret)) {
			print json_encode($ret);
			wp_die();
		}

		// No featured thumbnail and not `featured_media`, so just return
		// an array with the post ID
		print json_encode(array('id' => $data['id']));
		wp_die();
	}
}
add_action('wp_ajax_largo_featured_media_read', 'largo_featured_media_read');

/**
 * Save `featured_media` post meta. Expects array $_POST['data'] with at least
 * an `id` key corresponding to the post ID that needs meta saved.
 */
function largo_featured_media_save() {
	if (!empty($_POST['data'])) {
		$data = json_decode(stripslashes($_POST['data']), true);

		// If an attachment ID is present, update the post thumbnail/featured image
		if (!empty($data['attachment']))
			set_post_thumbnail($data['id'], $data['attachment']);
		else
			delete_post_thumbnail($data['id']);

		// Get rid of the old youtube_url while we're saving
		$youtube_url = get_post_meta($data['id'], 'youtube_url', true);
		if (!empty($youtube_url))
			delete_post_meta($data['id'], 'youtube_url');

		// Set the featured image for embed or oembed types
		if (isset($data['thumbnail_url']) && isset($data['thumbnail_type']) && $data['thumbnail_type'] == 'oembed')
			$thumbnail_id = largo_media_sideload_image($data['thumbnail_url'], null);
		else if (isset($data['attachment']))
			$thumbnail_id = $data['attachment'];

		// If featured media is a gallery, use the first image as the representative thumbnail
		if ($data['type'] == 'gallery')
			$thumbnail_id = $data['gallery'][0];

		if (isset($thumbnail_id)) {
			update_post_meta($data['id'], '_thumbnail_id', $thumbnail_id);
			$data['attachment_data'] = wp_prepare_attachment_for_js($thumbnail_id);
		}

		// Don't save the post ID in post meta
		$save = $data;
		unset($save['id']);

		// Save what's sent over the wire as `featured_media` post meta
		$ret = update_post_meta($data['id'], 'featured_media', $save);

		print json_encode($data);
		wp_die();
	}
}
add_action('wp_ajax_largo_featured_media_save', 'largo_featured_media_save');

/**
 * Saves the option that determines whether a featured image should be displayed
 * at the top of the post page or not.
 */
function largo_save_featured_image_display() {
	if (!empty($_POST['data'])) {
		$data = json_decode(stripslashes($_POST['data']), true);

		$post_ID = (int) $data['id'];
		$post_type = get_post_type($post_ID);
		$post_status = get_post_status($post_ID);

		if ($post_type && isset($data['featured-image-display']) && $data['featured-image-display'] == 'on') {
			update_post_meta($post_ID, 'featured-image-display', 'false');
		} else {
			delete_post_meta($post_ID, 'featured-image-display');
		}
		print json_encode($data);
		wp_die();
	}
}
add_action('wp_ajax_largo_save_featured_image_display', 'largo_save_featured_image_display');

/**
 * When a URL is typed/pasted into the url field of the featured video view,
 * this function tries to fetch the oembed information for that video.
 */
function largo_fetch_video_oembed() {
	if (!empty($_POST['data'])) {
		$data = json_decode(stripslashes($_POST['data']), true);

		require_once( ABSPATH . WPINC . '/class-oembed.php' );
		$oembed = _wp_oembed_get_object();
		$url = $data['url'];
		$provider = $oembed->get_provider($url);
		$data = $oembed->fetch($provider, $url);
		$embed = $oembed->data2html($data, $url);
		$ret = array_merge(array('embed' => $embed), (array) $data);
		print json_encode($ret);
		wp_die();
	}
}
add_action('wp_ajax_largo_fetch_video_oembed', 'largo_fetch_video_oembed');

/**
 * Add post classes to indicate whether a post has featured media and what type it is
 *
 * @since 0.5.2
 */
function largo_featured_media_post_classes($classes) {
	global $post;

	$featured = largo_get_featured_media($post->ID);
	if (!empty($featured)) {
		$classes = array_merge($classes, array(
			'featured-media',
			'featured-media-' . $featured['type']
		));
	}

	return $classes;
}
add_filter('post_class', 'largo_featured_media_post_classes');
