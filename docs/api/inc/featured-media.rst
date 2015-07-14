inc/featured-media.php
======================

.. php:function:: largo_default_featured_media_types()

   Returns the default available featured media types

.. php:function:: largo_get_featured_media()

   Template helpers

.. php:function:: largo_has_featured_media()

   Helper function to tell if a post has featured media or not

   :param string $id: A post ID

   :returns: bool $f a post ID has featured media or not.

.. php:function:: largo_add_featured_media_button()

   Adds the "Set Featured Media" button above the post editor

.. php:function:: largo_featured_media_templates()

   Prints the templates used by featured media modal.

.. php:function:: largo_featured_media_css()

   Print featured media css

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