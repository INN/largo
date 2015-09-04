inc/featured-media.php
======================

.. php:function:: largo_default_featured_media_types()

   Returns the default available featured media types

.. php:function:: largo_hero()

   Prints DOM for hero image.

   Determines the type of featured media attached to a post,
   and generates the DOM for that type of media.

   :param int|WP_Post $post: Optional. Post ID or WP_Post object. Default is global $post.
   :param String $classes: Optional. Class string to apply to outer div.hero

.. php:function:: largo_get_hero()

   Return DOM for hero image.

   Determines the type of featured media attached to a post,
   and generates the DOM for that type of media.

   :param int|WP_Post $post: Optional. Post ID or WP_Post object. Default is global $post.
   :param String $classes: Optional. Class string to apply to outer div.hero

.. php:function:: largo_featured_image_hero()

   Prints DOM for a featured image hero.

   :since: 0.5.1

   :see: largo_get_featured_image_hero()
   :param int|WP_Post $post: Optional. Post ID or WP_Post object. Default is global $post.
   :param String $classes: Optional. Class string to apply to outer div.hero

.. php:function:: largo_get_featured_image_hero()

   Returns DOM for a featured image hero.

   :since: 0.5.1

   :see: largo_get_featured_image_hero()
   :param int|WP_Post $post: Optional. Post ID or WP_Post object. Default is global $post.
   :param String $classes: Optional. Class string to apply to outer div.hero

.. php:function:: largo_featured_embed_hero()

   Prints DOM for an embed code hero.

   :since: 0.5.1

   :see: largo_get_featured_embed_hero()
   :param int|WP_Post $post: Optional. Post ID or WP_Post object. Default is global $post.
   :param String $classes: Optional. Class string to apply to outer div.hero

.. php:function:: largo_get_featured_embed_hero()

   Returns DOM for an embed code hero.

   :since: 0.5.1
   :param int|WP_Post $post: Optional. Post ID or WP_Post object. Default is global $post.
   :param String $classes: Optional. Class string to apply to outer div.hero

.. php:function:: largo_featured_gallery_hero()

   Prints DOM for a featured gallery hero.

   :since: 0.5.1

   :see: largo_get_featured_gallery_hero()
   :param int|WP_Post $post: Optional. Post ID or WP_Post object. Default is global $post.
   :param String $classes: Optional. Class string to apply to outer div.hero

.. php:function:: largo_get_featured_gallery_hero()

   Returns DOM for a featured gallery hero.

   :since: 0.5.1
   :param int|WP_Post $post: Optional. Post ID or WP_Post object. Default is global $post.
   :param String $classes: Optional. Class string to apply to outer div.hero

.. php:function:: largo_get_featured_media()

   Returns information about the featured media.

   			'id' => int, 		// post id.
   			'type' => string, 	// the type of featured_media

   			// ... other variables, dependent on what the type is.

   		}

   :since: 0.4
   :param int|WP_Post $post: Optional. Post ID or WP_Post object. Default is global $post.

   :returns: array $post_type {

.. php:function:: largo_has_featured_media()

   Does the post have featured media?

   :param int|WP_Post $post: Optional. Post ID or WP_Post object. Default is global $post.

   :returns: bool $f a post ID has featured media or not.

.. php:function:: largo_add_featured_media_button()

   Adds the "Set Featured Media" button above the post editor

.. php:function:: largo_featured_media_templates()

   Prints the templates used by featured media modal.

.. php:function:: largo_remove_featured_image_meta_box()

   Remove the default featured image meta box from post pages

.. php:function:: largo_featured_media_save()

   Save `featured_media` post meta. Expects array $_POST['data'] with at least
   an `id` key corresponding to the post ID that needs meta saved.

.. php:function:: largo_save_featured_image_display()

   Saves the option that determines whether a featured image should be displayed
   at the top of the post page or not.

.. php:function:: largo_fetch_video_oembed()

   When a URL is typed/pasted into the url field of the featured video view,
   this function tries to fetch the oembed information for that video.

.. php:function:: largo_featured_media_post_classes()

   Add post classes to indicate whether a post has featured media and what type it is

   :since: 0.5.2