inc/sidebars.php
================

.. php:function:: largo_register_sidebars()

   Register our sidebars and other widget areas

    (currently in inc/wp-taxonomy-landing/functions/cftl-admin.php)

   :todo: move $he taxonomy landing page sidebar registration here

   :since: 0.3

.. php:function:: largo_custom_sidebars_dropdown()

   Builds a dropdown menu of the custom sidebars
   Used in the meta box on post/page edit screen
   $skip_default was deprecated in Largo 0.4

   :since: 0.3

.. php:function:: largo_landing_page_custom_sidebars_dropdown()

   Builds a dropdown menu of the custom sidebars for use on custom landing pages
   Used in the meta box on post/page edit screen
   $skip_default was deprecated in Largo 0.4

   :param $left_or_right $tring: one of 'left' or 'right' to signal which landing page region to build a dropdown for
   :param $selected $tring: the id of the sidebar that should be marked as selected when the dropdown is generated
   :param $post_id $nteger: optionally specify which custom landing page post ID you want to generate a dropdown for

   :since: 0.4

.. php:function:: largo_get_excluded_sidebars()

   Returns sidebars that users should not be able to select for post, page and taxonomy layouts

.. php:function:: largo_get_custom_sidebar()

   Returns slug of custom sidebar that should be used

.. php:function:: largo_is_sidebar_required()

   Determines if is_single or is_singular context requires a sidebar