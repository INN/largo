homepages/layouts/HomepageSingleWithSeriesStories.php
=====================================================

.. php:class:: HomepageSingleWithSeriesStories

      Homepage layout that provides one BIG featured story and several other stories
      from the same Series.

   .. php:method:: HomepageSingleWithSeriesStories::unregister_HomepageSingleWithSeriesStories()

      If the Series taxonomy is not enabled, this class should unregister itself.

      The default homepage layouts are listed in homepages/homepage.php.
      For each default layout, register_homepage_layout is called on that layout class.
      register_homepage_layout calls the HomepageLayoutFactory method register on that class instance
      the register method creates a new instance of that class
      therefore the __construct method below checks largo_is_series_enabled
      and adds an auto-removal function to the init hook of series are not enabled.