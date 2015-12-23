inc/ajax-functions.php
======================

.. php:function:: largo_load_more_posts_enqueue_script()

   Enqueue script for "load more posts" functionality

   :since: 0.5.3

   :global: LARGO_DEBUG

.. php:function:: largo_load_more_posts_data()

   Print an HTML script tag for a post navigation element and corresponding query

   If you plan on plugging this function, make sure the data structure it returns includes and "is_home" key.

   :param string $nav_id: The unique id of the navigation element used as the trigger to load more posts
   :param object $the_query: The WP_Query object upon which calls to load more posts will be based

.. php:function:: largo_load_more_posts()

   Renders markup for a page of posts and sends it back over the wire.

   :global: $opt

   :global: $_POST

   :see: largo_load_more_posts_choose_partial

.. php:function:: largo_load_more_posts_choose_partial()

   Function to determine which partial slug should be used by LMP to render posts.

   Includes a "largo_lmp_template_partial" filter to allow for modifying the value $partial.

   :param object $post_query: The query object being used to generate LMP markup

   :returns: string $partial The slug of partial that should be loaded.

   :global: $opt

   :global: $_POST

   :see: largo_load_more_posts