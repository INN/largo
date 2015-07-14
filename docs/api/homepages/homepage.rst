homepages/homepage.php
======================

.. php:function:: largo_register_default_homepage_layouts()

   Registers all of the standard Largo homepage layout classes

.. php:function:: largo_get_home_layouts()

   Uses `$largo_homepage_factory` to build a list of homepage layouts. This list is used
   in Theme Options and Customizer to allow the user to choose a Homepage layout.

   :returns: array $n array of layouts, with friendly names as keys and arrays with 'path' and 'thumb' as values

.. php:function:: largo_get_home_thumb()

   Retrieves the thumbnail image for a homepage template, or a default

   :returns: string $he public url of the image file to use for the given template's screenshot

.. php:function:: largo_render_homepage_layout()

   Creates instance of a homepage layout class and renders it.

.. php:function:: largo_get_active_homepage_layout()

   Get the class name of the currently-active homepage layout

.. php:function:: largo_home_single_top()

   Get the post to display at the top of the home single template

.. php:function:: largo_home_featured_stories()

   Returns featured stories for the homepage.

.. php:function:: largo_home_series_stories_data()

   1. Gets 3 stories from the same series as the homepage's Big Story.
   2. Gets the term that the 3 series stories belong to.

   :returns: array $n array with `series_stories` and `series_stories_term` keys.

.. php:function:: largo_home_series_stories_term()

   Gets the homepage's Big Story series data and returns only the series stories' term.

.. php:function:: largo_home_series_stories()

   Gets the homepages Big Story series data and returns only the series stories.

.. php:function:: largo_home_get_single_featured_and_series()

   Returns the various posts for the homepage two and three panel layouts