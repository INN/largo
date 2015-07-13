inc/sidebars.php
================

.. php:function:: largo_register_sidebars()

   Register our sidebars and other widget areas

    (currently in inc/wp-taxonomy-landing/functions/cftl-admin.php)

   :todo: move $he taxonomy landing page sidebar registration here

   :since: 0.3

.. php:function:: largo_get_excluded_sidebars()

   Returns sidebars that users should not be able to select for post, page and taxonomy layouts

.. php:function:: largo_get_custom_sidebar()

   Returns slug of custom sidebar that should be used

.. php:function:: largo_is_sidebar_required()

   Determines if is_single or is_singular context requires a sidebar